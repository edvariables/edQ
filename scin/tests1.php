
<?php

include_once '../outils/rezo.php';
include_once '../outilsCurl/outilsCurlFonctions.php';
include_once '../outilsCurl/HTTPQuery.php';

// Tests provisoires

echo Html::B_H1 . 'Tests' . Html::B_H1F . Html::B_BR;
echo 'hello<br />';

if (extension_loaded('curl')) {
    echo 'avec cUrl !';
} else {
    echo 'sans cUrl :-((';
}
echo Html::B_BR;


// Tests récupération url

echo Html::B_H1 . 'Tests URL' . Html::B_H1F . Html::B_BR;

$url = 'http://energie.edf.com/';
echo Html::B_BR . 'DBG1 ' . $url . Html::B_BR;

if (true) {
    
    if (http_check_url($url)) {
    echo Html::B_BR . 'DBG2 ' . Html::B_BR;
        $pageATraiter = http_fetch_url($url);

        if ($pageATraiter === FALSE) {
            die('la page ' . $url . ' ne s\'est pas chargée.');
        } else {
            echo 'la page ' . $url . ' est correctement chargée.';
            //var_dump($pageATraiter); // la page html interprétée
            //echo htmlentities($pageATraiter); // le source
        }
    } else {
        die('la page ' . $url . ' n\'a pas été trouvée.');
    }

echo 'Fin test url' . Html::B_BR;
    
    
} else {
    //$curl = new OutilsCurl();
    echo Html::B_BR . 'DBG2 ' . Html::B_BR;

   // if ($curl->http_check_url($url)) {
        //$pageATraiter = $curl->http_fetch_url($url);
    if (OutilsCurl::http_check_url($url)) {
    echo Html::B_BR . 'DBG3 ' . Html::B_BR;
        $pageATraiter = OutilsCurl::http_fetch_url($url);
    echo Html::B_BR . 'DBG4 ' . Html::B_BR;

        if ($pageATraiter === FALSE) {
            die('la page ' . $url . ' ne s\'est pas chargée.');
        } else {
            var_dump($pageATraiter);
            //echo htmlentities($pageATraiter);
        }
    } else {
        die('la page ' . $url . ' n\'a pas été trouvée.');
    }
}
echo 'Fin test url' . Html::B_BR;
?>
