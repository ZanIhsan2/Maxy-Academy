from __future__ import annotations

import argparse
import csv
import sys
from pathlib import Path
from urllib.parse import urljoin

import requests
from bs4 import BeautifulSoup

DEFAULT_URL = "https://www.antaranews.com/"
DEFAULT_OUTPUT = Path(__file__).with_name("antaranews_news.csv")
DEFAULT_LIMIT = 10

HEADERS = {
    "User-Agent": "Mozilla/5.0 (compatible; learning-scraper/1.0)",
    "Accept-Language": "id-ID,id;q=0.9,en;q=0.8",
}


def fetch_html(url: str) -> str:
    response = requests.get(url, headers=HEADERS, timeout=30)
    response.raise_for_status()
    return response.text


def normalize_url(url: str) -> str:
    if not url:
        return ""

    normalized = url.strip()
    if normalized.startswith("//"):
        normalized = "https:" + normalized
    if normalized.startswith("/"):
        normalized = urljoin(DEFAULT_URL, normalized)

    normalized = normalized.split("?", 1)[0]
    normalized = normalized.split("#", 1)[0]
    return normalized


def extract_article_links(html: str) -> list[tuple[str, str]]:
    soup = BeautifulSoup(html, "html.parser")
    links: list[tuple[str, str]] = []
    seen: set[str] = set()

    for anchor in soup.select("a[href]"):
        href = normalize_url(anchor.get("href", ""))
        text = anchor.get_text(" ", strip=True)

        if not href or not text:
            continue

        if not href.startswith("https://www.antaranews.com/berita/"):
            continue

        if len(text) < 12:
            continue

        if href in seen:
            continue

        seen.add(href)
        links.append((text, href))

    return links


def extract_article_details(article_url: str) -> dict[str, str]:
    html = fetch_html(article_url)
    soup = BeautifulSoup(html, "html.parser")

    title = ""
    title_tag = soup.select_one("h1") or soup.select_one("article h1")
    if title_tag:
        title = title_tag.get_text(" ", strip=True)

    if not title:
        title = soup.title.get_text(" ", strip=True) if soup.title else article_url

    description_obj = soup.select_one('meta[name="description"]')
    description = description_obj.get("content", "").strip() if description_obj else ""

    if not description:
        paragraphs = []
        for paragraph in soup.select("article p, .article-body p, .post-content p"):
            text = paragraph.get_text(" ", strip=True)
            if text and len(text) > 30:
                paragraphs.append(text)

        description = " ".join(paragraphs[:2]).strip()

    category = ""
    category_tag = soup.select_one('meta[property="article:section"]')
    if category_tag:
        category = category_tag.get("content", "").strip()

    if not category:
        category_tag = soup.select_one(".category, .label, .tags a")
        if category_tag:
            category = category_tag.get_text(" ", strip=True)

    return {
        "title": title,
        "link": article_url,
        "category": category,
        "summary": description,
    }


def write_csv(rows: list[dict[str, str]], output_path: Path) -> None:
    output_path.parent.mkdir(parents=True, exist_ok=True)

    with output_path.open("w", newline="", encoding="utf-8-sig") as csv_file:
        writer = csv.DictWriter(
            csv_file,
            fieldnames=["title", "category", "summary", "link"],
        )
        writer.writeheader()
        writer.writerows(rows)


def main() -> int:
    parser = argparse.ArgumentParser(
        description="Scrape top news articles from ANTARA News and save the results as CSV."
    )
    parser.add_argument("--url", default=DEFAULT_URL, help="Homepage URL to scrape")
    parser.add_argument(
        "--limit",
        type=int,
        default=DEFAULT_LIMIT,
        help="Number of article pages to fetch",
    )
    parser.add_argument(
        "--output",
        type=Path,
        default=DEFAULT_OUTPUT,
        help="CSV file path for the scraped results",
    )
    args = parser.parse_args()

    try:
        homepage_html = fetch_html(args.url)
        candidates = extract_article_links(homepage_html)

        if not candidates:
            raise RuntimeError("Tidak ada link berita yang ditemukan di halaman utama.")

        news_rows: list[dict[str, str]] = []
        for _, article_url in candidates[: args.limit]:
            try:
                article = extract_article_details(article_url)
            except requests.RequestException:
                continue

            if article["title"]:
                news_rows.append(article)

        if not news_rows:
            raise RuntimeError("Tidak ada artikel yang berhasil diproses.")

        write_csv(news_rows, args.output)

    except (requests.RequestException, RuntimeError) as error:
        print(f"Scraping gagal: {error}", file=sys.stderr)
        return 1

    print(f"Berhasil scraping {len(news_rows)} artikel dari {args.url}")
    print(f"Hasil disimpan ke {args.output}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
