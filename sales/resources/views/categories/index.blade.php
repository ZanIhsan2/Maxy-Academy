<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Categories | {{ config('app.name', 'Sales') }}</title>
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
            max-width: 1100px;
            margin: 0 auto;
            padding: 34px 22px 60px;
        }

        .topbar {
            display: flex;
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
            padding: 22px;
            margin-bottom: 22px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: 0 12px 35px rgba(23, 33, 43, .06);
        }

        .heading {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: baseline;
            margin-bottom: 16px;
        }

        h2 {
            margin: 0;
            font-size: 18px;
        }

        .heading span {
            color: var(--muted);
            font-size: 13px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 2fr auto;
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

        input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 5px;
            padding: 10px 11px;
            color: var(--ink);
            background: #fff;
            font: inherit;
            font-size: 13px;
        }

        input:focus {
            outline: 2px solid rgba(232, 111, 81, .2);
            border-color: var(--accent);
        }

        button {
            border: 0;
            border-radius: 5px;
            padding: 10px 14px;
            cursor: pointer;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .primary {
            background: var(--accent);
            color: #fff;
        }

        .notice,
        .error {
            padding: 12px 15px;
            margin-bottom: 18px;
            border-radius: 5px;
            font-size: 13px;
        }

        .notice {
            border: 1px solid #b9dfce;
            color: #176044;
            background: #effaf5;
        }

        .error {
            border: 1px solid #f2c6ba;
            color: #9c3f2c;
            background: #fff5f2;
        }

        .edit-form {
            display: grid;
            grid-template-columns: 1fr 2fr auto;
            gap: 7px;
            align-items: center;
            min-width: 500px;
        }

        .edit-form input {
            padding: 7px 8px;
            font-size: 12px;
        }

        .save {
            background: #1e6f65;
            color: #fff;
            padding: 7px 9px;
        }

        .delete {
            color: #b64935;
            background: #fff1ee;
            padding: 7px 9px;
            margin-top: 6px;
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

        table.dataTable tbody td {
            vertical-align: middle;
            font-size: 13px;
        }

        table.dataTable thead th {
            color: var(--muted);
            font-size: 11px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        @media (max-width:650px) {
            .topbar {
                display: block;
            }

            .userbar {
                margin-top: 14px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .shell {
                padding: 24px 13px 40px;
            }
        }
    </style>
</head>

<body>
    <main class="shell">
        <header class="topbar">
            <div>
                <div class="eyebrow">Inventory workspace</div>
                <h1>Categories</h1>
            </div>
            <div class="userbar"><span>{{ auth()->user()->name }}</span><a href="{{ route('products.index') }}">Products</a><a href="{{ route('dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
            </div>
        </header>
        @if(session('success'))<div class="notice">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="error"><strong>Periksa input:</strong> {{ $errors->first() }}</div>@endif
        <section class="panel">
            <div class="heading">
                <h2>Tambah kategori</h2><span>Kategori digunakan pada data produk</span>
            </div>
            <form method="POST" action="{{ route('categories.store') }}" class="form-grid">@csrf<div><label for="name">Nama kategori</label><input id="name" name="name" value="{{ old('name') }}" required></div>
                <div><label for="description">Deskripsi</label><input id="description" name="description" value="{{ old('description') }}"></div><button class="primary" type="submit">Simpan</button>
            </form>
        </section>
        <section class="panel">
            <div class="heading">
                <h2>Daftar kategori</h2><span>Server-side processing aktif</span>
            </div>
            <table id="categories-table" class="display" style="width:100%" data-endpoint="{{ route('categories.data') }}">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Dibuat</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
            </table>
        </section>
    </main>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script>
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const categoriesTable = document.querySelector('#categories-table');
        const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#039;',
            '"': '&quot;'
        } [character]));
        new DataTable('#categories-table', {
            processing: true,
            serverSide: true,
            ajax: categoriesTable.dataset.endpoint,
            layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'print']
                }
            },
            pageLength: 10,
            order: [
                [2, 'desc']
            ],
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description',
                    render: data => escapeHtml(data || '-')
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: data => data ? new Date(data).toLocaleDateString('id-ID') : '-'
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: data => {
                        const categoryUrl = `/categories/${data.id}`;
                        return `<form method="POST" action="${categoryUrl}" class="edit-form"><input type="hidden" name="_token" value="${csrf}"><input type="hidden" name="_method" value="PUT"><input name="name" value="${escapeHtml(data.name)}" required><input name="description" value="${escapeHtml(data.description || '')}"><button class="save" type="submit">Update</button></form><form method="POST" action="${categoryUrl}" onsubmit="return confirm('Hapus kategori ini?');" style="text-align:right;"><input type="hidden" name="_token" value="${csrf}"><input type="hidden" name="_method" value="DELETE"><button class="delete" type="submit">Hapus</button></form>`;
                    }
                }
            ],
            language: {
                search: 'Cari:',
                lengthMenu: '_MENU_ per halaman',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ kategori',
                emptyTable: 'Belum ada kategori.'
            }
        });
    </script>
</body>

</html>