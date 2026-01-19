from PIL import Image, ExifTags
import sys
import datetime
import os

def exract_metadata(image_path):
    img = Image.open(image_path)
    metadata = {}
    img_exif = img.getexif()
    if img_exif and len(img_exif) > 0:
        for key, val in img_exif.items():
            tag = ExifTags.TAGS.get(key, key)
            metadata[tag] = val
    if img.info:
        for k, v in img.info.items():
            metadata[k] = v
    if metadata:
        print("All metadata:")
        for k, v in metadata.items():
            print(f"{k}: {v}")
        else:
            print("No metadata found.")
    os.path.getctime(image_path)
    creation_time = datetime.datetime.fromtimestamp(os.path.getctime(image_path))
    print(f"File creation time: {creation_time}")

def main():
    if len(sys.argv) < 2:
        print("Usage: python scorpion.py <image_path>")
        return
    for arg in sys.argv[1:]:
        print(f"Extracting metadata from {arg}")
        image_metadata = exract_metadata(arg)   

if __name__ == "__main__":
    main()