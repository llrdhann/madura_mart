<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('products.index', [
            'title' => 'Products',
            'datas' => Product::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('products.create', [
            'title' => 'Products'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $kd = DB::table('products')->where('kd_barang', $request->kd_barang)->value('kd_barang');
        $nama = DB::table('products')->where('nama_barang', $request->nama_barang)->value('nama_barang');

        if ($request->kd_barang == $kd) {
            return redirect()->route('products.create')->with('duplikat', 'Product ' . $request->kd_barang . ' data with the same name ' . $request->kd_barang . ' is already exists. Please use different data.')->withInput();
        }elseif ($request->nama_barang == $nama) {
            return redirect()->route('products.create')->with('duplikat', 'Product ' . $request->nama_barang . ' data with the same code ' . $request->nama_barang . ' is already exists. Please use different data.')->withInput();
        }else{
            //
            $data = $request->all();
            $data['foto_barang'] = $request->file('foto_barang')->store('product_images');
            Product::create($data);
            return redirect()->route('products.index')->with('simpan', 'The new Product Data, ' . $request->nama_barang . ', has been succesfully saved');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('products.edit', [
            'title' => 'Products',
            'data' => Product::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $product_lama = DB::table('products')->where('id', $id)->value('nama_barang');
        $kd = DB::table('products')->where('kd_barang', $request->kd_barang)->value('kd_barang');
        $nama = DB::table('products')->where('nama_barang', $request->nama_barang)->value('nama_barang');
        $jenis = DB::table('products')->where('jenis_barang', $request->jenis_barang)->value('jenis_barang');
        $tgl_expired = DB::table('products')->where('tgl_expired', $request->tgl_expired)->value('tgl_expired');
        $harga = DB::table('products')->where('harga_jual', $request->harga_jual)->value('harga_jual');
        $stok = DB::table('products')->where('stok', $request->stok_barang)->value('stok');
        $foto = DB::table('products')->where('foto_barang', $request->foto_barang)->value('foto_barang');

        if ($request->kd_barang == $kd && $request->nama_barang == $nama && $request->jenis_barang == $jenis && $request->tgl_expired == $tgl_expired && $request->harga_jual == $harga && $request->stok_barang == $stok && $request->foto_barang == $foto) {
            return redirect()->route('products.edit', $id)->with('duplikat', 'Product ' . $request->nama_barang . ' data with the same code ' . $request->kd_barang . ', type ' . $request->jenis_barang . ', expired date ' . $request->tgl_expired . ', price ' . number_format($request->harga_jual, 0, ',', '.') . ', stock ' . number_format($request->stok_barang, 0, ',', '.') . ', and image ' . basename($request->foto_barang) . ' is already exists. Please use different data.');
        }else{
            //
            $data = $request->all();
            $product = Product::findOrFail($id);
            $product->update($data);
            return redirect()->route('products.index')->with('ubah', 'The Product Data, ' . $product_lama . ' become ' . $request->nama_barang . ', has been succesfully updated');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $nama_barang = DB::table('products')->where('id', $id)->value('nama_barang');
        Product::findOrFail($id)->delete();
        return redirect()->route('products.index')->with('hapus', 'The Product Data, ' . $nama_barang . ', has been succesfully deleted');
    }
}
