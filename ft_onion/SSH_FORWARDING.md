# SSH Port Forwarding Guide

This hidden service supports SSH port forwarding for secure tunneling through Tor.

## SSH Configuration

### Hardening Features Enabled:
- ✅ Port: 4242 (non-standard)
- ✅ MaxAuthTries: 3
- ✅ PermitEmptyPasswords: no
- ✅ X11Forwarding: no
- ✅ Protocol: 2 only
- ✅ ClientAliveInterval: 300s
- ✅ ClientAliveCountMax: 2
- ✅ AllowTcpForwarding: yes (for port forwarding)
- ✅ GatewayPorts: no (security)
- ✅ PermitTunnel: yes (for VPN-like tunneling)
- ✅ AllowStreamLocalForwarding: yes

## Port Forwarding Types

### 1. Local Port Forwarding (Most Common)
Forward a local port to a remote service through the hidden service.

```bash
# Example: Forward local port 8080 to Google through Tor
torify ssh -L 8080:www.google.com:80 root@<your-onion-address>.onion -p 4242

# Then access in your browser:
http://localhost:8080
```

```bash
# Example: Access another .onion service
torify ssh -L 9090:another-onion-address.onion:80 root@<your-onion-address>.onion -p 4242

# Access at:
http://localhost:9090
```

### 2. Dynamic Port Forwarding (SOCKS Proxy)
Create a SOCKS proxy for all your traffic through Tor.

```bash
# Create SOCKS5 proxy on port 1080
torify ssh -D 1080 root@<your-onion-address>.onion -p 4242

# Configure your browser to use SOCKS5 proxy:
# Host: localhost
# Port: 1080
```

### 3. Remote Port Forwarding
Make a service on your local machine accessible through the hidden service.

```bash
# Example: Expose local web server (port 8000) through hidden service
torify ssh -R 9000:localhost:8000 root@<your-onion-address>.onion -p 4242

# Now port 9000 on the hidden service forwards to your local port 8000
```

## SSH Connection Examples

### Basic Connection
```bash
torify ssh root@<your-onion-address>.onion -p 4242
```

### With ProxyCommand (alternative to torify)
```bash
ssh -o "ProxyCommand nc -X 5 -x 127.0.0.1:9050 %h %p" root@<your-onion-address>.onion -p 4242
```

### SSH Config File Setup
Add to `~/.ssh/config`:

```
Host hidden-service
    HostName <your-onion-address>.onion
    Port 4242
    User root
    ProxyCommand nc -X 5 -x 127.0.0.1:9050 %h %p
    ServerAliveInterval 60
    ServerAliveCountMax 3
```

Then connect with:
```bash
ssh hidden-service
```

## Port Forwarding Use Cases

### 1. Secure Web Browsing
```bash
# Forward web traffic through hidden service
torify ssh -D 1080 root@<your-onion-address>.onion -p 4242 -N
```

### 2. Database Access
```bash
# Access a database through the hidden service
torify ssh -L 3306:db-server:3306 root@<your-onion-address>.onion -p 4242 -N
```

### 3. Multiple Port Forwards
```bash
# Forward multiple ports simultaneously
torify ssh \
  -L 8080:service1:80 \
  -L 3306:service2:3306 \
  -L 6379:service3:6379 \
  root@<your-onion-address>.onion -p 4242 -N
```

### 4. Background Tunnel
```bash
# Run SSH tunnel in background
torify ssh -f -N -D 1080 root@<your-onion-address>.onion -p 4242
```

## Security Notes

⚠️ **Important Security Considerations:**

1. **Change Default Password**: The default password is `toor` - change it immediately!
   ```bash
   # After connecting
   passwd
   ```

2. **Use SSH Keys**: Password authentication is less secure than key-based authentication
   ```bash
   # Generate SSH key
   ssh-keygen -t ed25519 -C "hidden-service"
   
   # Copy to hidden service
   torify ssh-copy-id -p 4242 root@<your-onion-address>.onion
   ```

3. **Disable Password Authentication**: After setting up keys
   ```bash
   # On the hidden service
   sed -i 's/PasswordAuthentication yes/PasswordAuthentication no/' /etc/ssh/sshd_config
   systemctl restart ssh
   ```

4. **Monitor Connections**: Regularly check active SSH sessions
   ```bash
   who
   w
   last
   ```

5. **Firewall Rules**: The hidden service has minimal attack surface
   - Only ports 80 (HTTP) and 4242 (SSH) are exposed through Tor
   - All ports are local-only by default

## Troubleshooting

### Connection Refused
```bash
# Check if Tor is running on your system
systemctl status tor

# Check if SSH service is running on hidden service
docker exec ft_onion_container systemctl status ssh
```

### Port Forwarding Not Working
```bash
# Verify AllowTcpForwarding is enabled
docker exec ft_onion_container grep "AllowTcpForwarding" /etc/ssh/sshd_config

# Check SSH logs
docker exec ft_onion_container tail -f /var/log/auth.log
```

### Slow Connection
- Tor routing adds latency (normal behavior)
- Try different Tor circuits: `killall -HUP tor` on your system
- Use `-C` flag for compression: `torify ssh -C -D 1080 ...`

## Advanced: Tunneling Through Multiple Hops

```bash
# Connect through first hidden service, then to another
torify ssh -J root@first.onion:4242 root@second.onion:4242
```

## Monitoring Port Forwards

```bash
# On the hidden service, see active port forwards
docker exec ft_onion_container netstat -tulpn | grep ssh

# On your local machine
netstat -an | grep LISTEN
```

---

**Default Credentials** (CHANGE THESE!):
- Username: `root`
- Password: `toor`
- SSH Port: `4242`
