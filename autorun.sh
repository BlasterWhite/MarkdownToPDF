#!/bin/bash

# Programme qui prend en entrée le dossier où sont envoyés les fichiés MD, le dossier tmp pour php
# Appelle le code PHP lorsqu'un fichier est trouvé, puis copie ce fichier dans un dossier temporaire
# Programme qui tourne en permanence

mkdir /work/tmp

stop=0

cd /source

echo "Début du traitement des données"

while [ $stop -eq 0 ]
do
    nbligne=$(ls -l | wc -l) 
    if [ "$nbligne" != "1" ]
    then
        for element in *
        do
            cp $element /work/tmp/
            rm $element
            php /work/mdToHtml.php $element
            php /work/facturation.php 
            element=$(echo $element | cut -d'.' -f 1)
            echo Traitement du fichier : $element.html
            weasyprint /work/tmp/$element.html /destination/$element.pdf
            echo Enregistrement dans le dossier : /destination/$element.pdf
            chmod +rx /destination/$element.pdf
            rm /work/tmp/$element.html
        done
    fi
    sleep 5
done