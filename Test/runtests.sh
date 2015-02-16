#!/bin/bash

clear;

echo "yOrk: running unit tests, generating documentation";
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


php phpunit.phar --configuration $here/phpunit.xml --coverage-html $COVERAGEDIR --coverage-clover $COVERAGEDIR/coverage.clover $TESTPATH;
#php phpunit.phar --configuration $here/phpunit.xml --coverage-html $COVERAGEDIR --coverage-xml $COVERAGEDIR --coverage-php $COVERAGEDIR/coverage.php --coverage-clover $COVERAGEDIR/coverage.clover $here;
