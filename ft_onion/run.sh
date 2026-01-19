#! /bin/bash

docker build -t ft_onion_image .
docker run -d -p 8080:80 --name ft_onion_container ft_onion_image
docker exec -it ft_onion_container /bin/bash