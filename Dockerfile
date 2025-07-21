FROM sail-8.2/app

# Garantir que pdo_mysql está instalado
RUN apt-get update && apt-get install -y default-mysql-client libmysqlclient-dev && \
    pecl install pdo_mysql && \
    docker-php-ext-enable pdo_mysql

# Desabilitar extensões problemáticas
RUN phpdismod -s cli xdebug swoole mongodb

# Configurar PHP para ser mais estável
RUN echo "memory_limit = 1G" >> /etc/php/8.2/cli/conf.d/99-sail.ini
RUN echo "max_execution_time = 300" >> /etc/php/8.2/cli/conf.d/99-sail.ini
RUN echo "opcache.enable = 0" >> /etc/php/8.2/cli/conf.d/99-sail.ini 