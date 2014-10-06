<!doctype html>
<html
<head>
<title>Uploader</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<form method="post" action="upload.php" enctype="multipart/form-data">
<ul>
<li>Fichier :<input type="file" value="Fichier" name="file" /></li>
<li>Référence :<input type="text" name="ref" /></li>
<input type="submit" value="Upload!" />
</ul>
</form>
<pre>
<?php

var_dump($_FILES);
var_dump($_POST);

$upload_dir = 'private/input/';
foreach($_FILES as $f)
    {
        if($f['error'])
            {
                echo $f['error']."\n";
                continue;
            }
        $filename = $f['name'];

        // Pas d'upload de .php
        if(substr($filename, -4) == '.php') continue;

        // Fichier existe
        while(file_exists($upload_dir.'/'.$_POST['ref'].'/'.$filename))
            {
                echo 'Le fichier existe déjà, changement de nom'."\n";
                $filename = 'X'.$filename;
                continue;
            }

        @mkdir($upload_dir.'/'.$_POST['ref']);
        move_uploaded_file($f['tmp_name'], $upload_dir.'/'.$_POST['ref'].'/'.$filename);
        echo 'Fichier envoyé !'."\n";
    }

/*
var_dump($_SERVER);
phpinfo();
*/
  echo 'File uploads: '.get_cfg_var('file_uploads')."\n";
  echo 'upload_max_filesize: '.get_cfg_var('upload_max_filesize')."\n";
  echo 'max_input_time: '.get_cfg_var('max_input_time')."\n";
  echo 'memory_limit: '.get_cfg_var('memory_limit')."\n";
  echo 'max_execution_time: '.get_cfg_var('max_execution_time')."\n";
  echo 'post_max_size: '.get_cfg_var('post_max_size')."\n";

?>
</pre>
</body>