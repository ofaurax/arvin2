<?php

require_once('config.php');

require_once($arv_config['theme_dir'].'/Itheme.php');
require_once($arv_config['theme_dir'].'/'.$arv_config['theme'].'/theme.php');

require_once('util.php');

$token_found = token_found($arv_config['tokens_dir'], $_GET['token']);
if($token_found < 1)
    html_err('Jeton d\'accès invalide');
elseif($token_found < 2)
    html_err('Jeton d\'accès expiré');

if(!check_ref($arv_config['docs_dir'], $_GET['ref']))
    html_err('Référence invalide');

$listing = listing_build($arv_config['docs_dir'].'/'.$_GET['ref']);
?>
<!DOCTYPE html>
<html>
<head>
<title>Téléchargements <?php echo $_GET['ref'] ?></title>

<?php
$theme->getCSS();
?>

<meta charset="utf-8"/>
</head>
<body>
<div id="page">

<?php
$theme->getBanner();
?>

<h1><a href="index.php?s=<?php echo $_GET['ref'] ?>"><?php echo $_GET['ref'] ?></a></h1>
<?php
listing_render_list($listing, $_GET['token'], $_GET['ref'], $arv_config);
?>
</div>
</body>
</html>