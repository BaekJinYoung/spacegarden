#!/bin/bash

set -e

sudo apt-get clean --assume-yes
sudo apt-get update --assume-yes
sudo apt-get upgrade --assume-yes

MYSQL_ROOT_PASSWORD=$1
LARAVEL_PROJECT_PATH=$2

# Composer 설치 함수
install_composer() {
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
}

# Laravel 설치 함수
install_laravel() {
    sudo composer global require laravel/installer
    sudo ln -s ~/.composer/vendor/bin/laravel /usr/local/bin/laravel
}

# 필수 패키지 설치 함수
install_packages() {
    sudo apt install software-properties-common
    sudo add-apt-repository ppa:ondrej/php
    sudo apt update

    sudo apt-get install -y \
        curl \
        git \
        unzip \
        zip \
        nginx \
        mysql-server \
        mysql-client \
        php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath php8.2-sqlite3 \
        vsftpd \
        certbot \
        python3-certbot-nginx
}

# Secure MySQL installation
secure_mysql_installation() {
    sudo chown -R mysql:mysql /var/lib/mysql
    sudo chmod -R 755 /var/lib/mysql
    sudo chmod -R 644 /var/lib/mysql/*

    if [ ! -f /root/.mysql_secure_installed ]; then
        mysql_secure_installation <<EOF

$MYSQL_ROOT_PASSWORD
n
y
y
y
y
EOF
        touch /root/.mysql_secure_installed


FLUSH PRIVILEGES;
    # MySQL에 root로 접속하여 사용자 관리 스크립트 실행
    sudo mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<MYSQL_SCRIPT
DROP USER IF EXISTS root@localhost;
CREATE USER root@localhost IDENTIFIED BY '$MYSQL_ROOT_PASSWORD';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;
MYSQL_SCRIPT
    fi

    # MySQL 서비스 재시작
    sudo systemctl restart mysql
}

# Configure VSFTPD
configure_vsftpd() {
    if [ ! -f /etc/vsftpd.conf ] || ! grep -q "listen_port=2121" /etc/vsftpd.conf; then
    sudo tee /etc/vsftpd.conf > /dev/null <<EOL
listen=YES
listen_ipv6=NO
anonymous_enable=NO
local_enable=YES
write_enable=YES
local_umask=022
dirmessage_enable=YES
use_localtime=YES
chroot_local_user=YES
pasv_enable=YES
pasv_min_port=10090
pasv_max_port=10100
allow_writeable_chroot=YES
listen_port=2121
port_enable=YES
connect_from_port_20=NO
local_max_rate=0
secure_chroot_dir=/var/run/vsftpd/empty
pam_service_name=vsftpd
rsa_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem
rsa_private_key_file=/etc/ssl/private/ssl-cert-snakeoil.key
ssl_enable=NO
EOL
    sudo systemctl restart vsftpd
    fi
}

# Configure firewall
configure_firewall() {
    sudo ufw allow OpenSSH
    sudo ufw allow mysql
    sudo ufw allow 80/tcp
    sudo ufw allow 443/tcp
    sudo ufw allow 2121/tcp
    sudo ufw allow 20:21/tcp
    sudo ufw allow 30000:31000/tcp
    sudo ufw --force enable
}

# Install SSL certificate with Certbot
install_ssl_cert() {
    if ! sudo certbot certificates | grep -q "baekjinyoung.co.kr"; then
        sudo certbot --nginx -d baekjinyoung.co.kr -n --agree-tos --email time121412@gmail.com || true
        sudo systemctl enable certbot.timer
        sudo systemctl start certbot.timer
    fi
}

## Configure PHP and Nginx
#configure_php_nginx() {
#    # Find PHP version (assuming PHP-FPM is installed)
#    PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
#
#    # Configure PHP-FPM
#    sudo sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/${PHP_VERSION}/fpm/php.ini
#    sudo systemctl restart php${PHP_VERSION}-fpm
#
#    # Configure Nginx
#    NGINX_CONF="/etc/nginx/sites-available/default"
#    if [ ! -f "$NGINX_CONF" ]; then
#        sudo tee $NGINX_CONF > /dev/null <<EOL
#server {
#    listen 80 default_server;
#    listen [::]:80 default_server;
#    root /home/ubuntu/laravel/public;
#    index index.php index.html index.htm index.nginx-debian.html;
#    server_name baekjinyoung.co.kr;
#
#    location / {
#        try_files \$uri \$uri/ /index.php?$query_string;
#    }
#
#    location ~ \.php$ {
#        include snippets/fastcgi-php.conf;
#        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;
#    }
#
#    location ~ /\.ht {
#        deny all;
#    }
#}
#EOL
#    fi
#
#    # symbolic link
#    if [ ! -e "/etc/nginx/sites-enabled/default" ]; then
#        sudo ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/
#    fi
#
#    sudo systemctl restart nginx
#}

# Laravel 프로젝트 설정
setup_laravel_project() {
    cd "$LARAVEL_PROJECT_PATH"

    # Composer 종속성 설치
    composer install --no-plugins --no-scripts --ignore-platform-reqs

    # .env 파일 설정 확인 및 생성
if [ ! -f .env ]; then
    cp .env.example .env
    chmod 644 .env
    php artisan key:generate
    fi

    sudo chmod -R 777 /home/ubuntu

    # Laravel 캐시 및 설정 초기화
    php artisan config:clear
    php artisan cache:clear
    php artisan config:cache

    # 데이터베이스 마이그레이션
    php artisan migrate --force
}

# Main function to execute all configuration steps
main() {
    install_packages
    install_composer
    install_laravel
    secure_mysql_installation
    configure_vsftpd
    configure_firewall
    install_ssl_cert
    configure_php_nginx
    setup_laravel_project
}

# Execute main function
main "$MYSQL_ROOT_PASSWORD"



