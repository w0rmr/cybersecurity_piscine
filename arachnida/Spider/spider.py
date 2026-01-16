import argparse
import os
import requests
import re

def download_image(url, path):
    pass


def spider( depth,  path,  recursive,  url):
    lol = requests.get(url)
    # print(lol.text)
    links = re.findall(r'href="(.*?)"', lol.text)
    for link in links:
        if(link .endswith(('.jpg', '.jpeg', '.png', '.gif'))):
            print("image",link)
            # img_data = requests.get(link).content
            # if not os.path.exists(path):
            #     os.makedirs(path)
            # with open(os.path.join(path, link.split("/")[-1]), 'wb') as handler:
            #     handler.write(img_data)
            # print(f"Downloaded: {link}")
        else:
            print("depth",depth)
            if( recursive and depth>0):
                print("link",link)
                spider(depth, path, recursive, link)
                depth -= 1
            # if(recursive and depth>0):
            #     if not link.startswith("http"):
            #         if url.endswith("/"):
            #             link = url + link
            #         else:
            #             link = url + "/" + link
            #     spider(depth-1, path, recursive, link)
        
    

def main():
    parser = argparse.ArgumentParser(
        description="Spider: download images from a website"
    )

    # Optional positional argument (URL)
    parser.add_argument(
        "url",
        nargs="?",
        help="Target URL to crawl"
    )

    # -r flag
    parser.add_argument(
        "-r",
        action="store_true",
        help="Recursively download images"
    )

    # -l depth
    parser.add_argument(
        "-l",
        type=int,
        default=5,
        help="Maximum recursion depth (default: 5)"
    )

    # -p path
    parser.add_argument(
        "-p",
        default="./data/",
        help="Download path (default: ./data/)"
    )

    args = parser.parse_args()

    # Validation logic
    if args.r and not args.url:
        parser.error("URL is required when using -r")

    if args.l != 5 and not args.r:
        parser.error("-l can only be used with -r")

    # Debug
    # print("URL:", args.url)
    # print("Recursive:", args.r)
    # print("Depth:", args.l)
    # print("Path:", args.p)
    spider(args.l, args.p, args.r, args.url)
if __name__ == "__main__":
    main()
