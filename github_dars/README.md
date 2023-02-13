# README #
Для докера используется образ sparanoid/php-fpm:8-latest

либо обновленный с композером и гитом образ - config/php/Dockerfile

нужно этот образ поднять

docker -t php:fantasy -f ~/dars/config/php/Dockerfile

php:fantasy - под этим именем образ в docker-compose.yml

nginx config  для yii2 - config/nginx/conf.d/default.conf - на него будет смотреть nginx

стоит контроллер по умолчанию book/entry
entry - это точка входа для админа или клиента

пользователь Клиент фиксированный:
email -
jacke@gmail.com
password -
userpassword

пользователь Админ фиксированный:
email -
queen@gmail.com
password -
adminpassword

Админ может смотреть все страницы. Клиент только свои.
В базе пароль хешированный.

могут быть проблемы с правами при запуске, тогда нужно разрешить:
chmod -R 777 ~/dars/data/nginx/web
