<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $produk = Produk::where('stok_produk', '>', 0)->get();
        return view('pos.index', compact('produk'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:cash,qris',
            'total_pembayaran' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Setup dummy user jika belum ada
            $user = User::first();
            if (!$user) {
                $user = User::create([
                    'name' => 'Admin Kasir',
                    'email' => 'admin@sukro.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin'
                ]);
            }

            $transaksi = Transaksi::create([
                'user_id' => $user->id, 
                'nomor_invoice' => 'INV-' . strtoupper(Str::random(8)),
                'total_pembayaran' => $validated['total_pembayaran'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status' => 'success',
                'tanggal_transaksi' => now()
            ]);

            foreach ($validated['items'] as $item) {
                $produk = Produk::lockForUpdate()->find($item['id']);
                
                if ($produk->stok_produk < $item['qty']) {
                    throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi.");
                }

                // Kurangi stok
                $produk->decrement('stok_produk', $item['qty']);

                // Record transaksi detail (Snapshot HPP & Profit real-time)
                $transaksi->detail()->create([
                    'produk_id' => $produk->id,
                    'kuantitas' => $item['qty'],
                    'harga_satuan' => $produk->harga_jual,
                    'hpp_satuan' => $produk->hpp_terakhir,
                    'subtotal_harga' => $produk->harga_jual * $item['qty'],
                    'subtotal_profit' => ($produk->harga_jual - $produk->hpp_terakhir) * $item['qty']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Transaksi berhasil diproses.',
                'invoice' => $transaksi->nomor_invoice
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function history()
    {
        $transaksi = Transaksi::with(['detail.produk', 'user'])->orderBy('tanggal_transaksi', 'desc')->paginate(15);
        return view('pos.history', compact('transaksi'));
    }
}
