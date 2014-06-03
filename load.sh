#!/bin/bash
#mysql -u root --password= -e "select count(*) from calendar.actopi"
mysql -u root --password= -e "load data local infile 'actopi_url.tsv' into table calendar.actopi(@date, @title, @link, @file) set date=@date, title=@title, link=@link, file=@file, created_at=NOW(), update_at=NOW();"
cat /dev/null > actopi_url.tsv
