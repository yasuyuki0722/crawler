#!/bin/bash
#urlを引数として$2に持つ
PRE_IFS=$IFS
IFS=$'\n'
actopi_title=(`grep -Eo '<h1\sclass="entry-title">.*<\/h1>' $1 | sed 's/^<h1\sclass="entry-title">\(.*\)<\/h1>$/\1/'`)
actopi_date=(`grep 'class="sep"' $1 | grep -Eo "[0-9]{4}年[0-9]+月[0-9]+日" | sed -e 's/^\([0-9]\{4\}\)年\([0-9]\+\)月\([0-9]\+\)日$/\1\/\2\/\3/' `)
#grep '<title>' $0 | sed 's/^.*<title>\(.*\)<\/title>/\1/' 
#grep 'class="sep"' $0 | sed -e 's/^.*<span class="sep">\(.*\)<\/span>span.*$/\1/' 

#actopi_detail+=(`grep '<p>' ${file} | grep '&hellip' | sed 's/\s*//g' | sed 's/^<p>\(.*\)&hellip;<\/p>/\1/' `)
IFS=$PRE_IFS

#@TODO *は注意？

echo -e "$actopi_date\t$actopi_title\t$2\t$3.html" | tee -a actopi_url.tsv
