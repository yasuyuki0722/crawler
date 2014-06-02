#!/bin/bash

#urlを変数
url='http://aucfan.com/article'
#ページ数をとる
lastpage=`curl -s http://aucfan.com/article | grep 'page-number num' | tail -n 1 | sed -e "s/^\s*<li><a class='page-number num' href='\(.*\)'>\(.*\)<\/a><\/li>$/\2/"`
echo $lastpage
