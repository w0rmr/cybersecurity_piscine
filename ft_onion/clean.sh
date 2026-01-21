#!/bin/bash

echo "🧹 Cleaning up ft_onion project..."

# Stop the container
echo "Stopping ft_onion container..."
docker stop ft_onion_container 2>/dev/null

# Remove the container
echo "Removing ft_onion container..."
docker rm ft_onion_container 2>/dev/null

# Remove the image
echo "Removing ft_onion image..."
docker rmi ft_onion_image 2>/dev/null

# Optional: Remove all stopped containers and dangling images
read -p "Remove all stopped containers and dangling images? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker container prune -f
    docker image prune -f
fi

echo "✅ Cleanup complete!"