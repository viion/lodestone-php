#!/usr/bin/env bash
echo "Creating Lodestone Development Environment"

echo "Starting"
cd /vagrant &> /dev/null
USER=vagrant &> /dev/null
sudo locale-gen en_GB.UTF-8 &> /dev/null

# update/upgrade
echo "Updating Ubuntu packages and running upgrade ..."
sudo apt-get update -y -qq &> /dev/null
sudo apt-get upgrade -y -qq &> /dev/null

# Stuff
echo "Installing python software properties and common packages ..."
sudo apt-get install python-software-properties &> /dev/null
sudo apt-get install software-properties-common &> /dev/null

# htop, unzip, curl and git
echo "Installing: Htop, Unzip, Curl and Git"
sudo apt-get install -y -qq acl htop unzip curl git &> /dev/null

# php 7
echo "Installing: PHP 7 + Modules ..."
sudo add-apt-repository ppa:ondrej/php -y &> /dev/null
sudo apt-get update -y &> /dev/null
sudo apt-get install -y -qq php7.0-fpm &> /dev/null
sudo apt-get install -y -qq php-apcu php7.0-dev php7.0-cli php7.0-json php7.0-fpm php7.0-intl php7.0-mysql &> /dev/null
sudo apt-get install -y -qq php7.0-sqlite php7.0-curl php7.0-mcrypt php7.0-gd php7.0-mbstring php7.0-dom &> /dev/null
sudo apt-get install -y -qq php7.0-xml php7.0-zip php7.0-bcmath &> /dev/null

# change some settings
echo "Adjusting PHP settings to: enable errors, increase upload size,"
echo "increase execution time, increase memory limit and set active user"
sed -i 's|display_errors = Off|display_errors = On|' /etc/php/7.0/fpm/php.ini
sed -i 's|upload_max_filesize = 2M|upload_max_filesize = 64M|' /etc/php/7.0/fpm/php.ini
sed -i 's|post_max_size = 8M|post_max_size = 64M|' /etc/php/7.0/fpm/php.ini
sed -i 's|max_execution_time = 30|max_execution_time = 300|' /etc/php/7.0/fpm/php.ini
sed -i 's|memory_limit = 128M|memory_limit = 2G|' /etc/php/7.0/fpm/php.ini
sed -i 's|;request_terminate_timeout = 0|request_terminate_timeout = 300|' /etc/php/7.0/fpm/pool.d/www.conf
sed -i "s|www-data|$USER|" /etc/php/7.0/fpm/pool.d/www.conf

# install composer
echo "Install: Composer ..."
curl -sS https://getcomposer.org/installer | php &> /dev/null
mv composer.phar /usr/local/bin/composer &> /dev/null
chmod +x /usr/local/bin/composer &> /dev/null

# reload
echo "Restart services ..."
sudo service php7.0-fpm restart &> /dev/null

# make sure all up to date
echo "Final update ..."
sudo apt-get update -y -qq &> /dev/null
sudo apt-get upgrade -y -qq &> /dev/null