#!/bin/sh -x

TSOHA_BASE=/home/bgran/tsoha/Tsoha-Bootstrap
TSOHA_DEPLOY=/home/bgran/tsoha/deploy

err () {
        what=$1
        err=$2

        if [ $err -eq 0 ]; then
                echo "NORMAL: $what"
        else
                echo "FATAL: $what"
                exit $err
        fi
}


#echo $TSOHA_BASE/config/environment.sh
#source $TSOHA_BASE/config/environment.sh || err "source failded for environment.sh" 1

echo "Luodaan projektikansio..."

if [ ! -e $TSOHA_DEPLOY ]; then
	mkdir $TSOHA_DEPLOY || err "coulnd't mkdir $TSOHA_DEPLOY" 4
fi
cd $TSOHA_DEPLOY || err "Couldn't chdir to $TSOHA_DEPLOY" 2

touch favicon.ico
touch .htaccess
echo 'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]' > .htaccess

echo "Valmis!"

echo "Siirretään tiedosto$TSOHA_DEPLOY -hakemistoon"

(cd $TSOHA_BASE && tar -cpf - app config lib vendor sql assets index.php composer.json) | tar -xpf -
# Siirretään tiedostot palvelimelle
#scp -r app config lib vendor sql assets index.php composer.json $USERNAME@users.cs.helsinki.fi:htdocs/$PROJECT_FOLDER

echo "Valmis!"

echo "Asetetaan käyttöoiudet ja Composer"

chmod -R a+rx $TSOHA_DEPLOY

# Asetetaan oikeudet ja asennetaan Composer
#ssh $USERNAME@users.cs.helsinki.fi "
#chmod -R a+rX htdocs
#cd htdocs/$PROJECT_FOLDER
#curl -sS https://getcomposer.org/installer | php
#php composer.phar install
#exit"
echo "curl...."
curl -sS https://getcomposer.org/installer | php || err "php curli epaonnistu" 20
php composer.phar install || err "php composer.phar install epaonnistui" 21

echo "Valmis! Sovelluksesi on nyt valmiina osoitteessa $TSOHA_DEPLOY"
exit  0
