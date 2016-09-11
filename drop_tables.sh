#!/bin/bash

function err {
	what=$1
	err=$2

	if [ $err -eq 0 ]; then
		echo "NORMAL: $what"
	else
		echo "FATAL: $what"
		exit $err
	fi
}


echo "Poistetaan tietokantataulut..."
cd /home/bgran/tsoha/Tsoha-Bootstrap/sql || err "Couldn't chdir to sql directory" 1
psql testi < dtop_tables.sql  || err "psql barffed" 2

echo "Valmis!"
exit 0
