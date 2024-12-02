FROM php:7.4.9-fpm

COPY php.ini /usr/local/etc/php/

# 必要な拡張をインストール
RUN apt update \
  && apt install -y default-mysql-client zlib1g-dev libzip-dev unzip libpng-dev tzdata cron supervisor \
  && docker-php-ext-install pdo_mysql zip gd

# Composerをインストール
RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

# タイムゾーン設定
ENV TZ=Asia/Tokyo

# 作業ディレクトリ
WORKDIR /var/www

# SchedulerスクリプトとSupervisor設定を追加
COPY scheduler.sh /usr/local/bin/scheduler.sh
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod +x /usr/local/bin/scheduler.sh

# Supervisorでプロセスを管理
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
