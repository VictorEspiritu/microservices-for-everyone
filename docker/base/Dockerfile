FROM php:7.1-cli
STOPSIGNAL SIGKILL
RUN docker-php-ext-install pdo_mysql
WORKDIR /opt

# Copied from https://hub.docker.com/r/ulff/php7-cassandra-dockerimage/~/dockerfile/
RUN apt-get update && \
    apt-get install -y git wget libssl-dev zlib1g-dev libicu-dev g++ make cmake libuv-dev libgmp-dev uuid-dev && \
    apt-get autoclean -y && \
    apt-get clean -y
RUN git clone https://github.com/datastax/php-driver.git /usr/src/datastax-php-driver && \
    cd /usr/src/datastax-php-driver && \
    git checkout v1.2.2 && \
    git submodule update --init && \
    cd ext && \
    ./install.sh && \
    echo extension=cassandra.so > /usr/local/etc/php/conf.d/cassandra.ini
