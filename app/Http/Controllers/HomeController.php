<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get();
        return view('home', compact('products'));
    }

    public function kontak()
    {
        $store = \App\Models\Store::first();
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
            'quantity' => 'required|integer|min:1',
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

        if ($request->has('product_id') && $request->has('quantity') && $request->has('direct')) {
            $product = Product::findOrFail($request->product_id);
            $cart = [
                $request->product_id => [
                    'name' => $product->product_name,
                    'price' => $product->purchase_price,
                    'quantity' => (int) $request->quantity,
                    'image' => $product->image,
                ]
            ];
        }

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong!');
        }

        return view('checkout', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang kosong!');
        }

        $total = 0;
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if (!$product) {
                return redirect()->route('cart')->with('error', "Produk {$item['name']} tidak ditemukan.");
            }

            if ($product->stock < $item['quantity']) {
                return redirect()->route('cart')->with('error', "Stok produk {$item['name']} tidak mencukupi.");
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
        ]);

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            $product->decrement('stock', $item['quantity']);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('checkout.success', $transaction->id)
            ->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses.');
    }

    public function checkoutSuccess($id)
    {
        $transaction = Transaction::with('items.product')->findOrFail($id);
        return view('checkout.success', compact('transaction'));
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

        // Restore stock
        foreach ($transaction->items as $item) {
            $product = $item->product;
            $product->increment('stock', $item->quantity);
        }

        $transaction->delete();

        return redirect()->route('orders')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
