#!/bin/bash

# Stop and remove existing container if it exists
docker stop ft_onion_container 2>/dev/null
docker rm ft_onion_container 2>/dev/null

# Build the image
echo "Building Docker image..."
docker build -t ft_onion_image .

if [ $? -ne 0 ]; then
    echo "Build failed!"
    exit 1
fi

# Run the container
echo ""
echo "Starting container..."
docker run -d \
    -p 8080:80 \
    -p 4242:4242 \
    --name ft_onion_container \
    ft_onion_image

# Wait for services to start
echo "Waiting for services to initialize..."
sleep 5

# Show the logs to display the .onion address
echo ""
echo "========================================"
docker logs ft_onion_container
echo "========================================"

echo ""
echo "📋 Quick Commands:"
echo "  • View logs:        docker logs -f ft_onion_container"
echo "  • Access shell:     docker exec -it ft_onion_container /bin/bash"
echo "  • Stop container:   docker stop ft_onion_container"
echo "  • Remove container: docker rm ft_onion_container"
echo ""
echo "🌐 Local Testing (not through Tor):"
echo "  • Web: http://localhost:8080"
echo "  • SSH: ssh root@localhost -p 4242 (password: toor)"
echo ""