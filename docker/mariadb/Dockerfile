FROM mariadb

# Upload custom config
ADD ["./gatewaymy.cnf", "/etc/mysql/conf.d/conf_mysql.cnf"]

# Create Database
ADD sql/createDatabase.sql /docker-entrypoint-initdb.d

# Set variables
ENV MYSQL_ROOT_PASSWORD test123
ENV MYSQL_DATABASE ips2019
ENV MYSQL_USER ips2019
ENV MYSQL_PASSWORD ips2019

# Upload dummy data
#ADD ["./sql/smallSet.sql", "/usr/sql/smallSet.sql"]

# PORT
EXPOSE 3306

# Alternatively install nano
#RUN apt-get update && apt-get -y install nano

CMD ["mysqld"]
