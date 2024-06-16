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
$sv = ''; $tv = '';
// recherche
if(isset($_GET['s'])) $sv = htmlspecialchars($_GET['s']);
// token
if(isset($_GET['t'])) $tv = (int)($_GET['t']);
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
</select>
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
        {
            if($i>=3) break;
        }
    }

    if($token_ok && is_dir($arv_config['docs_dir'].'/'.$l[3]))
    {
        echo '<div><a href="list.php?ref='.$l[3].'&token='.$_GET['token'].'" style="font-size:150%;font-weight:bolder;text-decoration:underline">Télécharger</a></div>';
    }
    echo "</div>\n";
}
echo '</div>';

//echo '</p>';


?>
</div>
</body>
</html>
