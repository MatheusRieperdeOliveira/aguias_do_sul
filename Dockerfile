FROM php:8.3-fpm

ARG user=aguias
ARG uid=1000

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    librabbitmq-dev \
    gnupg \
    libicu-dev \
    sudo \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm

COPY package*.json ./
RUN npm install

RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd intl zip sockets

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN useradd -G www-data,root -u $uid -d /home/$user $user && \
    echo "$user:$user" | chpasswd && \
    adduser $user sudo

RUN echo "root:root" | chpasswd

RUN echo "$user ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/$user && \
    chmod 0440 /etc/sudoers.d/$user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Supervisor configuration
RUN mkdir -p /var/log/supervisor /var/run && \
    chown -R $user:$user /var/log/supervisor /var/run

COPY docker/php/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

COPY docker/supervisor/supervisor.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/app_aguiasdosul

USER root

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
