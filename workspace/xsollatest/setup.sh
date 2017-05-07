#!/usr/bin/env bash
rm .phalcon/migration-version&&
ln -s '~/workspace/phalcon-devtools/phalcon.php' '/usr/bin/phalcon'&&
chmod ugo+x '/usr/bin/phalcon'&&mysql -u phalcon -e "create database xsollatest"&&
phalcon migration run&&
python3 "tests/loadtest.py"&&
