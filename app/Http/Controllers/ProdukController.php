<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Resep;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('id', 'desc')->get();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'stok_produk' => 'required|integer|min:0',
        ]);

        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Produk $produk)
    {
        // Untuk mengelola resep
        $produk->load('reseps.bahanBaku');
        $bahanBakuList = BahanBaku::all();
        
        // Auto kalkulasi HPP
        $hpp = 0;
        foreach($produk->reseps as $resep) {
            $hpp += ($resep->kuantitas_dibutuhkan * $resep->bahanBaku->harga_per_satuan);
        }
        
        // Update HPP Terakhir di db jika berubah
        if ($produk->hpp_terakhir != $hpp) {
            $produk->update(['hpp_terakhir' => $hpp]);
        }

        return view('produk.show', compact('produk', 'bahanBakuList', 'hpp'));
    }

    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
            'stok_produk' => 'required|integer|min:0',
        ]);

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Methods for Resep
    public function storeResep(Request $request, Produk $produk)
    {
        $validated = $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'kuantitas_dibutuhkan' => 'required|numeric|min:0.01',
        ]);

        $produk->reseps()->create($validated);

        return redirect()->route('produk.show', $produk->id)->with('success', 'Komponen bahan baku berhasil ditambahkan ke resep.');
    }

    public function destroyResep(Produk $produk, Resep $resep)
    {
        $resep->delete();
        return redirect()->route('produk.show', $produk->id)->with('success', 'Komponen bahan baku dihapus dari resep.');
    }
}
