<?php

$f = 'Archives_HMAP_130803.csv';

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
</style>
</head>
<body>
<?php
$sv = ''; $tv = ''; $lv = 0;
if(isset($_GET['s'])) $sv = htmlspecialchars($_GET['s']);
if(isset($_GET['t'])) $tv = (int)($_GET['t']);
if(isset($_GET['l'])) $lv = ($_GET['l'] ? true : false);
?>
<h1>Arvin2 : listing partition HMAP</h1>
<p><em>Fichier : <?php echo $f ?></em></p>
<form method="get">
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

echo '<table>';
echo '<tr>';
$i = 0;
foreach($entete as $c)
{
    echo '<th>'.$c.'</th>';
    if(!$lv)
    {
        $i++;
        if($i>=4) break;
    }
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
      if(!$lv)
      {
          $i++;
          if($i>=4) break;
      }
  }
  echo '</tr>';
}
echo '</table>';

?>
</body>
</html>