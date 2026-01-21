FROM debian:bullseye

# Install required packages
RUN apt-get update \
    && apt-get install -y \
        ca-certificates \
        procps \
        tor \
        nginx \
        openssh-server \
        php-fpm \
        php-mysql \
        curl \
        wget \
        vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure SSH
RUN mkdir /var/run/sshd \
    && sed -i 's/#Port 22/Port 4242/' /etc/ssh/sshd_config \
    && sed -i 's/#PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config \
    && sed -i 's/#PasswordAuthentication yes/PasswordAuthentication yes/' /etc/ssh/sshd_config \
    && echo 'root:toor' | chpasswd

# SSH Hardening and Port Forwarding Configuration
RUN sed -i 's/#PermitEmptyPasswords no/PermitEmptyPasswords no/' /etc/ssh/sshd_config \
    && sed -i 's/#MaxAuthTries 6/MaxAuthTries 3/' /etc/ssh/sshd_config \
    && sed -i 's/#PubkeyAuthentication yes/PubkeyAuthentication yes/' /etc/ssh/sshd_config \
    && sed -i 's/X11Forwarding yes/X11Forwarding no/' /etc/ssh/sshd_config \
    && sed -i 's/#AllowTcpForwarding yes/AllowTcpForwarding yes/' /etc/ssh/sshd_config \
    && sed -i 's/#GatewayPorts no/GatewayPorts no/' /etc/ssh/sshd_config \
    && sed -i 's/#PermitTunnel no/PermitTunnel yes/' /etc/ssh/sshd_config \
    && echo "AllowUsers root" >> /etc/ssh/sshd_config \
    && echo "Protocol 2" >> /etc/ssh/sshd_config \
    && echo "ClientAliveInterval 300" >> /etc/ssh/sshd_config \
    && echo "ClientAliveCountMax 2" >> /etc/ssh/sshd_config \
    && echo "PermitOpen any" >> /etc/ssh/sshd_config \
    && echo "AllowStreamLocalForwarding yes" >> /etc/ssh/sshd_config

# Create directories for the forum
RUN mkdir -p /var/www/html/uploads /var/www/data \
    && chown -R www-data:www-data /var/www/html /var/www/data \
    && chmod 755 /var/www/html/uploads /var/www/data

# Copy Tor configuration
COPY files/torrc /etc/tor/torrc

# Copy web files
COPY files/index.html /var/www/html/index.html
COPY files/index.php /var/www/html/forum.php
COPY files/api.php /var/www/html/api.php

# Copy nginx configuration
COPY files/nginx.conf /etc/nginx/sites-available/default

RUN chown -R www-data:www-data /var/www/html

# Copy startup scripts
COPY files/run.sh /usr/local/bin/run.sh
RUN chmod +x /usr/local/bin/run.sh

# Expose ports (for documentation only, not opening ports)
EXPOSE 80 4242

CMD ["/usr/local/bin/run.sh"]