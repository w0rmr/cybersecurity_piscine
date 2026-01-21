# FT_ONION - Tor Hidden Service with Web & SSH Access

A complete Tor hidden service implementation featuring:
- **Static Web Page**: Simple HTML page accessible via .onion address
- **Interactive Forum**: Anonymous file sharing and discussion board (Bonus)
- **SSH Access**: Secure shell on port 4242 with hardened configuration
- **Nginx Web Server**: Serving HTTP on port 80

## 📋 Mandatory Requirements

✅ **Nginx Web Server**: Configured to serve static content on port 80  
✅ **Static index.html**: Accessible through .onion address  
✅ **SSH on Port 4242**: OpenSSH server with hardened configuration  
✅ **No Firewall Rules**: Service configuration only, no iptables/firewall  
✅ **HTTP Access on Port 80**: Enabled through Tor hidden service  

## 🎁 Bonus Features

✅ **Interactive Forum Application**: Anonymous file upload and discussion  
✅ **File Sharing**: Upload files up to 10MB  
✅ **Link Sharing**: Share .onion or regular URLs  
✅ **Reply System**: Threaded discussions  
✅ **No JavaScript**: Pure PHP backend with HTML/CSS frontend

## Requirements

- Docker
- Tor Browser (to access the .onion address)

## 🚀 Quick Start

1. **Build and run the container:**
   ```bash
   chmod +x run.sh
   ./run.sh
   ```

2. **Get your .onion address:**
   The script will display your unique .onion address. Example:
   ```
   abcdefg1234567.onion
   ```

3. **Access the hidden service:**
   
   **Via Tor Browser (Web):**
   - Open Tor Browser
   - Navigate to `http://your-onion-address.onion`
   - View the static page or access the forum at `/forum.php`
   
   **Via SSH:**
   ```bash
   # Using torify
   torify ssh root@your-onion-address.onion -p 4242
   
   # Or with ProxyCommand
   ssh -o "ProxyCommand nc -X 5 -x 127.0.0.1:9050 %h %p" root@your-onion-address.onion -p 4242
   ```
   
   Default credentials: `root:toor` ⚠️ **Change immediately!**

## 🔧 Manual Commands

### Build the image:
```bash
docker build -t ft_onion_image .
```

### Run the container:
```bash
docker run -d -p 8080:80 -p 4242:4242 --name ft_onion_container ft_onion_image
```

### View logs and get .onion address:
```bash
docker logs ft_onion_container
```

### Access container shell:
```bash
docker exec -it ft_onion_container /bin/bash
```

### Stop the container:
```bash
docker stop ft_onion_container
```

### Remove the container:
```bash
docker rm ft_onion_container
```

## 🧪 Local Testing (Without Tor)

For testing purposes, you can access the services locally:

**Web Interface:**
```bash
# Open in browser
http://localhost:8080
```

**SSH:**
```bash
ssh root@localhost -p 4242
# Password: toor
```

## 🔐 SSH Hardening Features

The SSH configuration includes the following security measures:

- **Port**: Custom port 4242 (non-standard)
- **MaxAuthTries**: 3 attempts maximum
- **PermitEmptyPasswords**: Disabled
- **PubkeyAuthentication**: Enabled
- **PasswordAuthentication**: Enabled (for initial access)
- **X11Forwarding**: Disabled
- **Protocol**: 2 (SSH-2 only)
- **ClientAliveInterval**: 300 seconds
- **ClientAliveCountMax**: 2
- **AllowUsers**: root only

### Recommended SSH Security Improvements

After first login, you should:

1. **Change the root password:**
   ```bash
   passwd
   ```

2. **Add SSH key authentication:**
   ```bash
   # On your local machine
   ssh-keygen -t ed25519 -C "your_email@example.com"
   
   # Copy to hidden service (through Tor)
   cat ~/.ssh/id_ed25519.pub | torify ssh root@your-onion.onion -p 4242 'mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys'
   ```

3. **Disable password authentication** (edit `/etc/ssh/sshd_config`):
   ```bash
   PasswordAuthentication no
   ```

4. **Restart SSH:**
   ```bash
   service ssh restart
   ```

## 📊 Architecture

```
┌─────────────────────────────────────────────────────┐
│              Docker Container                        │
│                                                      │
│  ┌──────────┐    ┌──────────┐    ┌──────────┐     │
│  │   Tor    │◄──►│  Nginx   │    │   SSH    │     │
│  │  Daemon  │    │ +PHP-FPM │    │ (4242)   │     │
│  └──────────┘    └──────────┘    └──────────┘     │
│       │               │                │            │
│       │               │                │            │
│  .onion:80       .onion:4242      Local:4242       │
│       │               │                             │
│       └───────┬───────┘                             │
│               │                                     │
│          ┌────┴─────┐                              │
│          │          │                               │
│     index.html  forum.php                          │
│                      │                               │
│                 ┌────┴─────┐                       │
│                 │          │                        │
│              Uploads    Posts.json                  │
│         /var/www/html/uploads                       │
│         /var/www/data/posts.json                    │
└─────────────────────────────────────────────────────┘
```

## 🛡️ Security Notes

- All connections route through Tor for anonymity
- No JavaScript eliminates client-side tracking
- SSH is hardened with multiple security measures
- Nginx serves static content securely
- No user authentication required for forum (anonymous)
- Files stored locally (consider volumes for persistence)
- Default SSH credentials should be changed immediately

## ⚙️ Configuration Files

### Tor Configuration (`files/torrc`)
```ini
HiddenServiceDir /var/lib/tor/myservice/
HiddenServicePort 80 127.0.0.1:80
HiddenServicePort 4242 127.0.0.1:4242
```

### Nginx Configuration (`files/nginx.conf`)
- Serves static HTML and PHP files
- Configured for port 80
- Security headers enabled
- PHP-FPM integration

### SSH Configuration
- Port: 4242
- Hardened with best practices
- Root access enabled (change in production)

## 🎨 Customization

### Change upload size limit:
Edit `MAX_FILE_SIZE` in `files/index.php`

### Add file type restrictions:
Modify the file upload section in `files/index.php`

### Modify SSH settings:
Edit the Dockerfile SSH configuration section

### Persist data across container restarts:
Use Docker volumes:
```bash
docker run -d \
  -p 8080:80 -p 4242:4242 \
  -v $(pwd)/uploads:/var/www/html/uploads \
  -v $(pwd)/data:/var/www/data \
  -v $(pwd)/tor:/var/lib/tor \
  --name ft_onion_container \
  ft_onion_image
```

### Change SSH password:
Access the container and run:
```bash
docker exec -it ft_onion_container passwd
```

## 🐛 Troubleshooting

### Can't access the .onion address
- Ensure you're using Tor Browser
- Wait 2-3 minutes for hidden service propagation
- Check container logs: `docker logs ft_onion_container`
- Verify Tor is running: `docker exec ft_onion_container ps aux | grep tor`

### SSH connection refused
- Verify SSH is running: `docker exec ft_onion_container service ssh status`
- Check port mapping: `docker port ft_onion_container`
- For Tor access, ensure you're using `torify` or proxy settings
- Test locally first: `ssh root@localhost -p 4242`

### Files not uploading
- Check file size (max 10MB by default)
- Verify permissions: `docker exec ft_onion_container ls -la /var/www/html/uploads`
- Check PHP-FPM: `docker exec ft_onion_container service php7.4-fpm status`

### Nginx not serving pages
- Check Nginx status: `docker exec ft_onion_container service nginx status`
- View error logs: `docker exec ft_onion_container tail /var/log/nginx/error.log`
- Restart Nginx: `docker exec ft_onion_container service nginx restart`

### Container won't start
- Check if ports are in use: `netstat -tulpn | grep -E ':(80|4242)'`
- View detailed logs: `docker logs ft_onion_container`
- Rebuild image: `docker build --no-cache -t ft_onion_image .`

## 📝 Project Structure

```
ft_onion/
├── Dockerfile              # Container configuration
├── run.sh                  # Build and run script
├── clean.sh               # Cleanup script
├── README.md              # This file
└── files/
    ├── index.html         # Static homepage (mandatory)
    ├── index.php          # Forum application (bonus)
    ├── nginx.conf         # Nginx configuration
    ├── torrc              # Tor configuration
    └── run.sh             # Container startup script
```

## 🎯 Evaluation Checklist

### Mandatory Requirements
- ✅ Static `index.html` page exists and is accessible
- ✅ Nginx is the web server (not Apache or others)
- ✅ HTTP access on port 80 through .onion address
- ✅ SSH access on port 4242
- ✅ SSH is properly hardened
- ✅ No firewall rules configured (service config only)
- ✅ .onion address is generated and displayed

### Bonus (if mandatory is perfect)
- ✅ Interactive forum application
- ✅ File upload functionality
- ✅ Anonymous posting and replies
- ✅ No JavaScript (pure backend)

## 📚 Technologies Used

- **OS**: Debian Bullseye
- **Web Server**: Nginx
- **PHP**: PHP 7.4 with FPM
- **SSH**: OpenSSH Server
- **Anonymity**: Tor Network
- **Containerization**: Docker

## ⚠️ Security Warnings

1. **Default Credentials**: The default SSH password is `toor`. Change it immediately!
2. **Production Use**: This configuration is for educational purposes.
3. **File Uploads**: Be cautious with user-uploaded content.
4. **Log Files**: Consider disabling logs for true anonymity.
5. **Regular Updates**: Keep all packages updated for security patches.

## 📖 Additional Resources

- [Tor Project Documentation](https://www.torproject.org/docs/)
- [Nginx Security Best Practices](https://nginx.org/en/docs/)
- [SSH Hardening Guide](https://www.ssh.com/academy/ssh/security-hardening)
- [Docker Security Best Practices](https://docs.docker.com/engine/security/)

## 📄 License

Educational purposes only. Use responsibly and legally.

---

**Built for the 42 Cybersecurity Piscine - ft_onion project**
