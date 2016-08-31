<?php

class ThemeHMAP implements Itheme
{
    public function getCSS()
    {
echo '<link href="//www.hmap.fr/wp-content/themes/twentyeleven-hmap/style.css" media="all" type="text/css" rel="stylesheet"></link>';
echo "
<style type=\"text/css\">
@font-face {
    font-family: 'PoppeaRegular';
    src: url('themes/hmap/poppea-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;
}
</style>
";
echo '
<style type="text/css">
h1, h2, h3
{
	font-family: PoppeaRegular, serif;
	font-size:200%;
}
</style>
';
    }

    public function getBanner()
    {
        echo '<a href="//hmap.fr/"><img width="1000" height="288" alt="" src="//www.hmap.fr/wp-content/uploads/2013/09/entete4.jpg" /></a>';
    }
}

$theme = new ThemeHMAP();

