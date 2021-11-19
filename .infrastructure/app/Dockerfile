################################################################################
# base image
################################################################################

FROM debian:10-slim

################################################################################
# build args & environment vars
################################################################################

ARG DEBIAN_FRONTEND=noninteractive
ARG APP_ENV=production
ARG APP_PHP_VER=7.4

ENV PATH=/app:/app/vendor/bin:$PATH
ENV APP_ENV=${APP_ENV}
ENV APP_PHP_VER=${APP_PHP_VER}

################################################################################
# source code
################################################################################

COPY . /app
WORKDIR /app

################################################################################
# dependencies
################################################################################

RUN apt-get update && apt-get -y upgrade && apt-get install -y -qq --no-install-recommends \
    apt-transport-https \
    lsb-release \
    ca-certificates \
    procps \
    build-essential \
    make \
    cmake \
    libpng-dev \
    openssh-client \
    git \
    curl \
    wget \
    file \
    zip \
    unzip \
    p7zip-full \
    python-chardet \
    python-pip \
    python3-pip \
    python-setuptools \
    python3-setuptools \
    python-wheel \
    python3-wheel \
    dos2unix \
    mono-complete \
    xvfb \
    xauth \
    cron \
    nginx-light \
    supervisor; \
    \
################################################################################
# php & composer
################################################################################
    \
    curl https://packages.sury.org/php/apt.gpg --output /etc/apt/trusted.gpg.d/php.gpg; \
    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list; \
    apt-get update && apt-get install -y -qq --no-install-recommends \
    php${APP_PHP_VER}-fpm \
    php${APP_PHP_VER}-intl \
    php${APP_PHP_VER}-mbstring \
    php${APP_PHP_VER}-gmp \
    php${APP_PHP_VER}-bz2 \
    php${APP_PHP_VER}-pdo-mysql \
    php${APP_PHP_VER}-zip \
    php${APP_PHP_VER}-curl \
    php${APP_PHP_VER}-xml \
    php${APP_PHP_VER}-xmlrpc \
    php${APP_PHP_VER}-imagick \
    php${APP_PHP_VER}-redis \
    php${APP_PHP_VER}-cli; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --version=1.10.23 --filename=composer; \
    composer global require hirak/prestissimo; \
    \
################################################################################
# nodejs
################################################################################
    \
    curl -sL https://deb.nodesource.com/setup_10.x | bash - ; \
    apt-get install -y --no-install-recommends nodejs; \
    \
################################################################################
# cron
################################################################################
    \
    cp /app/.infrastructure/app/crontasks /etc/cron.d/crontasks; \
    chmod 0644 /etc/cron.d/crontasks; \
    \
################################################################################
# encoding detector: chared (http://corpus.tools/wiki/Chared)
################################################################################
    \
    export APP_CHARED_VER="1.2.2"; \
    mkdir -p /tmp/chared \
    && cd /tmp/chared \
    && curl -L http://corpus.tools/raw-attachment/wiki/Downloads/chared-${APP_CHARED_VER}.tar.gz --output chared-${APP_CHARED_VER}.tar.gz  \
    && tar xzvf chared-${APP_CHARED_VER}.tar.gz \
    && cd chared-${APP_CHARED_VER} \
    && python setup.py install; \
    \
################################################################################
# subtitle converter: subtitleedit (https://github.com/SubtitleEdit/subtitleedit)
################################################################################
    \
    curl -L https://github.com/SubtitleEdit/subtitleedit/releases/download/3.5.5/SE355.zip --output /tmp/subtitleedit.zip \
    && unzip /tmp/subtitleedit.zip -d /subtitleedit; \
    \
################################################################################
# subtitle converter: napi (https://gitlab.com/hesperos/napi.git)
################################################################################
    \
    mkdir /tmp/napi \
    && cd /tmp/napi \
    && git clone https://gitlab.com/hesperos/napi.git . \
    && git checkout v2.0.1 \
    && mkdir build \
    && cd build \
    && cmake .. \
    && make \
    && make install; \
    \
################################################################################
# subtitle searcher: subliminal (https://github.com/Diaoul/subliminal)
################################################################################
    \
    pip3 install subliminal==2.1.0; \
    \
################################################################################
# clear apt-cache & tmp
################################################################################
    \
    rm -rf /var/lib/apt/lists/*; \
    rm -rf /tmp/*; \
    \
################################################################################
# config files
################################################################################
    \
    cat /app/.infrastructure/app/.bashrc >> /root/.bashrc; \
    \
    printf '#!/bin/bash\nsupervisorctl $*\n' > /usr/bin/sv && chmod +x /usr/bin/sv; \
    printf '#!/bin/bash\nartisan $*\n' > /usr/bin/a && chmod +x /usr/bin/a; \
    \
    ln -sf /app/.infrastructure/app/${APP_ENV}/php.ini /etc/php/${APP_PHP_VER}/cli/conf.d/00-php.ini; \
    ln -sf /app/.infrastructure/app/${APP_ENV}/php-cli.ini /etc/php/${APP_PHP_VER}/cli/conf.d/01-php-cli.ini; \
    ln -sf /app/.infrastructure/app/${APP_ENV}/php.ini /etc/php/${APP_PHP_VER}/fpm/conf.d/00-php.ini; \
    \
    if [ -f /etc/php/${APP_PHP_VER}/fpm/php-fpm.conf ]; then mv /etc/php/${APP_PHP_VER}/fpm/php-fpm.conf /etc/php/${APP_PHP_VER}/fpm/php-fpm.conf.backup; fi; \
    ln -sf /app/.infrastructure/app/php-fpm.conf /etc/php/${APP_PHP_VER}/fpm/php-fpm.conf; \
    ln -sf /usr/sbin/php-fpm${APP_PHP_VER} /usr/local/bin/php-fpm; \
    \
    if [ -f /etc/nginx/nginx.conf ]; then mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup; fi; \
    ln -sf /app/.infrastructure/app/${APP_ENV}/nginx.conf /etc/nginx/nginx.conf; \
    \
    if [ -f /etc/supervisor/supervisord.conf ]; then mv /etc/supervisor/supervisord.conf /etc/supervisor/supervisord.conf.backup; fi; \
    ln -sf /app/.infrastructure/app/supervisord.conf /etc/supervisor/supervisord.conf; \
    \
    chmod +x /app/.infrastructure/app/run.sh \
    && ln -sf /app/.infrastructure/app/run.sh /usr/local/bin/run.sh;

################################################################################
# run
################################################################################

EXPOSE 80

CMD ["run.sh"]

