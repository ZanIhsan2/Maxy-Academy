<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Purchase Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="max-w-3xl mx-auto py-10 px-4">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">Detail Purchase Order: {{ $po->no_order }}</h1>
            <a href="{{ route('purchase-order.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali ke Daftar</a>
        </div>

        <!-- Informasi Header -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 space-y-3 text-sm mb-6">
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Nomor Order</span>
                <span class="font-medium">{{ $po->no_order }}</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-gray-500">Tanggal Dibutuhkan</span>
                <span class="font-medium">{{ $po->tanggal_dibutuhkan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Vendor</span>
                <span class="font-medium">{{ optional($po->vendor)->nama ?? $po->m_vendor_id1 }}</span>
            </div>
        </div>

        <!-- Tabel Rincian Item Barang -->
        <h3 class="text-lg font-bold mb-3">Daftar Item Barang</h3>
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-600">
                        <th class="py-3 px-4">SKU Barang</th>
                        <th class="py-3 px-4 text-center">Kuantitas</th>
                        <th class="py-3 px-4 text-right">Harga Unit</th>
                        <th class="py-3 px-4 text-right">Total Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($po->details as $detail)
                        <tr>
                            <td class="py-3 px-4 font-medium">{{ $detail->m_barang_sku }}</td>
                            <td class="py-3 px-4 text-center">{{ $detail->kuantitas }}</td>
                            <td class="py-3 px-4 text-right">Rp {{ number_format($detail->harga_unit, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-medium">Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>