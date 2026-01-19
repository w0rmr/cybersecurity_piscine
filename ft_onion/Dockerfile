FROM debian:bullseye


RUN apt-get update \
    && apt-get install -y \
        ca-certificates \
        procps \
        tor \
        mariadb-server \
        php \
        php-mysql \
        libapache2-mod-php \
        curl \
        wget \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*



COPY files/torrc /etc/tor/torrc

COPY files/start-tor.sh /usr/local/bin/start-tor.sh

COPY files/run.sh /usr/local/bin/run.sh

RUN chmod +x /usr/local/bin/start-tor.sh

RUN chmod +x /usr/local/bin/run.sh

RUN cat /var/lib/tor/myservice/hostname | awk '{print " \033[31mYour hostname is \033[32m" $0 "\033[0m"}'

CMD ["tor"]