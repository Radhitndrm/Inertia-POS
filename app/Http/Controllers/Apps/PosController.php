<?php

namespace App\Http\Controllers\Apps;

//import helper str dari laravel, helper ini nanti akan digunnakan untuk membuat text menjadi uppercase/capital
use Illuminate\Support\Str;

//mengimport adapter dari inertia
use Inertia\Inertia;


use App\Http\Controllers\Controller;

//mengimport 4 model(cart,product,customer,transaction)
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\TransactionRequest\StoreTransactionRequest;
use App\Models\Transaction;
use App\Models\Customer;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class PosController extends Controller
{
    //method function akan digunakan untuk menampilkan halaman transaksi, dimana halaman ini akan menapilkan beberapa data,seperti kasir, list customers
    //carts, dll.
    public function index(Request $request)
    {
        //get cart
        $carts = Cart::with('product')->where('cashier_id', auth()->user()->id)->latest()->get();
        //di atas, kita melakukan get data carts ke dalam database menggunakan model Cart dan kita juga memanggil relasi product menggunakan with
        //dan kita akan tampilkan data-nya sesuai dengan kasir/ user yang sedang login, jadi masing2 kasir memiliki item cart yang berbeda beda


        $categories = Category::all();

        $products = Product::when(request()->q, function ($query) use ($request) {
            return $query->whereCategory_id($request->q);
        })->latest()->paginate(6);

        //Setelah itu, kita return ke dalam view / component dengan mengirimkan beberapa props yg akan dipanggil di halaman vue
        return Inertia::render('Apps/Pos/Index', [
            'carts' => $carts,
            'carts_total' => $carts->sum('price'),
            'categories' => $categories,
            'products' => $products,

        ]);
        //Di atas, kita mengirimkan 3 data props, yaitu carts yang berisi list item cart, kemudian carts_total akan berisi total dari item yang ada di dlm carts. Dan customers merupakan
        //list data customers
    }

    //method ini akan digunakan untuk melakukan proses add to cart item product
    public function addToCart(Request $request)
    {

        $product = Product::whereId($request->product_id)->first();

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found!');
        }

        if ($product->stock < $request->qty) {
            return redirect()->back()->with('error', 'Out of Stock Product!');
        }

        //check cart
        $cart = Cart::with('product')
            ->where('product_id', $request->product_id)
            ->where('cashier_id', auth()->user()->id)
            ->first();
        //melakukan mengecekan apakah product yang akan dimasukan ke carts itu sudah ada atau belum, ini agar tidak terjadi duplikasi barang dalam 

        if ($cart) {

            $cart->increment('qty', $request->qty);

            $cart->price = $cart->product->sell_price * $cart->qty;

            $cart->save();

            //apabila product sudah ada maka tinggal diupdate saja qty dan harganya
        } else {

            Cart::create([
                'cashier_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'qty' => $request->qty,
                'price' => $request->sell_price * $request->qty,
            ]);

            return redirect()->route('apps.pos.index')->with('success', 'Product Added Successfully!');
            //dan apabila belum, maka lakukan insert item baru ke dlm cart dgn mengembalikan pesan success
        }
    }

    //method ini akan digunakan untuk melakukan hapus item dari data cart berdasarkan ID
    public function destroyCart(Request $request)
    {
        $cart = Cart::with('product')
            ->whereId($request->cart_id)
            ->first();

        $cart->delete();

        return redirect()->back()->with('success', 'Product Removed Successfully!');
        //di atas kita mencari data cart bedasarkan ID yang di dapatkan dari request yang bernama cart_id dan jika data ditemukan, maka kita akan menghapusnya dari databsase
    }

    //method inio yang nanti akan digunakan untuk melakukan proses insert data transaksi
    public function store(Request $request)
    {


        //pertama-tama kita membuat dulu no invoicenya
        $length = 10;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
        }

        //generate no invoice
        $invoice = 'TRX-' . Str::upper($random);
        //awalan dari no invoicenya ialah 'TRX-' dan akan dilanjutin dengan no random sesuai dengan perulangan diatas

        //dikarenakan tidak menggunakan category_id lagi, dan menggunaan nama dan tidak terhubung ke model customer
        $customers = Customer::create([
            'name' => $request->customer
        ]);



        //insert transaction
        $transaction = Transaction::create([
            'cashier_id' => auth()->user()->id,
            'status' => 'Paid',
            'customer_id' => $customers->id,
            'invoice' => $invoice,
            'cash' => $request->cash,
            'change' => $request->change,
            'discount' => $request->discount,
            'grand_total' => $request->grand_total,
        ]); //kemudian kita insert data transaksinya ke dalam databse menggunakan model

        $carts = Cart::where('cashier_id', auth()->user()->id)->get(); //get data caers dari database berdasarkan kasir / user ID

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

        //delete carts(setelah data berhasil disimpan semuanya maka tinggal lanjutkan mengosongkan data cart)
        Cart::where('cashier_id', auth()->user()->id)->delete();

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]); //terkahir kita menggunakan format JSON dengab mengitimkan balik data transaksasi yang dibuat
    }

    //method ini akan digunakan untuk melakukan cetak nota dan pembelian dari transaksi
    public function print(Request $request)
    {
        //get transaction(disini kita akan melakukan get data transaksi berdasarrkan nomor invoice)
        $transaction  = Transaction::with('details.product', 'cashier')->where('invoice', $request->invoice)->firstOrFail();

        //return view
        return view('print.nota', compact('transaction')); //setelah itu kita return ke dlaam view dengan mengirimkan data transaksi tersebut unutk nanti kita print
    }
}
