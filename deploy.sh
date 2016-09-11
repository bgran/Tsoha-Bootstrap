#!/bin/bash

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


# Missä kansiossa komento suoritetaan
#DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

#source $TSOHA_BASE/config/environment.sh

echo "Siirretään tiedostot users-palvelimelle..."

# Tämä komento siirtää tiedostot palvelimelta
rsync -zr $TSOHA_BASE/app $TSOHA_BASE/assets $TSOHA_BASE/config $TSOHA_BASE/lib $TSOHA_BASE/sql $TSOHA_BASE/vendor $TSOHA_BASE/index.php $TSOHA_BASE/composer.json $TSOHA_DEPLOY || err "rsync epaonnistu" 12
#rsync -z -r $DIR/app $DIR/assets $DIR/config $DIR/lib $DIR/sql $DIR/vendor $DIR/index.php $DIR/composer.json $USERNAME@users.cs.helsinki.fi:htdocs/$PROJECT_FOLDER

echo "Valmis!"

echo "Suoritetaan komento php composer.phar dump-autoload..."

# Suoritetaan php composer.phar dump-autoload
#ssh $USERNAME@users.cs.helsinki.fi "
#cd htdocs/$PROJECT_FOLDER
cd $TSOHA_DEPLOY || err "epaonnistui chdir $TSOHA_DEPLOY" 10
php composer.phar dump-autoload || err "php composer.phar dump-autoload epaonnistu" 13
#exit"

#echo "Valmis! Sovelluksesi on nyt valmiina osoitteessa $USERNAME.users.cs.helsinki.fi/$PROJECT_FOLDER"
echo "Done, sovelllus on $TSOHA_DEPLOY -hakemistossa"
exit 0
