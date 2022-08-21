FROM mysql:8

ENV MYSQL_ALLOW_EMPTY_PASSWORD=true
ENV MYSQL_DATABASE klimo

COPY mysql/klimo.sql /docker-entrypoint-initdb.d/

EXPOSE 3306