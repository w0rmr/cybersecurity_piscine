#! /bin/bash

docker build -t lab_image .
docker run -d -p 8080:80 --name lab_container lab_image
docker exec -it lab_container /bin/bash