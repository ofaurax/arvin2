<?php

class ThemeHMAP implements Itheme
{
    public function getCSS()
    {
        echo '<link href="http://www.hmap.fr/wp-content/themes/twentyeleven-hmap/style.css" media="all" type="text/css" rel="stylesheet"></link>';
    }

    public function getBanner()
    {
        echo '<img width="1000" height="288" alt="" src="http://www.hmap.fr/wp-content/uploads/2013/09/entete4.jpg"></img>';
    }
}

$theme = new ThemeHMAP();

