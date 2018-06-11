FROM mysql:5.7

COPY ./server/database/script.sql /docker-entrypoint-initdb.d/script.sql