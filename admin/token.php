<?php

require_once('../util.php');

// Debug
//echo realpath('token.php');

$tokendir = '../private/tokens';

?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">

    </head>
    <body>
        <h1>Gestionnaire de jetons d'accès</h1>

        <?php
        if($_POST['action'] == 'creer')
        {
            echo '<strong>NOUVEAU jeton !</strong><br/>';

            $rand = '';
            for($i=0; $i<16; $i++)
                $rand .= chr(random_int(1, 26)+0x40+random_int(0,1)*0x20);

            $f = $_POST['annee'].$_POST['mois'].$_POST['jour'].'-'.$rand.'.txt';
            echo $f.'<br/>';
            touch($tokendir.'/'.$f);            
        }

        
        $tokens = array();
        foreach(scandir($tokendir) as $f)
        {
            if(!preg_match('/([0-9]{8})-([^.]+).*/', $f, $m)) continue;
            $tokens[] = array(
                'date' => $m[1],
                'rand' => $m[2],
                'valid' => (token_found($tokendir, $m[2]) == 2),
                'fichier' => $m[0]
            );   
        }

        if($_POST['action'] == 'suppr')
        {
            echo '<strong>SUPPRESSION du jeton...</strong><br/>';
            foreach($tokens as $t)
            {
                if($t['rand'] != $_POST['del']) continue;
                
                echo $tokendir.'/'.$t['fichier'];
                unlink($tokendir.'/'.$t['fichier']);
            }

            // Mise à jour après suppression
            $tokens = array();
            foreach(scandir($tokendir) as $f)
            {
                if(!preg_match('/([0-9]{8})-([^.]+).*/', $f, $m)) continue;
                $tokens[] = array(
                    'date' => $m[1],
                    'rand' => $m[2],
                    'valid' => (token_found($tokendir, $m[2]) == 2),
                    'fichier' => $m[0]
                );   
            }
        }


        ?>
        

        <h2>Jetons actuels</h2>
        <ul>
            <?php
            foreach($tokens as $t)
            {
                echo '<li>';
                echo $t['date'].' '.$t['rand'];
                if(!$t['valid'])
                    echo ' Expiré';
                else
                    echo ' Valide : <a href="../index.php?token='.$t['rand'].'">Lien magique</a>';
                echo '</li>';
            }
            ?>
        </ul>

        <h2>Ajouter un jeton</h2>

        <form action="" method="post" accept-charset="UTF-8">
            <label for="annee">Année</label>
            <input type="text" id="annee" name="annee" value="<?php echo date('Y')+1; ?>" />
            <label for="mois">Mois</label>
            <input type="text" id="mois" name="mois" value="<?php echo date('m'); ?>"/>
            <label for="jour">Jour</label>
            <input type="text" id="jour" name="jour" value="<?php echo date('d'); ?>"/>
            <button type="submit" name="action" value="creer">Créer un jeton</button>
        </form>


        <h2>Supprimer un jeton</h2>

        <form action="" method="post" accept-charset="UTF-8">
            <label for="del">Jeton à supprimer</label>
            <select id="del" name="del" >
                <?php
                foreach($tokens as $t)
                {
                    echo '<option value="'.$t['rand'].'">'.$t['date'].' '.$t['rand'].'</option>';
                }
                ?>
            </select>
            <button type="submit" name="action" value="suppr">Supprimer ce jeton</button>
        </form>

        <pre>

            <?php //var_dump($_POST); ?>
        </pre>
    </body>
</html>
