import re
import json
import asyncio
import sys
from datetime import datetime
from crawl4ai import AsyncWebCrawler
from bs4 import BeautifulSoup
from urllib.parse import urljoin

BASE_LIST_URL = "https://vietnamnet.vn/giao-duc/diem-thi/tra-cuu-diem-chuan-cd-dh-{year}-page{page}"

if len(sys.argv) >= 3:
    from_year = int(sys.argv[1])
    to_year = int(sys.argv[2])
else:
    current = datetime.now().year
    from_year = current
    to_year = current

YEARS = list(range(from_year, to_year + 1))


async def fetch_content(crawler, url):
    result = await crawler.arun(url=url)
    if not result:
        return ""

    return result.html or ""

async def get_list_urls(crawler):
    list_urls = []
    MAX_PAGE = 30

    for year in YEARS:
        page = 0
        while page <= MAX_PAGE:
            url = BASE_LIST_URL.format(year=year, page=page)
            print(f"[LIST] {url}", flush=True)

            try:
                content = await fetch_content(crawler, url)
            except Exception as e:
                print(f"Error fetching page {page} year {year}: {e}", flush=True)
                break

            if not content or "Không có dữ liệu" in content:
                print(f"Stop at year {year}, page {page} (no data)", flush=True)
                break

            soup = BeautifulSoup(content, "html.parser")

            # lấy tất cả <a> trong bảng
            links = []
            for a in soup.select("table a[href]"):
                href = a["href"]

                if "/truong/" in href:
                    full_url = urljoin("https://vietnamnet.vn", href)
                    links.append(full_url)

            if not links:
                print(f"Stop at year {year}, page {page} (no detail links)", flush=True)
                break

            list_urls.extend([(year, link) for link in links])
            page += 1

        print(f"Done year {year}, total pages: {page}", flush=True)

    return list(set(list_urls))

async def parse_detail(crawler, detail_urls):
    results = []

    for year, url in detail_urls:
        print(f"[DETAIL] {url}", flush=True)
        page = 0

        while True:
            # build paginated URL
            if "?" in url:
                base_url, query = url.split("?", 1)
            else:
                base_url, query = url, ""

            # rebuild từ URL gốc tránh bị append nhiều lần
            clean_base = re.sub(r"-page\d+", "", base_url)
            paged_url = f"{clean_base}-page{page}"
            if query:
                paged_url += f"?{query}"

            print(f"Fetching: {paged_url}", flush=True)

            try:
                content = await fetch_content(crawler, paged_url)
                if not content:
                    print(f"No content: {paged_url}", flush=True)
                    break

                if "KHÔNG TÌM THẤY ĐƯỜNG DẪN" in content:
                    print(f"Invalid page: {paged_url}", flush=True)
                    break

                soup = BeautifulSoup(content, "html.parser")

                # school name
                school_tag = soup.select_one(".detailSchoool__head-info h2")
                if school_tag:
                    # clone để không phá DOM
                    school_clone = BeautifulSoup(str(school_tag), "html.parser")
                    # remove <select> (năm)
                    for s in school_clone.select("select"):
                        s.decompose()

                    school_name = school_clone.get_text(strip=True)
                    school_name = re.sub(r"\(\w+\)", "", school_name).strip()
                else:
                    school_name = "Unknown School"

                # metadata
                metadata = {}
                info_items = soup.select(".detailSchoool__main-left li")

                for item in info_items:
                    label = item.select_one(".text-bold")
                    if not label:
                        continue

                    key = label.get_text(strip=True).replace(":", "")
                    values = item.find_all("p")
                    texts = [v.get_text(strip=True) for v in values if v != label]

                    metadata[key] = ", ".join(texts)

                # table
                table = soup.find("table")
                if not table:
                    print(f"No table found: {paged_url}", flush=True)
                    break

                rows = table.find("tbody").find_all("tr") if table.find("tbody") else table.find_all("tr")[1:]
                if not rows:
                    print(f"No rows: {paged_url}", flush=True)
                    break

                for row in rows:
                    tds = row.find_all("td")
                    if len(tds) < 3:
                        continue

                    # parse từng field
                    major_td = tds[1]
                    major = major_td.get_text(" ", strip=True)
                    major = major.replace("(Xem)", "").strip()

                    score_text = tds[2].get_text(strip=True)
                    level = tds[3].get_text(strip=True) if len(tds) > 3 else ""
                    block = tds[4].get_text(strip=True) if len(tds) > 4 else ""
                    note = tds[5].get_text(strip=True) if len(tds) > 5 else ""

                    try:
                        score_val = float(score_text.replace(",", "."))
                    except:
                        score_val = None

                    results.append({
                        "year": year,
                        "school": school_name,
                        "major": major,
                        "score": score_val,
                        "level": level,
                        "block": block,
                        "note": note,
                        "metadata": metadata,
                        "source_url": paged_url
                    })

                # next page
                page += 1

            except Exception as e:
                print(f"Error parsing {paged_url}: {e}", flush=True)
                break

    return results


async def main():
    print("Start crawling...", flush=True)

    async with AsyncWebCrawler() as crawler:
        list_urls = await get_list_urls(crawler)
        print(f"List pages: {len(list_urls)}")

        results = await parse_detail(crawler, list_urls)
        print(f"Parsed records: {len(results)}", flush=True)

        with open("storage/app/diem_chuan.json", "w", encoding="utf-8") as f:
            json.dump(results, f, ensure_ascii=False, indent=2)

    print("Done!", flush=True)


if __name__ == "__main__":
    asyncio.run(main())


