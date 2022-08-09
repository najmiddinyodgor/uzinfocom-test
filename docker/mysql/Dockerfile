FROM mysql:5.7

RUN apt update && apt install -y locales \
    && rm -rf /var/lib/apt/lists/* \
    && localedef -i ru_RU -c -f UTF-8 -A /usr/share/locale/locale.alias ru_RU.UTF-8

ENV LANG ru_RU.utf8
ENV LC_ALL ru_RU.utf8

ADD conf.d /etc/mysql/conf.d

EXPOSE 3306
