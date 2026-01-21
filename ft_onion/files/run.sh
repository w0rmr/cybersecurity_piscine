#!/bin/bash

# Create Tor data directory with proper permissions
mkdir -p /var/lib/tor/myservice
chown -R debian-tor:debian-tor /var/lib/tor
chmod 700 /var/lib/tor
chmod 700 /var/lib/tor/myservice

# Start SSH service
echo "Starting SSH service on port 4242..."
service ssh start

# Start PHP-FPM
echo "Starting PHP-FPM..."
service php7.4-fpm start

# Start Nginx
echo "Starting Nginx..."
service nginx start

# Start Tor in the background
echo "Starting Tor..."
# Run Tor as the debian-tor user and redirect output
su -s /bin/bash -c "tor -f /etc/tor/torrc" debian-tor > /var/log/tor.log 2>&1 &

# Wait for the hostname file to be created
echo "Waiting for Tor hidden service to generate hostname..."
TIMEOUT=60
ELAPSED=0
while [ ! -f /var/lib/tor/myservice/hostname ] && [ $ELAPSED -lt $TIMEOUT ]; do
    sleep 1
    ELAPSED=$((ELAPSED + 1))
done

# Check if Tor started successfully
if [ ! -f /var/lib/tor/myservice/hostname ]; then
    echo "ERROR: Tor failed to start or create hidden service!"
    echo "Tor log output:"
    cat /var/log/tor.log
    exit 1
fi

# Display the hostname and access information
echo ""
echo "========================================"
echo "   🧅 HIDDEN SERVICE IS READY 🧅"
echo "========================================"
echo ""
echo -e "\033[32mYour .onion address:\033[0m"
echo -e "\033[1;32m$(cat /var/lib/tor/myservice/hostname)\033[0m"
echo ""
echo -e "\033[33mWeb Access:\033[0m"
echo "  http://$(cat /var/lib/tor/myservice/hostname)"
echo ""
echo -e "\033[33mSSH Access:\033[0m"
echo "  torify ssh root@$(cat /var/lib/tor/myservice/hostname) -p 4242"
echo "  Default password: toor"
echo ""
echo -e "\033[31m⚠️  SECURITY NOTE:\033[0m"
echo "  - SSH is running on port 4242"
echo "  - Change the default password immediately!"
echo "  - SSH is hardened with the following settings:"
echo "    • MaxAuthTries: 3"
echo "    • PermitEmptyPasswords: no"
echo "    • X11Forwarding: no"
echo "    • Protocol: 2"
echo ""
echo "========================================"
echo ""

# Keep the container running and show logs
tail -f /var/log/nginx/access.log