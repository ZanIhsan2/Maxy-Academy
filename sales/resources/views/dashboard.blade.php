<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | {{ config('app.name', 'Sales') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink: #18181b;
            --muted: #71717a;
            --line: #e4e4e7;
            --surface: #ffffff;
            --page: #f7f7f8;
            --accent: #e86f51;
            --accent-soft: #f4f4f5;
            --success: #166534;
            --success-soft: #f0fdf4;
            --warning: #a16207;
            --warning-soft: #fefce8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--page);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3 {
            font-family: 'Space Grotesk', sans-serif;
        }

        a {
            color: inherit;
        }

        .shell {
            max-width: 1180px;
            margin: 0 auto;
            padding: 42px 28px 64px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 28px;
            margin-bottom: 34px;
            padding-bottom: 22px;
            border-bottom: 1px solid var(--line);
        }

        .eyebrow {
            color: var(--accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .13em;
            text-transform: uppercase;
        }

        h1 {
            margin: 4px 0 0;
            font-size: clamp(30px, 4vw, 42px);
            line-height: 1.05;
            letter-spacing: -.035em;
        }

        .userbar {
            display: flex;
            align-items: center;
            gap: 22px;
            color: var(--muted);
            font-size: 13px;
        }

        .userbar a {
            color: var(--ink);
            font-weight: 600;
            text-decoration: none;
        }

        .userbar a:hover {
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            border: 1px solid var(--line);
            background: var(--surface);
            margin-bottom: 24px;
        }

        .stat-card {
            min-width: 0;
            padding: 22px 20px;
            border-right: 1px solid var(--line);
        }

        .stat-card:last-child {
            border-right: 0;
        }

        .stat-card .label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .stat-card .icon {
            display: none;
        }

        .stat-card .value {
            margin-bottom: 5px;
            font-size: clamp(24px, 2vw, 31px);
            font-weight: 700;
            letter-spacing: -.035em;
            line-height: 1.1;
        }

        .stat-card .meta {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-card.accent,
        .stat-card.success,
        .stat-card.warning,
        .stat-card.neutral {
            border-top: 0;
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            padding: 24px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 16px;
            margin-bottom: 18px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--line);
        }

        h2 {
            margin: 0;
            font-size: 17px;
            letter-spacing: -.015em;
        }

        .section-heading span {
            color: var(--muted);
            font-size: 12px;
        }

        .item-list {
            display: grid;
            gap: 0;
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 15px 0;
            border-bottom: 1px solid var(--line);
        }

        .list-item:last-child {
            border-bottom: 0;
            padding-bottom: 2px;
        }

        .list-item:first-child {
            padding-top: 4px;
        }

        .item-main {
            min-width: 0;
        }

        .item-name {
            display: block;
            margin-bottom: 4px;
            font-weight: 600;
            line-height: 1.35;
        }

        .item-sub {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.5;
        }

        .pill {
            flex: 0 0 auto;
            min-width: 52px;
            padding: 5px 9px;
            border: 1px solid var(--line);
            border-radius: 5px;
            color: var(--muted);
            background: #fafafa;
            font-size: 10px;
            font-weight: 700;
            text-align: center;
        }

        .pill.success {
            border-color: #bbf7d0;
            background: var(--success-soft);
            color: var(--success);
        }

        .pill.warning {
            border-color: #fde68a;
            background: var(--warning-soft);
            color: var(--warning);
        }

        .empty-state {
            padding: 24px 0 4px;
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .stat-card:nth-child(2) {
                border-right: 0;
            }

            .stat-card:nth-child(-n+2) {
                border-bottom: 1px solid var(--line);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .shell {
                padding: 28px 16px 44px;
            }

            .topbar {
                display: block;
                margin-bottom: 26px;
            }

            .userbar {
                flex-wrap: wrap;
                gap: 12px 18px;
                margin-top: 18px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card,
            .stat-card:nth-child(2) {
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }

            .stat-card:last-child {
                border-bottom: 0;
            }

            .panel {
                padding: 20px;
            }

            .list-item {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <div class="shell">
        <div class="topbar">
            <div>
                <div class="eyebrow">Overview</div>
                <h1>Dashboard</h1>
            </div>
            <div class="userbar">
                <span>{{ auth()->user()->name }}</span>
                <a href="{{ route('products.index') }}">Products</a>
                <a href="{{ route('categories.index') }}">Categories</a>
            </div>
        </div>

        <div class="stats-grid">
            <article class="stat-card accent">
                <div class="label">
                    <span>Products</span>
                    <span class="icon">📦</span>
                </div>
                <div class="value">{{ number_format($stats['products']) }}</div>
                <div class="meta">Jumlah produk terdaftar</div>
            </article>

            <article class="stat-card success">
                <div class="label">
                    <span>Categories</span>
                    <span class="icon">🗂️</span>
                </div>
                <div class="value">{{ number_format($stats['categories']) }}</div>
                <div class="meta">Jumlah kategori aktif</div>
            </article>

            <article class="stat-card warning">
                <div class="label">
                    <span>Stock</span>
                    <span class="icon">📊</span>
                </div>
                <div class="value">{{ number_format($stats['stock']) }}</div>
                <div class="meta">Total item tersedia</div>
            </article>

            <article class="stat-card neutral">
                <div class="label">
                    <span>Inventory Value</span>
                    <span class="icon">💰</span>
                </div>
                <div class="value">Rp {{ number_format($stats['inventory_value'], 0, ',', '.') }}</div>
                <div class="meta">Nilai stok saat ini</div>
            </article>
        </div>

        <div class="content-grid">
            <section class="panel">
                <div class="section-heading">
                    <h2>Ringkasan kategori</h2>
                    <span>{{ $stats['categories'] }} kategori</span>
                </div>

                @if ($categorySummary->isNotEmpty())
                <div class="item-list">
                    @foreach ($categorySummary as $category)
                    <div class="list-item">
                        <div class="item-main">
                            <span class="item-name">{{ $category->name }}</span>
                            <span class="item-sub">{{ $category->description ?: 'Tanpa deskripsi' }}</span>
                        </div>
                        <span class="pill success">{{ $category->products_count }} produk</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">Belum ada kategori yang dibuat.</div>
                @endif
            </section>

            <section class="panel">
                <div class="section-heading">
                    <h2>Produk terbaru</h2>
                    <span>{{ $stats['products'] }} total</span>
                </div>

                @if ($recentProducts->isNotEmpty())
                <div class="item-list">
                    @foreach ($recentProducts as $product)
                    <div class="list-item">
                        <div class="item-main">
                            <span class="item-name">{{ $product->name }}</span>
                            <span class="item-sub">
                                {{ $product->category->name ?? 'Tanpa kategori' }} · {{ number_format($product->stock) }} stok
                            </span>
                        </div>
                        <span class="pill {{ $product->stock < 10 ? 'warning' : '' }}">
                            {{ $product->stock < 10 ? 'Low stock' : 'Ready' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">Belum ada produk yang ditambahkan.</div>
                @endif
            </section>
        </div>
    </div>
</body>

</html>