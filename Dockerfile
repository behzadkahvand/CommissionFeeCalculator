FROM m.docker-registry.ir/php:8.1-fpm

MAINTAINER Behzad Kahvand <behzad.kahvand@gmail.com>

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install dependencies
RUN apt-get --allow-releaseinfo-change update  && apt-get install -yq --fix-missing \
    build-essential \
    libpng-dev \
    libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    libcurl4 \
    libcurl4-openssl-dev \
    locales \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    vim \
    git \
    curl \
    gnupg2 \
    libssh-dev

# Install extensions
RUN docker-php-ext-install zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install curl

# Install composer
RUN curl -s http://getcomposer.org/installer | php && \
    echo "export PATH=${PATH}:/var/www/vendor/bin" >> ~/.bashrc && \
    mv composer.phar /usr/local/bin/composer

COPY . /var/www

# Set working directory
WORKDIR /var/www

CMD ["php-fpm"]
