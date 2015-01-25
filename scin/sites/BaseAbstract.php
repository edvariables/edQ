<?php

abstract class BaseAbstract extends ASNabstract {

    protected $url = '';
    protected $listeObjets = array();
    protected $paramsRecherche;

    abstract protected function listeParametresRecherche($champ, $valeur, $valeur2 = '');

    public function afficherListeDepuisBase() {

        //echo Html::parHtmlBal(Html::parHtmlBal($this->paramsRecherche, 'em'), 'div');
        echo Html::parHtmlBal($this->paramsRecherche, 'h1');

        if (count($this->infosItem) == 0) {
            echo Html::parHtmlBal('Aucune information trouvée pour cette demande.', 'h2');
            return;
        } else {
            // OK
        }

        $bPremierPassage = true;

        foreach ($this->infosItem as $item) {
            if ($bPremierPassage) {
                $info = 'Nombre d\'informations dans la liste : ' . count($this->infosItem);
                echo Html::parHtmlBal((Html::B_SMALL . $info . Html::B_SMALLF), 'div');
                $bPremierPassage = false;
            }
//
            if (!simple_html_dom_node::is_utf8($item['libelleInstallation'])) {
                $item['libelleInstallation'] = utf8_encode($item['libelleInstallation']);
            }
            if (!simple_html_dom_node::is_utf8($item['installation'])) {
                $item['installation'] = utf8_encode($item['installation']);
            }
            if (!simple_html_dom_node::is_utf8($item['texte'])) {
                $item['texte'] = utf8_encode($item['texte']);
            }
            if (!simple_html_dom_node::is_utf8($item['texteDetail'])) {
                $item['texteDetail'] = utf8_encode($item['texteDetail']);
            }
            if (!simple_html_dom_node::is_utf8($item['fichierTelecharge'])) {
                $item['fichierTelecharge'] = utf8_encode($item['fichierTelecharge']);
            }
//

            if ($item['libelleInstallation'] != '') {
                echo Html::parHtmlBal($item['libelleInstallation'], 'h2');
            } else {
                if (array_key_exists('installation', $item)) {
                    echo Html::parHtmlBal($item['installation'], 'h2');
                }
            }
            $date = rezo::dateAMJversJMAstr($item['dateIncident']);
            $libelleH3 = $date . ' - ' . $item['titre'];
            //$libelleH3 = $item['titre'];
            if (array_key_exists('id', $item)) {
                $libelleH3 .= ' (id scin = ' . $item['id'] . ')';
            }
            if (array_key_exists('origine', $item)) {
                $libelleH3 .= ' - ' . $item['origine'];
            }

            if (!simple_html_dom_node::is_utf8($libelleH3)) {
                $libelleH3 = utf8_encode($libelleH3);
            }
             if ($item['origine'] == 'ASN arret')
                $classe = 'arret';
            else
                $classe = $item['origine'];
            echo Html::parHtmlBal($libelleH3, 'h3', $classe);
            //var_dump($item['dateIncident']);

            if (array_key_exists('decision', $item)) {
                if ($item['decision'] != '') {
                    echo Html::parHtmlBal($item['decision'], 'h4');
                }
            }
            if (array_key_exists('installation', $item)) {
                if ($item['installation'] != '') {
                    if ($item['libelleInstallation'] != '') {
                        echo Html::parHtmlBal($item['installation'], 'h4');
                    }
                }
            }
// Selon la source            
            $b_AffichageStandard = true;

            if (array_key_exists('_source', $item)) {
                if ($item['_source'] == 'DecisionsASN') {
                    $b_AffichageStandard = false;
                } else {
                    $b_AffichageStandard = true;
                }
            }

// affichage standard
            if ($b_AffichageStandard) {
                if ($item['texteDetail'] != '') {
                    echo Html::parHtmlBal(Html::parHtmlBal($item['texte'], 'em'), 'div'); // en italique
                    echo Html::parHtmlBal('');
                    echo Html::parHtmlBal($item['texteDetail'], 'div');
                } else {
                    echo Html::parHtmlBal($item['texte'], 'div');
                }
            } else {
// non standard, selon la source                
                switch ($item['_source']) {
                    case 'DecisionsASN':
                        $libelleDatePublication = 'Date de publication : ';
                        $libelleDatePublication .= rezo::dateAMJversJMAstr($item['texteDetail']);
                        echo Html::parHtmlBal(Html::parHtmlBal($libelleDatePublication,'em'), 'div');
                        echo Html::parHtmlBal($item['texte'], 'div');
                        break;

                    default:
                        break;
                }
            }

            if (array_key_exists('fichierTelecharge', $item)) {
                if ($item['fichierTelecharge'] != '') {
                    echo Html::parHtmlBal($item['fichierTelecharge'], 'p');
                }
            }

            $url = html::lienHtml(trim($item['url'])); // on rend le lien cliquable
            $info = 'Source : ' . $url;
            echo Html::parHtmlBal((Html::B_SMALL . $info . Html::B_SMALLF), 'div');
        }
    }

    public function getParamsRecherche() {
        return $this->paramsRecherche;
    }

}

?>
