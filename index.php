<?php
/*
Arvin2, créé par Olivier FAURAX le 12/8/2013
Dernière version sur https://github.com/ofaurax/arvin2
*/

require_once('config.php');
require_once('util.php');

$token = (isset($_GET['token']) ? $_GET['token'] : '');
$token_ok = (token_found($arv_config['tokens_dir'], $token) >= 2);
    
$f = 'Archives_HMAP_130922.csv';

?>
<!DOCTYPE html>
<html>
<head>
<title>Arvin2 : archiviste virtuel</title>
<meta charset="utf-8"/>
<style type="text/css">
td {
 border: 1px solid grey;
}

@media print {
    body { font-size:8pt; }
    h1 { font-size:10pt; }
    .noprint { display:none; }
}

</style>
</head>
<body>
<?php
$sv = ''; $tv = ''; $lv = 0;
if(isset($_GET['s'])) $sv = htmlspecialchars($_GET['s']);
if(isset($_GET['t'])) $tv = (int)($_GET['t']);
if(isset($_GET['l'])) $lv = ($_GET['l'] ? true : false);
?>
<p style="float:right;padding:0;margin:0"><em><?php echo $f ?></em></p>
<h1>Arvin2 : listing partition HMAP</h1>
<form method="get" class="noprint">
<?php
if($token_ok)
{
  echo '<input type="hidden" name="token" value="'.$token.'" />';
}
?>
<input name="s" value="<?php echo $sv ?>" />
<input type="submit" value="Cherche !"/><br/>
Tri par :<select name="t">
<option value="0" <?php if($tv==0) echo 'selected' ?>>titre</option>
<option value="1" <?php if($tv==1) echo 'selected' ?>>auteur</option>
<option value="2" <?php if($tv==2) echo 'selected' ?>>référence</option>
</select>
<input type="checkbox" id="l" name="l" <?php if($lv) echo 'checked' ?>/><label for="l">avec instruments</label>
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
    echo '<h2>Programme '.str_replace('_', ' ', substr($sv, 4)).'</h2>';
    $pgm = file(
        $arv_config['pgm_dir'].'/'.substr($sv, 4).'.txt',
        FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}
//print_r($pgm);

echo '<table>';
echo '<tr>';
$i = 0;
foreach($entete as $c)
{
    echo '<th>'.$c.'</th>';
    $i++;
    if(!$lv)
    {
        if($i>=4) break;
    }
}
if($token_ok)
{
    echo '<th>Téléchargement</th>';
}
echo '</tr>';

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

  echo '<tr>';
  $i = 0;
  foreach($l as $c)
  {
    echo '<td>';
    if($i==0) echo '<a href="http://www.youtube.com/results?search_query='.urlencode($c).'">';
    echo $c;
    if($i==0) echo '</a>';
    echo '</td>';
    $i++;
      if(!$lv)
      {
          if($i>=4) break;
      }
  }
  if($token_ok && is_dir($arv_config['docs_dir'].'/'.$l[3]))
  {
      echo '<td><a href="list.php?ref='.$l[3].'&token='.$_GET['token'].'">Documents</a></td>';
  }
  else
  {
      echo '<td></td>';
  }
  echo '</tr>';
}
echo '</table>';

?>
</body>
</html>