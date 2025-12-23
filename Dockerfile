FROM php:7.4-apache

# Configuración de Apache y extensiones de PHP
RUN a2enmod rewrite && docker-php-ext-install opcache gettext mysqli
EXPOSE 80

# Instalación de dos2unix para corregir finales de línea
RUN apt-get update && apt-get install -y dos2unix

# Creación del script cron
RUN cat > /var/cron.sh <<EOF
#!/bin/bash
while true
do
  sleep 3600  # 3600 = 1h
  /usr/bin/curl --silent http://localhost/cron
done
EOF

# Corrección de los finales de línea del script
RUN dos2unix /var/cron.sh

# Hacer el script ejecutable
RUN chmod +x /var/cron.sh

# Comando para ejecutar el script y Apache en paralelo
CMD ["bash", "-c", "/var/cron.sh & apache2-foreground"]