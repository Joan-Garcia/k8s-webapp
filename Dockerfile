FROM mysql:5.7

ENV MYSQL_ROOT_PASSWORD=root
ENV MYSQL_PASSWORD=root
ENV MYSQL_DB=bienes_raices

COPY ./db/bienes_raices.sql /docker-entrypoint-initdb.d/bienes_raices.sql

EXPOSE 3306

CMD ["mysqld"]