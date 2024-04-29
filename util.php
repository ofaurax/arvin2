<?php

function token_found($tokens_dir, $token)
{
    $tdir = opendir($tokens_dir);

    while(($d = readdir($tdir)))
    {
        if(!preg_match('/([0-9]{8})-([^.]+)/', $d, $m)) continue;
        
        if($token != $m[2]) continue;
        $token_found = true;

        if(date('Ymd') > $m[1])
            return 1; // found invalid
        else
            return 2; // found & valid

        break;
    }
    return 0; // not found
}

function check_ref($docs_dir, $ref)
{
    if(isset($ref) && $ref
       && strpos($ref, '/') === FALSE
       && strpos($ref, '.') === FALSE
       && is_dir($docs_dir.'/'.$ref))
    {
        return true;
    }
    return false;
}

function check_file($docs_dir, $ref, $file)
{
    if(!check_ref($docs_dir, $ref)) return false;
    
    if(isset($file) && $file
       && is_file($docs_dir.'/'.$ref.'/'.$file))
    {
        return true;
    }
    return false;
}

// prends les lettres/chiffres après le dernier point d'un nom de fichier
// et les mets en minuscule
// ex: truc.pdf -> pdf, machin.TXT -> txt
function get_type($file)
{
    preg_match('/\.([a-zA-Z0-9]+)$/', $file, $m);
    if($m[1])
        return strtolower($m[1]);
    else
        return false;
}

function html_err($msg)
{
    echo '
<!DOCTYPE html>
<html>
<head><title>Message</title></head>
<body>
<h1>'.$msg.'</h1>
</body>
</html>
';
    exit(0);
}

function listing_build($dir)
{
    $a = array();
    $tmpd = opendir($dir);

    while(($d = readdir($tmpd)))
    {
        if($d[0] == '.') continue;

        if(is_file($dir.'/'.$d))
        {
            $a[$d] = $d;
        }
        elseif(is_dir($dir.'/'.$d))
        {
            $a[$d] = listing_build($dir.'/'.$d);
        }
        else
        {
            $a[$d] = 'invalid:'.$d;
        }
    }

    return $a;
}

function listing_render_list($listing, $token, $ref, $config, $base='.', $filebase='')
{
    echo '<ul class="listing">';

    //ksort($listing); // tri par clé
    uksort($listing, "strnatcmp"); // tri naturel par clé

    foreach($listing as $k => $v)
    {
        $is_conduc = (stristr($k, 'conduc') !== FALSE);

        echo '<li>';
        // on peut aussi tester si $v est Array
        if($k == $v)
        {
            echo $k;
            if(!$is_conduc)
            {
                echo ' : ';
                echo '<a href="'.$base.'/dl.php?'.
                 'token='.$token.
                 '&amp;ref='.$ref.
                 '&amp;file='.urlencode($filebase.$k).
                 '"><img class="icon2" src="pictures/download-alt-4-svgrepo-com.svg" alt="download-logo">téléchargement</a>';
                echo ' ('.round(filesize($config['docs_dir'].'/'.$ref.'/'.$filebase.$k)/1024).'Ko)';
                echo ' | ';
                echo '<a href="'.$base.'/dl.php?'.
                 'token='.$token.
                 '&amp;ref='.$ref.
                 '&amp;file='.urlencode($filebase.$k).
                 '&amp;inline'.
                 '"><img class="icon2" src="pictures/eye-svgrepo-com.svg" alt="eye-logo">voir en ligne</a>';
            }
        }
        else
        {
            echo $k;
            listing_render_list($v, $token, $ref, $config, $base, $filebase.$k.'/', $config);
        }
        echo '</li>';
    }
    echo '</ul>';
}

?>
