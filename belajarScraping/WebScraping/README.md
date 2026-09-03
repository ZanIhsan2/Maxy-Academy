# Web Scraper Test Sites

Scraper untuk tugas web scraping pada Web Scraper Test Sites.

## Hasil

- URL: https://webscraper.io/test-sites/e-commerce/allinone/computers/laptops
- Output: `laptops.csv`
- Produk: 117
- Kolom: `name`, `price`, `rating`, `description`

## Menjalankan

Pastikan Python dan dependensi berikut tersedia:

```text
requests
beautifulsoup4
```

Jalankan dari folder ini:

```powershell
python -m pip install requests beautifulsoup4
python scrape_products.py
```

URL dan nama output dapat diganti:

```powershell
python scrape_products.py --url "https://webscraper.io/test-sites/e-commerce/allinone/computers/tablets" --output tablets.csv
```

`laptops.csv` menggunakan UTF-8 with BOM agar langsung terbaca baik oleh Excel.
