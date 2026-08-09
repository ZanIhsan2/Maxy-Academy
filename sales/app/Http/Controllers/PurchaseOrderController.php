<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Models\PurchaseOrder;
use App\Models\DetailPurchaseOrder;
use Illuminate\Support\Facades\DB;
use Exception;

class PurchaseOrderController extends Controller
{
    public function store(StorePurchaseOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. Simpan Header Purchase Order
            $purchaseOrder = PurchaseOrder::create([
                'no_order' => $request->no_order,
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
                'm_vendor_id1' => $request->m_vendor_id1,
            ]);

            // 2. Simpan Detail Purchase Order (Looping items)
            foreach ($request->items as $item) {
                DetailPurchaseOrder::create([
                    'm_purchase_order_id' => $purchaseOrder->id,
                    'm_barang_sku' => $item['m_barang_sku'],
                    'kuantitas' => $item['kuantitas'],
                    'harga_unit' => $item['harga_unit'],
                    'total_harga' => $item['kuantitas'] * $item['harga_unit'],
                ]);
            }

            // Jika sukses, commit database
            DB::commit();
            return redirect()->route('purchase-order.index')->with('success', 'Transaksi Purchase Order berhasil disimpan.');

        } catch (Exception $e) {
            // Jika gagal, batalkan semua perubahan
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $po = PurchaseOrder::with(['vendor', 'details'])->findOrFail($id);
        return view('purchasing.show', compact('po'));
    }
}