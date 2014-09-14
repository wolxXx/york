#!/bin/bash

clear;

echo "York: generating documentation, adding new svn files";
echo "";
echo "";

here=$(dirname $(readlink -f $0));
cd $here;
cd ..;
cd ..;

phpdoc -d York -t York/documentation -i York/documentation -i York/Test

NEWFILES=$(svn status | grep "?" | cut -d " " -f8);
for NEWFILE in $NEWFILES
do
	echo "new file in svn: $NEWFILE";
	svn add $NEWFILE;
done;

cd $here;
