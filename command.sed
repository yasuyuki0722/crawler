curl http://www.rickynews.com/blog/archives/ | grep 'h1' | grep 'href="\/blog' | gsed -e 's/^<h1><a href="\(.*\)">\(.*\)<\/a><\/h1>$/\1/'
