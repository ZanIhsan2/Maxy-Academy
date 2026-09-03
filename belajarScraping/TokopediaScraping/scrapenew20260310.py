import re
from pathlib import Path
from urllib.parse import quote, urlsplit, urlunsplit

import pandas as pd
from playwright.sync_api import (
    TimeoutError as PlaywrightTimeoutError,
    sync_playwright,
)


# ============================================================
# CONFIGURATION
# ============================================================

QUERY = "mouse b100"
MAX_ITEMS = 200
ROWS = 60

OUTPUT_FILE = Path(__file__).with_name("tokopedia_results.csv")

CHROME_PATH = (
    r"C:\Program Files\Google\Chrome\Application\chrome.exe"
)


# ============================================================
# HELPER FUNCTIONS
# ============================================================

def clean_product_link(link: str) -> str:
    """Membersihkan query dan fragment dari URL produk."""
    parts = urlsplit(link)

    return urlunsplit(
        (
            parts.scheme,
            parts.netloc,
            parts.path,
            "",
            "",
        )
    )


def parse_product(text: str, link: str) -> dict[str, str] | None:
    """Mengambil informasi produk dari teks card."""
    lines = [
        line.strip()
        for line in text.splitlines()
        if line.strip()
    ]

    # Cari posisi harga
    price_index = next(
        (
            index
            for index, line in enumerate(lines)
            if line.startswith("Rp")
        ),
        None,
    )

    if price_index is None:
        return None

    # Cari posisi "terjual"
    sold_index = next(
        (
            index
            for index, line in enumerate(lines)
            if "terjual" in line.lower()
        ),
        None,
    )

    if sold_index is None or sold_index + 2 >= len(lines):
        return None

    # Nama produk
    name = " ".join(
        line
        for line in lines[:price_index]
        if not re.fullmatch(r"\d+%", line)
    )

    if not name:
        return None

    # Rating
    rating = next(
        (
            line
            for line in lines[price_index + 1:sold_index]
            if re.fullmatch(r"\d(?:\.\d)?", line)
        ),
        "",
    )

    return {
        "name": name,
        "price": lines[price_index],
        "shop": lines[sold_index + 1],
        "location": lines[sold_index + 2],
        "rating": rating,
        "link": clean_product_link(link),
    }


def scroll_page(page) -> None:
    """Melakukan auto-scroll untuk memuat produk tambahan."""
    last_height = 0

    for _ in range(10):
        page.mouse.wheel(0, 3000)
        page.wait_for_timeout(2000)

        new_height = page.evaluate(
            "document.body.scrollHeight"
        )

        if new_height == last_height:
            break

        last_height = new_height


# ============================================================
# SCRAPER
# ============================================================

def scrape_products() -> list[dict[str, str]]:
    products_all: list[dict[str, str]] = []
    seen_links: set[str] = set()

    with sync_playwright() as playwright:

        browser = playwright.chromium.launch(
            executable_path=CHROME_PATH,
            headless=False,
            args=[
                "--disable-http2",
                "--disable-quic",
            ],
        )

        page = browser.new_page(
            viewport={
                "width": 1366,
                "height": 768,
            },
            locale="id-ID",
            timezone_id="Asia/Jakarta",
        )

        total_pages = (
            MAX_ITEMS + ROWS - 1
        ) // ROWS

        for page_number in range(1, total_pages + 1):

            # ------------------------------------------------
            # URL
            # ------------------------------------------------

            url = (
                "https://www.tokopedia.com/search"
                f"?st=product&q={quote(QUERY)}"
            )

            if page_number > 1:
                url += f"&page={page_number}"

            # ------------------------------------------------
            # OPEN PAGE
            # ------------------------------------------------

            try:
                page.goto(
                    url,
                    wait_until="commit",
                    timeout=60000,
                )

                page.wait_for_timeout(3000)
                scroll_page(page)

            except PlaywrightTimeoutError:
                print(
                    f"Halaman {page_number}: "
                    "timeout atau hasil tidak tersedia"
                )
                break

            # ------------------------------------------------
            # FIND PRODUCT CARDS
            # ------------------------------------------------

            cards = page.locator(
                'a[href*="tokopedia.com/"]'
            )

            card_count = cards.count()

            print(
                f"Candidate cards: {card_count}"
            )

            # ------------------------------------------------
            # PARSE PRODUCTS
            # ------------------------------------------------

            page_count = 0

            for index in range(card_count):

                if len(products_all) >= MAX_ITEMS:
                    break

                card = cards.nth(index)

                try:
                    text = card.inner_text(
                        timeout=5000
                    )

                    link = (
                        card.get_attribute("href")
                        or ""
                    )

                    product = parse_product(
                        text,
                        link,
                    )

                    if not product:
                        continue

                    product_link = product["link"]

                    # Hindari duplikat
                    if product_link in seen_links:
                        continue

                    seen_links.add(product_link)
                    products_all.append(product)
                    page_count += 1

                except Exception:
                    continue

            # ------------------------------------------------
            # PROGRESS
            # ------------------------------------------------

            print(
                f"Halaman {page_number}: "
                f"{page_count} produk, "
                f"total {len(products_all)}"
            )

            # Tidak ada produk baru
            if page_count == 0:
                break

            # Target sudah tercapai
            if len(products_all) >= MAX_ITEMS:
                break

        browser.close()

    return products_all[:MAX_ITEMS]


# ============================================================
# SAVE DATA
# ============================================================

try:
    products_all = scrape_products()

except Exception as error:
    print(
        f"Scraping Tokopedia gagal: {error}"
    )
    products_all = []


df = pd.DataFrame(
    products_all,
    columns=[
        "name",
        "price",
        "shop",
        "location",
        "rating",
        "link",
    ],
)

df.to_csv(OUTPUT_FILE, 
          index=False, 
          encoding="utf-8-sig",
    )

print(
    f"Saved {len(df)} products to {OUTPUT_FILE}"
)