<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function index()
    {
        $products = Product::latest()->take(8)->get();
        return view('home', compact('products'));
    }

    public function kontak()
    {
        $store = Store::first();
        return view('kontak', compact('store'));
    }

    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products', compact('products', 'categories'));
    }

    public function Product($id)
    {
        $product = Product::findOrFail($id);
        return view('detail', compact('product'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($request->action === 'checkout') {
            return redirect()->route('checkout.page', ['product_id' => $request->product_id, 'quantity' => $request->quantity]);
        }

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            $cart[$request->product_id] = [
                'name' => $product->product_name,
                'price' => $product->purchase_price,
                'quantity' => $request->quantity,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    public function removeCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('cart')->with('success', 'Barang berhasil dihapus dari keranjang!');
    }

    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min=1',
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart')->with('success', 'Jumlah produk berhasil diperbarui!');
    }

    public function checkoutPage(Request $request)
    {
        $cart = session()->get('cart', []);
        $directCheckout = null;

        if ($request->has('product_id') && $request->has('quantity') && $request->has('direct')) {
            $product = Product::findOrFail($request->product_id);
            $directCheckout = [
                $request->product_id => [
                    'name' => $product->product_name,
                    'price' => $product->purchase_price,
                    'quantity' => (int) $request->quantity,
                    'image' => $product->image,
                ]
            ];
            session()->put('direct_checkout', $directCheckout);
        }

        if (empty($cart) && empty($directCheckout)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong!');
        }

        return view('Checkout', compact('cart', 'directCheckout'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        $directCheckout = session()->get('direct_checkout', []);

        // Determine which items to process
        $itemsToProcess = !empty($directCheckout) ? $directCheckout : $cart;

        if (empty($itemsToProcess)) {
            return redirect()->route('checkout.page')->with('error', 'Keranjang kosong!');
        }

        $total = 0;
        foreach ($itemsToProcess as $id => $item) {
            $product = Product::find($id);
            if (!$product) {
                return redirect()->route('checkout.page')->with('error', "Produk {$item['name']} tidak ditemukan.");
            }

            if ($product->stock < $item['quantity']) {
                return redirect()->route('checkout.page')->with('error', "Stok produk {$item['name']} tidak mencukupi.");
            }

            $total += $item['price'] * $item['quantity'];
        }

        $orderCode = 'ORD-' . time() . '-' . Auth::id();

        $transaction = Transaction::create([
            'order_code' => $orderCode,
            'customer_id' => Auth::id(),
            'address' => $request->address,
            'status' => 'pending',
            'total' => $total,
            'notes' => $request->notes,
            'payment_method' => 'midtrans',
            'payment_status' => 'pending',
        ]);

        foreach ($itemsToProcess as $id => $item) {
            $product = Product::find($id);
            $product->decrement('stock', $item['quantity']);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Create Midtrans Snap Token
        try {
            $customer = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
            ];

            $snapToken = $this->midtransService->createTransaction($transaction, $customer);
            $transaction->update(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            // Handle error - maybe log it and continue
            Log::error('Midtrans Snap Token creation failed: ' . $e->getMessage());
        }

        // Clear the appropriate session data
        if (!empty($directCheckout)) {
            session()->forget('direct_checkout');
        } else {
            session()->forget('cart');
        }

        return redirect()->route('checkout.payment', $transaction->id)
            ->with('success', 'Checkout berhasil! Silakan lakukan pembayaran.');
    }

    public function checkoutPayment($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);
        return view('Checkout.Payment', compact('transaction'));
    }

    public function checkoutSuccess($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);
        return view('Checkout.Success', compact('transaction'));
    }

    public function orders()
    {
        $transactions = Transaction::with('items.product')
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pesanan', compact('transactions'));
    }

    public function orderDetail($id)
    {
        $transaction = Transaction::with('items.product')
            ->where('customer_id', Auth::id())
            ->findOrFail($id);

        return view('detail-pesanan', compact('transaction'));
    }

    public function deleteOrder($id)
    {
        $transaction = Transaction::where('customer_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        foreach ($transaction->items as $item) {
            $product = $item->product;
            $product->increment('stock', $item->quantity);
        }

        $transaction->delete();

        return redirect()->route('orders')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
