<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Purchase_Detail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('purchase.index', [
            'title' => 'Purchase',
            'datas' => DB::table('vwpurchase')->get()
            
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('purchase.create', [
            'title' => 'Purchase',
            'distributors' => Distributor::all(),
            'products' => Product::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if($request->has('no_nota')) {
            $purchase = $request->only('no_nota', 'tgl_nota', 'id_distributor');
            $purchase['total_bayar'] = 0;
            $purchase = Purchase::create($purchase);
        }

        $purchaseDetails = $request->only('id_barang', 'harga_beli', 'margin_jual', 'jumlah_beli', 'subtotal');
        $purchaseDetails['id_pembelian'] = DB::table('purchases')->max('id');    
        $purchaseDetails = Purchase_Detail::create($purchaseDetails);

       return redirect()->route('purchase.index')->with('success', 'Purchase with invoice no  '. $request->no_nota .' has been created successfully.')->with('data', DB::table('purchases')->where('id', DB::table('purchases')->max('id'))->first());
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

    public function edit(Request $request, string $id)
    {
        //
        if ($request->ajax()) {
            $owner = User::where('role', 'owner')->first();
            
            if (!$owner || !Hash::check($request->boss_password, $owner->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong Boss Password! Access Denied.'
                ]);
            }

            session()->put('sudo_edit_granted_' . $id, true);

            return response()->json([
                'success' => true,
                'redirect_url' => route('purchase.edit', $id)
            ]);
        }


        if (!session()->has('sudo_edit_granted_' . $id)) {
            return redirect()->route('purchase.index')->with('gagal', 'Unauthorized! You must verify password first.');
        }

        return view('purchase.edit', [
            'title' => 'Purchase',
            'distributors' => Distributor::all(),
            'products' => Product::all(),
            'data' => DB::table('vwpurchase')->where('id', $id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        session()->forget('sudo_edit_granted_' . $id);

        Purchase::findOrFail($id)->update([
            'no_nota' => $request->no_nota,
            'tgl_nota' => $request->tgl_nota,
            'id_distributor' => $request->id_distributor,
            'total_bayar' => $request->total_bayar ?? 0,
        ]);

        // Update data Detail (Purchase_Details)
        DB::table('purchase__details')->where('id_pembelian', $id)->update([
            'id_barang' => $request->id_barang,
            'harga_beli' => $request->harga_beli,
            'margin_jual' => $request->margin_jual,
            'jumlah_beli' => $request->jumlah_beli,
            'subtotal' => $request->subtotal,
        ]);

        return redirect()->route('purchase.index')->with('ubah', 'Purchase data has been successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        //
        $owner = User::where('role', 'owner')->first();
        
        if (!$owner || !Hash::check($request->boss_password, $owner->password)) {
            return redirect()->route('purchase.index')->with('gagal', 'Delete Failed! Wrong Boss Password.');
        }

        DB::table('purchase__details')->where('id_pembelian', $id)->delete();
        Purchase::findOrFail($id)->delete();

        return redirect()->route('purchase.index')->with('hapus', 'Purchase data has been successfully deleted!');
    }
}
