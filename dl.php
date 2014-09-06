<?php

require_once('config.php');
require_once('util.php');

$token_found = token_found($arv_config['tokens_dir'], $_GET['token']);

if($token_found < 1)
    html_err('Jeton d\'accès invalide');
elseif($token_found < 2)
    html_err('Jeton d\'accès expiré');

if(!check_ref($arv_config['docs_dir'], $_GET['ref']))
    html_err('Référence invalide');

if(!check_file($arv_config['docs_dir'], $_GET['ref'], $_GET['file']))
    html_err('Fichier invalide');

$type = get_type($_GET['file']);
if($type == 'pdf')
{
    header('Content-Type: application/pdf;');// charset=iso-8859-1');
    if(!isset($_GET['inline']))
    {
        header('Content-Disposition: attachment; filename="'.basename($_GET['file']).'"');
    }
    readfile($arv_config['docs_dir'].'/'.$_GET['ref'].'/'.$_GET['file']);
}
elseif($type == 'txt')
{
    header('Content-Type: text/plain;');// charset=iso-8859-1');
    echo file_get_contents($arv_config['docs_dir'].'/'.$_GET['ref'].'/'.$_GET['file']);
}
else
{
    html_err('Type de fichier non-pris en charge');
}

?>
