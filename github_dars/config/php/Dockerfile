FROM sparanoid/php-fpm:8-latest
RUN apt-get update && apt-get upgrade -y
RUN apt-get install git -y
RUN git config --global user.email \"huravltampl@gmail.com\"
RUN git config --global user.name \"Egor\"
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN composer config --global --auth github-oauth.github.com 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