<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Products | {{ config('app.name', 'Sales') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.4/css/buttons.dataTables.min.css">
    <style>
        :root {
            --ink: #17212b;
            --muted: #6b7785;
            --line: #dce3e8;
            --accent: #e86f51;
            --wash: #f5f7f6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--wash);
            font-family: 'DM Sans', sans-serif;
        }

        h1,
        h2 {
            font-family: 'Space Grotesk', sans-serif;
        }

        .shell {
            max-width: 1220px;
            margin: 0 auto;
            padding: 34px 22px 60px;
        }

        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 28px;
        }

        .eyebrow {
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
        }

        h1 {
            margin: 5px 0 0;
            font-size: clamp(28px, 4vw, 44px);
            letter-spacing: -.03em;
        }

        .userbar {
            display: flex;
            align-items: center;
            gap: 14px;
            color: var(--muted);
            font-size: 13px;
        }

        .userbar a {
            color: var(--ink);
            font-weight: 600;
            text-decoration: none;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 12px 35px rgba(23, 33, 43, .06);
        }

        .form-panel {
            padding: 22px;
            margin-bottom: 22px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 12px;
            margin-bottom: 16px;
        }

        h2 {
            margin: 0;
            font-size: 18px;
        }

        .section-heading span {
            color: var(--muted);
            font-size: 13px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1.6fr 1fr 1fr 2fr auto;
            gap: 12px;
            align-items: end;
        }

        label {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        input,
        textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 5px;
            padding: 10px 11px;
            color: var(--ink);
            background: #fff;
            font: inherit;
            font-size: 13px;
        }

        input:focus,
        textarea:focus {
            outline: 2px solid rgba(232, 111, 81, .2);
            border-color: var(--accent);
        }

        button,
        .button {
            border: 0;
            border-radius: 5px;
            padding: 10px 14px;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .primary {
            background: var(--accent);
            color: #fff;
        }

        .primary:hover {
            background: #cf5940;
        }

        .table-panel {
            padding: 20px;
            overflow-x: auto;
        }

        .notice {
            padding: 12px 15px;
            margin-bottom: 18px;
            border: 1px solid #b9dfce;
            border-radius: 5px;
            color: #176044;
            background: #effaf5;
            font-size: 13px;
        }

        .error-list {
            padding: 12px 15px;
            margin-bottom: 18px;
            border: 1px solid #f2c6ba;
            border-radius: 5px;
            color: #9c3f2c;
            background: #fff5f2;
            font-size: 13px;
        }

        table.dataTable thead th {
            color: var(--muted);
            font-size: 11px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        table.dataTable tbody td {
            vertical-align: middle;
            font-size: 13px;
        }

        .edit-form {
            display: grid;
            grid-template-columns: 100px minmax(150px, 1fr) 145px 115px 85px minmax(150px, 1fr) auto;
            gap: 7px;
            align-items: center;
            min-width: 760px;
        }

        .edit-form input {
            padding: 7px 8px;
            font-size: 12px;
        }

        .actions {
            display: flex;
            gap: 6px;
        }

        .save {
            background: #1e6f65;
            color: #fff;
            padding: 7px 9px;
        }

        .delete {
            background: #fff1ee;
            color: #b64935;
            padding: 7px 9px;
        }

        .dataTables_wrapper .dt-buttons {
            margin-bottom: 12px;
        }

        .dt-button {
            border: 1px solid var(--line) !important;
            border-radius: 4px !important;
            background: #fff !important;
            color: var(--ink) !important;
            font-size: 12px !important;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid .full-mobile {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 560px) {
            .shell {
                padding: 24px 13px 40px;
            }

            .topbar {
                display: block;
            }

            .userbar {
                margin-top: 14px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <main class="shell">
        <header class="topbar">
            <div>
                <div class="eyebrow">Inventory workspace</div>
                <h1>Products</h1>
            </div>
            <div class="userbar"><span>{{ auth()->user()->name }}</span><a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
            </div>
        </header>

        @if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="error-list"><strong>Periksa input:</strong> {{ $errors->first() }}</div>@endif

        <section class="panel form-panel">
            <div class="section-heading">
                <h2>Tambah produk</h2><span>Produk baru masuk ke katalog aktif</span>
            </div>
            <form method="POST" action="{{ route('products.store') }}" class="form-grid">
                @csrf
                <div><label for="sku">SKU</label><input id="sku" name="sku" value="{{ old('sku') }}" required></div>
                <div><label for="name">Nama produk</label><input id="name" name="name" value="{{ old('name') }}" required></div>
                <div><label for="category_id">Kategori</label><select id="category_id" name="category_id" required>
                        <option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id')==$category->id)>{{ $category->name }}</option>@endforeach
                    </select></div>
                <div><label for="price">Harga</label><input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" required></div>
                <div><label for="stock">Stok</label><input id="stock" name="stock" type="number" min="0" value="{{ old('stock', 0) }}" required></div>
                <div class="full-mobile"><label for="description">Deskripsi</label><input id="description" name="description" value="{{ old('description') }}"></div>
                <button class="primary" type="submit">Simpan</button>
            </form>
        </section>

        <section class="panel table-panel">
            <div class="section-heading">
                <h2>Katalog produk</h2><span>Server-side processing aktif</span>
            </div>
            <table id="products-table" class="display" style="width:100%" data-endpoint="{{ route('products.data') }}" data-categories="{{ $categories->toJson() }}">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Deskripsi</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
            </table>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.print.min.js"></script>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const productsTable = document.querySelector('#products-table');
        const categories = JSON.parse(productsTable.dataset.categories);
        const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#039;',
            '"': '&quot;'
        } [character]));
        const categoryOptions = (selected) => '<option value="">Pilih kategori</option>' + categories.map(category => `<option value="${category.id}" ${Number(selected) === Number(category.id) ? 'selected' : ''}>${escapeHtml(category.name)}</option>`).join('');

        new DataTable('#products-table', {
            processing: true,
            serverSide: true,
            ajax: productsTable.dataset.endpoint,
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'print']
                }
            },
            pageLength: 10,
            order: [
                [0, 'asc']
            ],
            columns: [{
                    data: 'sku',
                    name: 'products.sku',
                    render: data => `<strong>${escapeHtml(data)}</strong>`
                },
                {
                    data: 'name',
                    name: 'products.name'
                },
                {
                    data: 'category_name',
                    name: 'categories.name',
                    render: data => escapeHtml(data || '-')
                },
                {
                    data: 'price',
                    name: 'products.price',
                    render: data => `Rp ${Number(data).toLocaleString('id-ID')}`
                },
                {
                    data: 'stock',
                    name: 'products.stock',
                    render: data => Number(data).toLocaleString('id-ID')
                },
                {
                    data: 'description',
                    name: 'products.description',
                    render: data => escapeHtml(data || '-')
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: data => {
                        const productUrl = `{{ url('/products') }}/${data.id}`;
                        return `<form method="POST" action="${productUrl}" class="edit-form"><input type="hidden" name="_token" value="${csrf}"><input type="hidden" name="_method" value="PUT"><input name="sku" value="${escapeHtml(data.sku)}" required><input name="name" value="${escapeHtml(data.name)}" required><select name="category_id" required>${categoryOptions(data.category_id)}</select><input name="price" type="number" min="0" step="0.01" value="${data.price}" required><input name="stock" type="number" min="0" value="${data.stock}" required><input name="description" value="${escapeHtml(data.description || '')}"><button class="save" type="submit">Update</button></form><form method="POST" action="${productUrl}" onsubmit="return confirm('Hapus produk ini?');" style="margin-top:6px;text-align:right;"><input type="hidden" name="_token" value="${csrf}"><input type="hidden" name="_method" value="DELETE"><button class="delete" type="submit">Hapus</button></form>`;
                    }
                }
            ],
            language: {
                search: 'Cari:',
                lengthMenu: '_MENU_ per halaman',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ produk',
                emptyTable: 'Belum ada produk.'
            }
        });
    </script>
</body>

</html>