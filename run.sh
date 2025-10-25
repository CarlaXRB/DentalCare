#!/bin/bash
# run.sh

# Reemplaza el puerto 8080 en Apache con el puerto que Cloud Run proporciona ($PORT)
sed -i "s/8080/${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/8080/${PORT:-8080}/g" /etc/apache2/sites-available/000-default.conf

# Ejecuta Apache en primer plano
apache2-foreground