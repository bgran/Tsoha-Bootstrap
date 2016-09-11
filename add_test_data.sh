#!/bin/bash

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


#//source config/environment.sh

cd /home/bgran/tsoha/Tsoha-Bootstrap/sql || err "Couldn't chdir to sql directory" 1
cat add_test_data.sql | psql -1 -f - testi || err "psql barffed" 2

echo "Valmis!"
exit 0


