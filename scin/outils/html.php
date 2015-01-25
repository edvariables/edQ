<?php

/**
 * classe statique utilitaires (x)HTML
 *
 * @author jm
 */
final class Html {
    // Constantes
    // balises HTML

    const B_BR = '<br />';
    const B_H1 = '<h1>';
    const B_H1F = '</h1>';
    const B_H2 = '<h2>';
    const B_H2F = '</h2>';
    const B_H3 = '<h3>';
    const B_H3F = '</h3>';
    const B_UL = '<ul>';
    const B_ULF = '</ul>';
    const B_LI = '<li>';
    const B_LIF = '</li>';
    const B_PAR = '<p>';
    const B_PARf = '</p>';
    const B_DIV = '<div>';
    const B_DIVf = '</div>';
    const B_SPAN = '<span>';
    const B_SPANf = '</span>';
    const B_SMALL = '<small>';
    const B_SMALLF = '</small>';
    const B_BODY = '<body>';
    const B_BODYf = '</body>';
    const B_HTMLf = '</html>';

    /* ==========================================================
     * Fonctions renvoie chaine de caractère en paragraphe html
     * =======================================================  */
    const BALISE_PARAGRAPHE = 0;
    const BALISE_DIV = 1;
    const BALISE_SPAN = 2;

    public static function paragrapheHtml($texte, $type = 0) {
        $RC = chr(13);
        $baliseDebut = '';
        $baliseFin = '';

        switch ($type) {
            case self::BALISE_PARAGRAPHE:
                $baliseDebut = Html::B_PAR;
                $baliseFin = Html::B_PARf;
                break;
            case self::BALISE_DIV:
                $baliseDebut = Html::B_DIV;
                $baliseFin = Html::B_DIVf;
                break;
            case self::BALISE_SPAN:
                $baliseDebut = Html::B_SPAN;
                $baliseFin = Html::B_SPANf;
                break;
            default:
                $baliseDebut = Html::B_PAR;
                $baliseFin = Html::B_PARf;
                break;
        }
        return $RC . $baliseDebut . $texte . $baliseFin . $RC;
    }

    /* ==========================================================
     * Fonctions renvoie chaine de caractère en balise html
     * au choix
     * =======================================================  */

    const SANS_RETOUR_LIGNE = 0;
    const AVEC_RETOUR_LIGNE = 1;

    public static function parHtmlBal($texte, $balise = 'p', $classe = '', $retChariot = Html::AVEC_RETOUR_LIGNE) {
        $RC = chr(13);
        
        $baliseDebut = '<' . $balise;
        if ($classe != '') {
            $baliseDebut .= ' class="'.$classe.'"';
        }
        $baliseDebut .= '>';

        $baliseFin = '</' . $balise . '>';
        $retour = '';

        // Retour ligne avant pour les entetes
        if (substr($balise, 0, 1) == 'h') {
            $retour .= $RC; // par défaut
        }

        $retour .= $baliseDebut . $texte . $baliseFin;

        if ($retChariot == Html::AVEC_RETOUR_LIGNE) {
            $retour .= $RC; // par défaut
        }
        return $retour;
    }

    /*
     * Fonction générant un objet Select à partir d'un tableau
     */

    const S_SEL = "<select name='";
    const S_SELf = "</select>";
    const S_OPT = "    <option value='";
    const S_OPT_SEL = "    <option selected='selected' value='";
    const S_OPTf = "</option>";
    const S_BALf = "'>";

    public static function MettreTableauDansObjetSelect(array $tb_liste, $nomSelection, $rangSelection = 0) {
        $RC = chr(13);
        $LF = chr(10);
        // balise select avec le name
        $selectListe = self::S_SEL . $nomSelection . self::S_BALf . $RC;
        $indListe = 0;

        foreach ($tb_liste as $indListe => $valueliste) {

            if ($indListe == (int) $rangSelection) {
                //echo $indListe;
                $selectListe .= self::S_OPT_SEL;
            } else {
                $selectListe .= self::S_OPT;
            }

            $selectListe .= $valueliste . self::S_BALf; // pour value
            $selectListe .= $valueliste . self::S_OPTf . $RC; // pour affichage dans le select
        }
        $selectListe .= self::S_SELf . $RC;

        return $selectListe;
    }

    /*
     * Afficher un entete de page xhtml standard
     * @param string $titre titre de la page html 
     */

    public static function enteteFichierHtml($titre, $css = '') {
        $RC = chr(13);
        $LF = chr(10);

        $entete = '';
        $entete .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
        $entete .= ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . $RC;
        $entete .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">' . $RC;
        $entete .= '<head>' . $RC;
        $entete .= '<title>' . $titre . '</title>' . $RC;
        $entete .= '<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8"/>' . $RC;
        if ($css != '') {
            // <LINK REL="stylesheet" TYPE="text/css" HREF="evtsCentrales/outils/rezo.css">
            $entete .= '<LINK REL="stylesheet" TYPE="text/css" HREF="' . $css . self::F_BALf . $RC;
        }
        $entete .= ' </head >' . $RC;
        //echo htmlentities($entete);
        return $entete;
    }

    public static function finFichierHtml() {
        $RC = chr(13);
        return $RC . Html::B_BODYf . $RC . Html::B_HTMLf;
    }

    /*     * ************************************
     *         Formulaires
     * *************************************** */
    //'<form name="form1" method="post" action="exo13b_appel.php" enctype="application/x-www-form-urlencoded">'
    // methode POST par défaut

    const F_FORM_NAME = '<form name="';
    const F_FORM_POST = '" method="post" ';
    const F_FORM_GET = '" method="get" ';
    const F_FORM_ACTION = ' action="';
    const F_FORM_ENCTYPE = '" enctype="';
    const F_BALf = '">';
    const F_FORMf = '</form>';
    const METHODE_POST = 0;
    const METHODE_GET = 1;

    public static function formulaireHtmlDebut($nom, $action, $methode = self::METHODE_POST, $encType = self::ENCTYPE_APP) {
        $RC = chr(13);
        $LF = chr(10);

        $formulaire = $RC . '<!--  Debut formulaire  -->' . $RC;
        $formulaire .= Html::F_FORM_NAME . $nom;

        if ($methode === self::METHODE_POST) {
            $formulaire .= Html::F_FORM_POST;
        } else {
            $formulaire .= Html::F_FORM_GET;
        }
        $formulaire .= Html::F_FORM_ACTION . $action;
        $formulaire .= Html::F_FORM_ENCTYPE . $encType . Html::F_BALf . $RC;
        return $formulaire;
    }

    public static function formulaireHtmlFin() {
        $RC = chr(13);
        $formulaire = $RC . Html::F_FORMf . $RC;
        $formulaire .= '<!--  Fin formulaire  -->' . $RC;
        return $formulaire;
    }

    // enctype

    const ENCTYPE_APP = 'application/x-www-form-urlencoded';
    const ENCTYPE_DATA = 'multipart/form-data';
    const ENCTYPE_TXT = 'text/plain';

    // paramétrage des objets input
    const INPUT_NONE = 0;
    const INPUT_LABEL = 1;
    const INPUT_COMPL_BALISE = 2;
    const INPUT_CHAINE = 3;

    /**-----------------------------------------
     *  Génère un bouton d’envoi de formulaire
     ------------------------------------------ */
    public static function setSubmitForm($nom, $legende = "Envoyer") {
        $RC = chr(13);
// <p><input type="submit" name="nom" value="Envoyer" /></p> 
        $codeBtn = '';
        $codeBtn .= self::setFormatInputForm('submit', $nom, $legende);
        return $codeBtn;
    }

    /**-----------------------------------------
     * Gère les balises de Fieldset et légende
     ------------------------------------------ */
    public static function setLegendForm($legend = NULL) {
        $RC = chr(13);
        if ($legend != NULL) {
            return "<fieldset>" . $RC . "<legend>" . $legend . "</legend>" . $RC;
        } else {
            return "</fieldset>" . $RC;
        }
    }

    public function setTextForm($nom, $label, $valeur = '') {
        /*  <p><label>Titre : <br />
          <input type="text" name="nom" value="" /></label></p>
         */
        $codeTexte = self::setFormatInputForm('text', $nom, $valeur, self::INPUT_LABEL, $label);
        return $codeTexte;
    }

    /**------------------------------------------------
     * Génère une balise html Input avec ses attributs
     * (texte ou bouton)
     * ----------------------------------------------- */
    public static function setFormatInputForm($type, $nom, $valeur, $input = self::INPUT_NONE, $complement = '', $check = false) {

        $RC = chr(13);
        $codeInp = self::B_PAR;
        if ($input == self::INPUT_LABEL) {
            $codeInp .= '<label>' . $complement . $RC;
        }
        $codeInp .= '<input type="' . $type . '" ';
        $codeInp .= 'name="' . $nom . '" ';
        $codeInp .= 'value="' . $valeur . '"';
        if ($check) {
            $codeInp .= ' checked="checked"';
        }
        $codeInp .= ' />';
        if ($input == self::INPUT_CHAINE) {
            $codeInp .= $complement . self::B_BR . $RC;
        }
        if ($input == self::INPUT_LABEL) {
            $codeInp .='</label>';
        }
        $codeInp .= self::B_PARf . $RC;
        return $codeInp;
    }

    /* ==============================================
     * génère un lien html
     * ============================================== */

    const L_AHREF = '<a href="';
    const L_BALf = '">';
    const L_AHREFf = '</a>';

    public static function lienHtml($url, $legende = '') {
        $RC = chr(13);
        $lien = Html::L_AHREF . $url . Html::L_BALf;
        if ($legende != '') {
            $lien .= $legende;
        } else {
            $lien .= $url;
        }
        $lien .= Html::L_AHREFf . $RC;
        return $lien;
    }

    /* ==============================================
     *  renvoie une url récupérée dans un <a href=
      ============================================== */

    const argRecherche = '<a href="';
    const longueurArgRecherche = 9;

    public static function urlDunLien($chaineHtml) {
        if(preg_match('/^http/', $chaineHtml))
            return $chaineHtml;
        $url = strstr($chaineHtml, self::argRecherche); // tronque ce qui précède le lien

        if ($url !== false) {
            $positionFin = strpos($url, '"', self::longueurArgRecherche); // position guillemet de fin

            if ($positionFin !== false) {
                $positionDebut = self::longueurArgRecherche;
                $longueur = $positionFin - $positionDebut;
                $url = substr($url, $positionDebut, $longueur);
            } else {
                $url = '';
            }
        } else {
            $url = '';
        }

        return $url;
    }

}

?>
