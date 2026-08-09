<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Purchase Order Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="max-w-2xl mx-auto py-10 px-4">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">Form Purchase Order</h1>
            <a href="{{ route('purchase-order.index') }}" class="text-sm text-gray-600 hover:underline">&larr; Kembali</a>
        </div>

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error!</strong> Mohon perbaiki kesalahan berikut:
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('purchase-order.store') }}" method="POST" class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Order</label>
                <input type="text" name="no_order" value="{{ old('no_order') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibutuhkan</label>
                <input type="date" name="tanggal_dibutuhkan" value="{{ old('tanggal_dibutuhkan') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" required>
            </div>

            <!-- Bagian Vendor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Vendor</label>
                <select name="m_vendor_id1" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-white focus:outline-none focus:ring-1 focus:ring-blue-500" required>
                    <option value="">-- Pilih Vendor --</option>
                    @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ old('m_vendor_id1') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->nama_vendor }}
                    </option>
                    @endforeach
                </select>
            </div>

            <hr class="border-gray-200 my-4">

            <h3 class="text-lg font-medium text-gray-800">Item Barang (Contoh Baris 1)</h3>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">SKU Barang</label>
                    <input type="text" name="items[0][m_barang_sku]" value="{{ old('items.0.m_barang_sku') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Kuantitas</label>
                    <input type="number" name="items[0][kuantitas]" value="{{ old('items.0.kuantitas') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Harga Unit</label>
                    <input type="number" name="items[0][harga_unit]" value="{{ old('items.0.harga_unit') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">Simpan Transaksi</button>
            </div>
        </form>
    </div>
</body>

</html>