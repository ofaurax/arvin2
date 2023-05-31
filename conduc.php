<?php
/*
Arvin2, créé par Olivier FAURAX
Voir index.php
*/

require_once('config.php');

require_once($arv_config['theme_dir'].'/Itheme.php');
require_once($arv_config['theme_dir'].'/'.$arv_config['theme'].'/theme.php');

require_once('util.php');

$token = (isset($_GET['token']) ? $_GET['token'] : '');
$token_ok = (token_found($arv_config['tokens_dir'], $token) >= 2);
    
$f = 'Archives_HMAP.csv';

?>
<!DOCTYPE html>
<html>
<head>
<title>Arvin2 : liste conducteurs</title>
<meta charset="utf-8"/>

<?php
$theme->getCSS();
?>
<style type="text/css">
p { margin-bottom:initial }
</style>
</head>
<body>
<div id="page">
<?php
$theme->getBanner();
?>

<?php
$l = 1;
$of = fopen($f, 'r');
fgetcsv($of);
$entete = fgetcsv($of);
while($l)
{
    $l = fgetcsv($of);
    $data[] = $l;
}
?>
<h1>Liste des conducteurs</h1>

<?php
    foreach($data as $k => $l)
    {
        if($token_ok && is_dir($arv_config['docs_dir'].'/'.$l[3]))
        {
            $list = listing_build($arv_config['docs_dir'].'/'.$l[3]);
            foreach($list as $k => $p)
            {
                if(stristr($k, 'conduc') === FALSE) continue;
                echo '<p><a href="list.php?ref='.$l[3].'&token='.$_GET['token'].'">'.$l[3].'</a> ';
                echo $l[0].' ('.$k.")</p>\n";
            }
            //echo "<p>".var_dump($l)."</p>";
        }
        
    }
?>
</body>
</html>