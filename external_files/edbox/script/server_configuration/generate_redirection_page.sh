#! /bin/bash

PrintBegin() {
    echo "<ul>"
}

PrintEnd() {
    echo "</ul>"
}

PrintLinks() {
    echo "$1" |
	while read -r line
	do
	    URL="`echo $line | cut -d ' ' -f 1`"
	    VALUE="`echo $line | cut -d ' ' -f 2-`"
	    VALUE="${VALUE//\"}"
	    echo "<li><a href='http://$URL' target='_blank' rel='noopener'>$VALUE</a></li>"
	done
}

PrintHtml() {
    PrintBegin
    PrintLinks "$1"
    PrintEnd
}

ReplaceSpecialCharacters() {
    cat | sed -r 's/é/\&eacute;/g; s/è/\&egrave;/g'
}

FindHomePageId() {
    SQL_REQUEST="SELECT ID FROM wp_posts WHERE post_title='Contenue Pedagogique' AND post_status='publish';"
    
    echo $SQL_REQUEST | mysql -u root -prasp wordpress | sed 1d
}

GenerateNewPage() {
    SQL_REQUEST="UPDATE wp_posts SET post_content = \"$1\" WHERE ID = \"$2\";"

    echo $SQL_REQUEST
}

if [ $# == 0 ]
then
    FILE="website_list.conf"
else
    FILE="$1"
fi

PAGES="`sed -e 's/#.*$//' -e '/^$/d' $FILE`"
HTML="`PrintHtml "$PAGES" | ReplaceSpecialCharacters`"
HOMEPAGE_ID="`FindHomePageId`"

GenerateNewPage "$HTML" "$HOMEPAGE_ID" | mysql -u root -prasp wordpress &&
    (echo ok; exit 0) ||
	(echo ko; exit 1)
