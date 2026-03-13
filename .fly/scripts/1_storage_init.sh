#!/usr/bin/env sh

# Restaure le contenu du storage si le volume est vide
FOLDER=/var/www/html/storage/app
if [ ! -d "$FOLDER" ]; then
  echo "Initialisation du storage..."
  cp -r /var/www/html/storage_/. /var/www/html/storage
  rm -rf /var/www/html/storage_
fi

# Crée le dossier database et le fichier SQLite s'ils n'existent pas
DBFOLDER=/var/www/html/storage/database
if [ ! -d "$DBFOLDER" ]; then
  echo "Création de la base SQLite..."
  mkdir -p /var/www/html/storage/database
  touch /var/www/html/storage/database/database.sqlite
fi
