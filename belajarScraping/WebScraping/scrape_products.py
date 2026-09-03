from __future__ import annotations

import argparse
import csv
import sys
from pathlib import Path

import requests
from bs4 import BeautifulSoup

DEFAULT_URL = "https://webscraper.io/test-sites/e-commerce/allinone/computers/laptops"
DEFAULT_OUTPUT = Path(__file__).with_name("laptops.csv")


def scrape_products(url: str) -> list[dict[str, str]]:
    """Return product data from one Web Scraper test-site category."""
    response = requests.get(
        url,
        headers={"User-Agent": "Mozilla/5.0 (compatible; learning-scraper/1.0)"},
        timeout=30,
    )
    response.raise_for_status()

    soup = BeautifulSoup(response.text, "html.parser")
    products: list[dict[str, str]] = []
    for card in soup.select('[itemscope][itemtype="https://schema.org/Product"]'):
        name_element = card.select_one('[itemprop="name"]')
        price_element = card.select_one('[itemprop="price"]')
        description_element = card.select_one('[itemprop="description"]')
        rating_element = card.select_one("[data-rating]")

        if not all((name_element, price_element, description_element, rating_element)):
            continue

        products.append(
            {
                "name": name_element.get("title") or name_element.get_text(" ", strip=True),
                "price": price_element.get_text(" ", strip=True),
                "rating": rating_element["data-rating"],
                "description": description_element.get_text(" ", strip=True),
            }
        )

    return products


def write_csv(products: list[dict[str, str]], output_path: Path) -> None:
    """Write products in a spreadsheet-friendly UTF-8 CSV."""
    output_path.parent.mkdir(parents=True, exist_ok=True)
    with output_path.open("w", newline="", encoding="utf-8-sig") as csv_file:
        writer = csv.DictWriter(
            csv_file,
            fieldnames=["name", "price", "rating", "description"],
        )
        writer.writeheader()
        writer.writerows(products)


def main() -> int:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--url", default=DEFAULT_URL, help="Category URL to scrape")
    parser.add_argument("--output", type=Path, default=DEFAULT_OUTPUT, help="CSV output path")
    args = parser.parse_args()

    try:
        products = scrape_products(args.url)
        if not products:
            raise RuntimeError("No complete product cards were found")
        write_csv(products, args.output)
    except (requests.RequestException, RuntimeError) as error:
        print(f"Scraping failed: {error}", file=sys.stderr)
        return 1

    print(f"Scraped {len(products)} products from {args.url}")
    print(f"Saved CSV: {args.output}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
