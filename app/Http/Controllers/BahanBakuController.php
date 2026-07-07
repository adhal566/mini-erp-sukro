<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahanBaku = BahanBaku::orderBy('id', 'desc')->get();
        return view('bahan_baku.index', compact('bahanBaku'));
    }

    public function create()
    {
        return view('bahan_baku.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|in:kg,gram,pcs,liter',
            'harga_per_satuan' => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|numeric|min:0',
        ]);

        BahanBaku::create($validated);

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function edit(BahanBaku $bahanBaku)
    {
        return view('bahan_baku.edit', compact('bahanBaku'));
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan' => 'required|in:kg,gram,pcs,liter',
            'harga_per_satuan' => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|numeric|min:0',
        ]);

        $bahanBaku->update($validated);

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil diupdate.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        $bahanBaku->delete();
        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus.');
    }
}
