import argparse

def spider(int depth, str path, bool recursive, str url):
    # Placeholder for the spider function implementation

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
    print("URL:", args.url)
    print("Recursive:", args.r)
    print("Depth:", args.l)
    print("Path:", args.p)

if __name__ == "__main__":
    main()
