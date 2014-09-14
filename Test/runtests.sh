#!/bin/bash

clear;

echo "YORK: running unit tests, generating documentation";
echo "";
echo "";

here=$(dirname $(readlink -f $0));
cd $here;

pwd;

if [ "$#" -lt 1 ]; then
	TESTPATH='.';
else
	TESTPATH=$1
fi

if [ "$#" -lt 2 ]; then
	COVERAGEDIR="$here/Coverage";
else
	COVERAGEDIR=$2
fi

if [ -d $COVERAGEDIR ]; then
	echo ""
else
	mkdir -p $COVERAGEDIR;
fi



php phpunit.phar --configuration $here/phpunit.xml --coverage-html $COVERAGEDIR $TESTPATH;
#phpunit --configuration $here/phpunit.xml --coverage-html $COVERAGEDIR $TESTPATH;
#phpunit --colors --bootstrap TestBootstrap.php  --coverage-html $COVERAGEDIR $TESTPATH;
