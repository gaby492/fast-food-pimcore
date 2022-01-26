# Pimcore 6 Local Installation (XAMPP)

## System requirements 
- XAMPP (PHP >= 8.0, MariaDB, Apache).
- Composer (Latest version)
- Git
- Go to C:\xampp\php\php.ini and edit php.ini (You can save the original one under php.ini.ORIGINAL)
	- memory_limit >= 512M
	- upload_max_filesize and post_max_size >= 100M (depending on your data)
	- uncomment ;extension=intl  (remove the semicolon)
	- Add zend_extension=C:\xampp\php\ext\php_opcache.dll at the end of file
  - Add extension=php_gd.dll

## Get project from repo
- Go to C:/xampp/htdocs and open the cmd 
- clone the repo
- cd repo_name  
- COMPOSER_MEMORY_LIMIT=-1 composer install

### Create the database
Go to phpmyadmin and create a pimcore user and the pimcore DB.

### Install pimcore
cd my-project/
COMPOSER_MEMORY_LIMIT=-1 ./vendor/bin/pimcore-install --ignore-existing-config

### Install assets
- php bin/console cache:clear
- php bin/console assets:install

## Classes rebuild command 
php bin/console pimcore:deployment:classes-rebuild -c -d -n

### Create Virtual Host
Open notepad as Administrator, go to C:\Windows\System32\drivers\etc\hosts and enter
127.0.0.1 pimcore.local.com


Go to C:\xampp\apache\conf\extra\httpd-vhosts.conf and enter the next information: 

```
NameVirtualHost *
<VirtualHost *>
  DocumentRoot "C:\xampp\htdocs"
  ServerName localhost
</VirtualHost>
<VirtualHost *>
  DocumentRoot "C:\xampp\htdocs\my-project\web"
  ServerName pimcore.local.com
  <Directory "C:\xampp\htdocs\my-project\web">
    Require all granted
  </Directory>
</VirtualHost>
```
Restart Apache
