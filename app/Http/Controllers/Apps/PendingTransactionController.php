<?php

namespace App\Http\Controllers\Apps;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Transaction;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest\StoreTransactionRequest;
use App\Models\PendingCart;
use Illuminate\Http\Request;

class PendingTransactionController extends Controller
{
    public function index()
    {
        $pendingTransactions = Transaction::where('status', 'Pending')->with('customer')->get();

        return Inertia::render('Apps/Pending-Transactions/Index', [
            'pendingTransactions' => $pendingTransactions,
        ]);
    }

    public function edit($id)
    {
        $pendingTransaction = Transaction::with('customer')->findOrFail($id);
        return Inertia::render('Apps/Pending-Transactions/Edit', [
            'pendingTransaction' => $pendingTransaction,
        ]);
    }
    public function Update(Transaction $transaction, $id, Request $request)
    {
        $this->validate($request, [
            'cash' => 'required',
        ]);

        $transaction = Transaction::findOrfail($id);

        $transaction->update([
            'status' => 'Paid',
            'cash' => $request->cash,
            'discount' => $request->discount,
            'change' => $request->change,
        ]);

        $carts = PendingCart::where('transaction_id', $transaction->id)->get(); //get data caers dari database berdasarkan kasir / user ID

        //insert transaction detail
        foreach ($carts as $cart) {
            //didalam perulangan ini kita insert data cart ke dalam transaction details
            //insert transaction detail
            $transaction->details()->create([
                'transaction_id' => $transaction->id,
                'product_id' => $cart->product_id,
                'qty' => $cart->qty,
                'price' => $cart->price,
            ]);

            //get price(disini kita mencari harga beli dan harga jual prodcut yang ada di dalam data cart)
            $total_buy_price = $cart->product->buy_price * $cart->qty;
            $total_sell_price = $cart->product->sell_price * $cart->qty;


            //get profits(data profit hasil penguarangan harga jual dan harga beli)
            $profits = $total_sell_price - $total_buy_price;

            //insert profits
            $transaction->profits()->create([
                'transaction_id' => $transaction->id,
                'total' => $profits
            ]); //kita insert ke dalam table profits


            //jangan lupa melakukan pengurangan stok product sesusai dengan qty yang dibeli
            $product = Product::find($cart->product_id);
            $product->stock = $product->stock - $cart->qty;
            $product->save();
        }
        PendingCart::where('transaction_id', $transaction->id)->delete();



        return redirect()->route('apps.pendingTransactions.index');
    }
}
