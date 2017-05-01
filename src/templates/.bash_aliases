#!/bin/bash
#
# Phalcon Box
#
# Copyright (c) 2011-2017, Phalcon Framework Team
#
# The contents of this file are subject to the New BSD License that is
# bundled with this package in the file LICENSE.txt
#
# If you did not receive a copy of the license and are unable to obtain it
# through the world-wide-web, please send an email to license@phalconphp.com
# so that we can send you a copy immediately.
#

alias cls=clear

function psgrep
{
	if [ -z $1 ]; then
		echo -e "Usage: psgrep <appname> | awk '{print \$2}' | xargs kill"
	else
		ps aux | grep "$1" | grep -v grep
	fi
}

function myexport()
{
	FILE=${1:-/vagrant/mysqldump.sql.gz}
	echo "Exporting databases to '$FILE'"

	mysqldump -uphalcon --skip-lock-tables 2>/dev/null | gzip > "$FILE"

	echo "Done."
}

function myimport()
{
	FILE=${1:-/vagrant/mysqldump.sql.gz}
	echo "Importing databases from '$FILE'"

	cat "$FILE" | zcat | mysql 2>/dev/null

	echo "Done."
}

function share()
{
	if [[ "$1" ]]; then
		ngrok http ${@:2} -host-header="$1" 80
	else
		echo "Error: missing required parameters."
		echo "Usage: "
		echo "  share domain"
		echo "Invocation with extra params passed directly to ngrok"
		echo "  share domain -region=eu -subdomain=test1234"
	fi
}
