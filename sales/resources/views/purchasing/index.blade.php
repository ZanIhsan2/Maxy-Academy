<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold tracking-tight">Daftar Purchase Order</h1>
            <a href="{{ route('purchase-order.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">+ Buat PO Baru</a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-600">
                        <th class="py-3 px-4">No. Order</th>
                        <th class="py-3 px-4">Tanggal Dibutuhkan</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($purchaseOrders as $po)
                        <tr>
                            <td class="py-3 px-4 font-medium">{{ $po->no_order }}</td>
                            <td class="py-3 px-4">{{ $po->tanggal_dibutuhkan }}</td>
                            <!-- Mengambil nama vendor dari relasi model, fallback ke ID jika relasi null -->
                            <td class="py-3 px-4">{{ optional($po->vendor)->nama ?? $po->m_vendor_id1 }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('purchase-order.show', $po->id) }}" class="text-blue-600 hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 px-4 text-center text-gray-500">Belum ada data Purchase Order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>