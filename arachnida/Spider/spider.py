import argparse
import os
import requests
from urllib.parse import urljoin, urlparse

IMAGE_EXT = ('.jpg', '.jpeg', '.png', '.gif', '.bmp')

def extract_images(page):
    # Simple string search for <img src="...">
    imgs = []
    idx = 0
    while True:
        idx = page.find('<img', idx)
        if idx == -1:
            break
        src_idx = page.find('src=', idx)
        if src_idx == -1:
            idx += 4
            continue
        quote = page[src_idx+4]
        if quote not in "\"'":
            idx = src_idx + 4
            continue
        end_quote = page.find(quote, src_idx+5)
        if end_quote == -1:
            idx = src_idx + 5
            continue
        img_url = page[src_idx+5:end_quote]
        imgs.append(img_url)
        idx = end_quote + 1
    return imgs

def extract_links(page):
    # Simple string search for <a href="...">
    links = []
    idx = 0
    while True:
        idx = page.find('<a', idx)
        if idx == -1:
            break
        href_idx = page.find('href=', idx)
        if href_idx == -1:
            idx += 2
            continue
        quote = page[href_idx+5]
        if quote not in "\"'":
            idx = href_idx + 5
            continue
        end_quote = page.find(quote, href_idx+6)
        if end_quote == -1:
            idx = href_idx + 6
            continue
        link_url = page[href_idx+6:end_quote]
        links.append(link_url)
        idx = end_quote + 1
    return links

def download_image(img_url, path):
    try:
        os.makedirs(path, exist_ok=True)
        filename = os.path.join(path, os.path.basename(urlparse(img_url).path))
        if not os.path.exists(filename):
            resp = requests.get(img_url, timeout=5)
            if resp.status_code == 200:
                with open(filename, 'wb') as f:
                    f.write(resp.content)
    except Exception:
        pass

def normalize_url(url):
    return url.split('#')[0]

def spider_bfs(start_url, path, max_depth, recursive):
    visited = set()
    current_level = [start_url]
    base_domain = urlparse(start_url).netloc
    depth = 0
    headers = {'User-Agent': 'Mozilla/5.0 (compatible; SpiderBot/1.0)'}
    print(f"Starting crawl at: {start_url}")

    while current_level and (not recursive or depth < max_depth):
        print(f"Depth {depth}: {len(current_level)} URLs to visit")
        next_level = []
        for url in current_level:
            norm_url = normalize_url(url)
            if norm_url in visited:
                continue
            visited.add(norm_url)
            try:
                resp = requests.get(norm_url, timeout=5, headers=headers)
                if resp.status_code != 200:
                    continue
                page = resp.text
            except Exception as e:
                print(f"Failed to fetch {norm_url}: {e}")
                continue

            for img in extract_images(page):
                full_img = urljoin(norm_url, img)
                if full_img.lower().endswith(IMAGE_EXT):
                    print(f"Downloading image: {full_img}")
                    download_image(full_img, path)

            if recursive:
                for link in extract_links(page):
                    full_url = normalize_url(urljoin(norm_url, link))
                    if urlparse(full_url).netloc == base_domain and full_url not in visited:
                        next_level.append(full_url)
        current_level = next_level
        depth += 1





def main():
    parser = argparse.ArgumentParser(description="Depth-limited image spider")
    parser.add_argument("url", help="Target URL to crawl")
    parser.add_argument("-r", action="store_true", help="Recursive crawl")
    parser.add_argument("-l", type=int, default=5, help="Max depth (works only with -r)")
    parser.add_argument("-p", default="./data/", help="Download path")
    args = parser.parse_args()

    if args.l != 5 and not args.r:
        parser.error("-l can only be used with -r")

    spider_bfs(args.url, args.p, args.l, args.r)

if __name__ == "__main__":
    main()