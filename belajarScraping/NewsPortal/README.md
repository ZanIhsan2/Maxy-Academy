# News Portal Scraping

Scraper sederhana untuk mengambil judul, kategori, ringkasan, dan link berita dari ANTARA News.

## Persiapan

Pastikan Python dan library berikut tersedia:

```text
requests
beautifulsoup4
```

Install:

```powershell
py -m pip install requests beautifulsoup4
```

## Menjalankan

```powershell
cd "c:\xampp\htdocs\Maxy\belajarScraping\NewsPortal"
py scrape_newsportal.py
```

Secara default, scraper akan mengambil 10 artikel dari halaman utama ANTARA News dan menyimpannya ke file CSV bernama `antaranews_news.csv`.

Untuk mengubah jumlah artikel atau target URL:

```powershell
py scrape_newsportal.py --limit 20 --url "https://www.antaranews.com/"
```
