<?php
/*
Arvin2, créé par Olivier FAURAX le 12/8/2013
Dernière version sur https://github.com/ofaurax/arvin2
*/

require_once('config.php');

require_once($arv_config['theme_dir'].'/Itheme.php');
require_once($arv_config['theme_dir'].'/'.$arv_config['theme'].'/theme.php');

require_once('util.php');

$token = (isset($_GET['token']) ? $_GET['token'] : '');
$token_ok = (token_found($arv_config['tokens_dir'], $token) >= 2);
    
$f = 'Archives_HMAP.csv';

// Instuments minimum pour pouvoir jouer un morceau
// Ex: au moins flûte 1, clar 1, sax 1, etc. 
$instru_oblig = array(
    'Conducteur',
    'Flûte 1',
    'Hautbois 1 ',
    'Clarinette 1 Sib',
    'Clarinette Basse',
    'Sax Alto 1',
    'Sax Ténor 1 ',
    'Sax Baryton',
    'Trompette 1',
    'Cor fa 1 + Mib',
    'Trombone Ut 1',
    'Baryton sib clé de sol',
    'Basse Sib clé de fa',
    'Batterie',
    'Percussion 1',
);

// Tous les instruments de l'HMAP
// $instru_oblig + piccolo, flûte 2, clar 2/3, sax 2/3, etc.
$instru_hmap =
array(
    'Piccolo',
    'Flûte 2 ',
    'Hautbois 2',
    'Clarinette 2 Sib',
    'Clarinette 3 Sib',
    'Sax Alto 2 ',
    'Sax Alto 3 ',
    'Sax Ténor 2',
    'Bugle 1',
    'Bugle 2',
    'Cornet 1',
    'Cornet 2',
    'Cornet 3',
    'Trompette 2',
    'Trompette 3',
    'Cor fa 2 + Mib',
    'Trombone Ut 2',
    'Tuba sib clé de fa',
    'Euphonium Ut clé de fa',
    'Euph 1 Sib clé de fa + sol',
    'Basse Sib clé de sol',
    'Percussion 2',
    'Timbales',
);

$instru_hmap = array_merge($instru_hmap, $instru_oblig);

// Autres instrus
/*
    'Ptte flûte réb',
    'Basson 1',
    'Basson 2',
    'Petite Clarinette Mib',
    'Clarinette Alto',
    'Clarinette Solo',
    'Sax. Soprano',
    'Sax Basse',
    'Cor fa 3 + Mib',
    'Cor fa 4 + Mib',
    'Trombone Ut 3',
    'Trombone Ut 4',
    'Trombone Sib 1 clé fa',
    'Trombone Sib2 Clé fa',
    'Trombone Sib3 clé fa',
    'Trombone Sib 4 clé fa',
    'Trombone sib 1 clé sol',
    'TromboneSib2 clé de sol',
    'Trombone Sib 3 clé de sol',
    'Tuba Ut clé de fa',
    'Euphonium 2 Sib clé de fa',
    'Contrebasse Sib clé de fa',
    'Contrebasse Sib clé de sol',
    'Contrebasse Mib clé de fa',
    'Contrebasse Mib clé de sol',
    'Contrebasse Ut clé de Fa',
    'Divers',
*/

?>
<!DOCTYPE html>
<html>
<head>
<title>Arvin2 : archiviste virtuel</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
$theme->getCSS();
?>

<style type="text/css">

table {
  width: 100%;
}

td {
 border: 1px solid grey;
}

td, th {
 padding : 2px;
}

li {
  display: inline-block;
    margin-right: 2em;
}

.manquant {
    text-decoration: line-through;
} 
.hmap.manquant {
    color: orange;
}
.oblig.manquant {
    color: red;
    font-weight: bold;
}
li {
  color: grey;
}
.hmap {
    color: black;
}

.score {
  margin: 0.5em;
  padding-left:0.5em;
  width:19rem;
  border-left:2px grey solid;
}

@media print {
    body { font-size:8pt; }
    h1 { font-size:10pt; }
    .noprint { display:none; }
}

</style>
</head>
<body>
<div id="page">
<?php
$theme->getBanner();
?>

<?php
$sv = ''; $tv = ''; $lv = 0;
// recherche
if(isset($_GET['s'])) $sv = htmlspecialchars($_GET['s']);
// token
if(isset($_GET['t'])) $tv = (int)($_GET['t']);
// avec instruments
if(isset($_GET['l'])) $lv = ($_GET['l'] ? true : false);
?>
<!-- <p style="float:right;padding:0;margin:0"><em><?php echo $f ?></em></p> -->
    <h1 class="entry-title">Arvin <span style="color:#ff379b">l'archiviste</span></h1>

<!-- Select program -->
<form method="get" class="noprint">
    <?php
    if($token_ok)
    {
        echo '<input type="hidden" name="token" value="'.$token.'" />';
    }
    ?>
    Programme :
    <select name="s">
        <?php
            $tmpd = opendir($arv_config['pgm_dir']);
            $programmes = [];
            while($d = readdir($tmpd))
            {
                if($d[0] == '.') continue;
                $r = preg_match("/(\d+)/", $d, $m);
                if($r)
                {
                    $k = $m[0];
                }
                else
                {
                    $k = "Autre";
                }
                $programmes[$k][] = $d;
            }
            krsort($programmes);

            foreach($programmes as $k => $p)
            {
                foreach($p as $d)
                {
                    if(substr($d, -4) != '.txt') continue;
                    $p = substr($d, 0, -4);
                    echo "<option value=\"pgm:{$p}\"";
                    if($sv == "pgm:{$p}") echo " selected";
                    echo ">{$p}</option>";
                }
            }
            echo "<option value=\"\"";
            if(substr($sv, 0, 4) != 'pgm:') echo " selected";
            echo ">Tous les morceaux</option>";
        ?>
    </select>
    <input type="submit" value="Voir le programme"/>
</form>
<br/>

<form method="get" class="noprint">
<?php
if($token_ok)
{
  echo '<input type="hidden" name="token" value="'.$token.'">';
}
?>
<input name="s" value="<?php echo $sv ?>">
<input type="submit" value="Cherche !">
Tri par <select name="t">
<option value="0" <?php if($tv==0) echo 'selected' ?>>Titre</option>
<option value="1" <?php if($tv==1) echo 'selected' ?>>Auteur</option>
<option value="2" <?php if($tv==2) echo 'selected' ?>>Référence</option>
<option value="3" <?php if($tv==3) echo 'selected' ?>>Complétion</option>
</select>
<!--
     <input type="checkbox" id="l" name="l" <?php if($lv) echo 'checked' ?>><label for="l">avec instruments</label>
-->
</form>

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

// Trie par la colonne $i
function tri_ligne($a, $b, $i)
{
    if($a[$i] == $b[$i]) return 0;
    if($a[$i] < $b[$i] ) return -1;
    return 1;
}

function tri_par_titre($a, $b) {return tri_ligne($a, $b, 0);}
function tri_par_auteur($a, $b) {return tri_ligne($a, $b, 1);}
function tri_par_ref($a, $b) {return tri_ligne($a, $b, 3);}
function tri_par_completion($a, $b) {return tri_ligne($a, $b, 'completion');}

switch($tv)
{
    default:
    case 0:
    usort($data, 'tri_par_titre');
    break;
    case 1:
    usort($data, 'tri_par_auteur');
    break;
    case 2:
    usort($data, 'tri_par_ref');
    break;
    case 3:
    $data_sorted = array();
    foreach($data as $k => $l)
    {
        $oblig_compt = 0;
        $hmap_compt = 0;
        for($j=4; $j<count($l);$j++)
        {
            if(!$entete[$j]) continue; // passe les colonnes vides
            
            $en_stock = ($l[$j] !== '' && $l[$j] !== '0');
            if($en_stock && in_array($entete[$j], $instru_oblig)) $oblig_compt++;
            if($en_stock && in_array($entete[$j], $instru_hmap)) $hmap_compt++;
        }
        $key = sprintf('%03d', (int)(100*$oblig_compt/count($instru_oblig))).'_'.
               sprintf('%03d', (int)(100*$hmap_compt/count($instru_hmap))).'_'.
               $l[3]; // reference pour le tri en cas d'égalité
        $data_sorted[$key] = $l;
    }
    krsort($data_sorted);
    $data = array_values($data_sorted);
    break;
}

$pgm = array();
if(substr($sv, 0, 4) == 'pgm:'
   && is_file($arv_config['pgm_dir'].'/'.substr($sv, 4).'.txt'))
{
    echo '<h2 class="entry-title">Programme '.str_replace('_', ' ', substr($sv, 4)).'</h2>';
    $pgm = file(
        $arv_config['pgm_dir'].'/'.substr($sv, 4).'.txt',
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
//print_r($pgm);

echo '<div id="partlist" style="display:inline-flex;flex-wrap:wrap">';

foreach($data as $l)
{
    if(!$l || !$l[0]) continue;

    if($sv)
    {
        if(
            stripos($l[0], $sv) === FALSE
            && stripos($l[1], $sv) === FALSE
            && stripos($l[2], $sv) === FALSE
            && stripos($l[3], $sv) === FALSE
            && !in_array($l[3], $pgm)
        )
        continue;
    }

    echo "\n".'<div class="score">';
    for($i = 0; $i < sizeof($l); $i++)
    {
        $c = $l[$i];
        echo "\n".'<div>';
        if($i==0)
        {
            echo '<h2>'.$c.'</h2>';
            echo ' [<a href="http://www.youtube.com/results?search_query='.urlencode($c.', '.$l[$i+1].', '.$l[$i+2]).'">youtube</a>]';
            echo ' [<a href="http://musicainfo.net/quiksrch.php?vol='.urlencode($c).'">musicainfo</a>]';
        }
        else
            echo $c;
        echo '</div>';
        //if(!$lv)
        {
            if($i>=3) break;
        }
    }

    $oblig_compt = 0;
    $hmap_compt = 0;
    for($j=4; $j<count($l);$j++)
    {
        if(!$entete[$j]) continue; // passe les colonnes vides
        
        $en_stock = ($l[$j] !== '' && $l[$j] !== '0');
        if($en_stock && in_array($entete[$j], $instru_oblig)) $oblig_compt++;
        if($en_stock && in_array($entete[$j], $instru_hmap)) $hmap_compt++;
    }
    /*
       // Désactivation complétion
       echo '<td>'.(int)(100*$oblig_compt/count($instru_oblig)).'% / '.
       (int)(100*$hmap_compt/count($instru_hmap)).'%</td>';
     */
    if($token_ok && is_dir($arv_config['docs_dir'].'/'.$l[3]))
    {
        echo '<div><a href="list.php?ref='.$l[3].'&token='.$_GET['token'].'" style="font-size:150%;font-weight:bolder;text-decoration:underline">Télécharger</a></div>';
    }
    echo "</div>\n";

    if($lv)
    {
        echo '</div>';
        //var_dump($l);
        echo '<ul>';
        $oblig_compt = 0;
        for($j=4; $j<count($l);$j++)
        {
            if(!$entete[$j]) continue; // passe les colonnes vides

            $class = '';
            if(in_array($entete[$j], $instru_hmap)) $class .= ' hmap';
            if(in_array($entete[$j], $instru_oblig)) $class .= ' oblig';

            $en_stock = ($l[$j] !== '' && $l[$j] !== '0');
            if(!$en_stock) $class .= ' manquant';
            if($en_stock && in_array($entete[$j], $instru_oblig)) $oblig_compt++;

            echo '<li';
            if($class) echo ' class="'.$class.'"';
            echo '>';
            echo $entete[$j];
            if($en_stock) echo ' ('.$l[$j].')';
            echo '</li>';
        }
        echo '</ul>';

        echo '<div>';
    }

}
echo '</div>';

//echo '</p>';


?>
</div>
</body>
</html>
