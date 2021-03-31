sudo docker build . -t lunaproject/leonam-php-mysql:1.0.0
sudo docker run -d -it -p 30001:80 --name "leonam-php-mysql" \
-v "$(pwd)"/src:/var/www/html \
lunaproject/leonam-php-mysql:1.0.0
