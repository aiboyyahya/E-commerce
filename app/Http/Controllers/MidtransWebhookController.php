<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Midtrans Webhook Received', $payload);


            $orderId = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];
            $paymentType = $payload['payment_type'];
            $fraudStatus = $payload['fraud_status'] ?? null;

            $transaction = Transaction::where('order_code', $orderId)->first();

            if (!$transaction) {
                Log::error('Transaction not found for order_id: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }

            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $transaction->update(['payment_status' => 'pending']);
                    } else if ($fraudStatus == 'accept') {
                        $transaction->update(['payment_status' => 'paid', 'status' => 'packing']);
                    }
                    break;
                case 'settlement':
                    $transaction->update(['payment_status' => 'paid', 'status' => 'packing']);
                    break;
                case 'pending':
                    $transaction->update(['payment_status' => 'pending']);
                    break;
                case 'deny':
                    $transaction->update(['payment_status' => 'failed']);
                    break;
                case 'expire':
                    $transaction->update(['payment_status' => 'expired']);
                    break;
                case 'cancel':
                    $transaction->update(['payment_status' => 'failed']);
                    break;
                default:
                    Log::warning('Unknown transaction status: ' . $transactionStatus);
                    break;
            }

            Log::info('Transaction updated', [
                'order_id' => $orderId,
                'payment_status' => $transaction->payment_status,
                'status' => $transaction->status
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Processing failed'], 500);
        }
    }
}
