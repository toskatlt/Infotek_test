FROM mysql:8.0.30

RUN usermod -u 1000 mysql
RUN groupmod -g 1000 mysql

RUN set -x \
    && apt update \
    && apt install -y nano procps
