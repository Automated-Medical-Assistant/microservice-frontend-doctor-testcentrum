# Labor - Frontend

### init

###### Technical Requirements

* Install PHP 7.4 or higher and these PHP extensions (which are installed and enabled by default in most PHP 7 installations): Ctype, iconv, JSON, PCRE, Session, SimpleXML, and Tokenizer;
* Install Composer, which is used to install PHP packages;


##### RabbitMQ

Login: admin  
Pass: admin

###### Setup
```
docker-compose pull
docker-compose up -d
composer install
```


##### PHP-Setup
```
sudo apt-get -y install gcc make autoconf libc-dev pkg-config
sudo apt-get -y install libssl-dev
sudo apt-get -y install librabbitmq-dev
sudo apt-get -y install php-pear php-dev
sudo apt-get -y install php-redis php-igbinary

sudo pecl install amqp [autodetect]

sudo touch /etc/php/7.4/cli/conf.d/20-amqp.ini
sudo echo "extension=amqp.so" >> /etc/php/7.4/cli/conf.d/20-amqp.ini
```
