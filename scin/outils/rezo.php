<?php

/**
 * classe statique utilitaires divers
 *
 * @author jm
 */
final class Rezo {

    // --------------------------------------
    //   entete standard pages html CSIN
    // --------------------------------------
    public static function enteteFichierHtml($titre, $h1 = TRUE) {
        $entete = Html::enteteFichierHtml($titre, "../outils/default.css");
        echo $entete;
        echo Html::B_BODY;
        $bandeau = 'SCIN - Surveillance Citoyenne des Installations Nucléaires';
        echo Html::parHtmlBal($bandeau, 'div', 'tete');
        if ($h1) {
            echo Html::parHtmlBal($titre, 'h1');
        }
    }

    public static function dateJMMMMAversAMJstr($date) {
        // reçoit = 12 octobre 2006
        // renvoi = 2006/08/12
        $parts = explode(' ', preg_replace('/\s+/', ' ', $date));

        $mois_lettreMin = array(
            "janvier" => "01",
            "février" => "02",
            "mars" => "03",
            "avril" => "04",
            "mai" => "05",
            "juin" => "06",
            "juillet" => "07",
            "août" => "08",
            "septembre" => "09",
            "octobre" => "10",
            "novembre" => "11",
            "décembre" => "12"
        );
        
        if(array_key_exists($parts[1], $mois_lettreMin))
            $parts[1] = $mois_lettreMin[$parts[1]] ;
        else
            foreach($mois_lettreMin as $mois_nom => $mois_num)
                if(stripos($mois_nom, $parts[1])>=0){
                    $parts[1] = $mois_num;
                }
        return $parts[2] . '/' . $parts[1] . '/' . $parts[0];
    }

    public static function dateJMAversAMJstr($date) {
        // reçoit = 12/08/2006
        // renvoi = 2006/08/12
        return substr($date, 6, 4) . "/" . substr($date, 3, 2) . "/" . substr($date, 0, 2);
    }

    public static function dateAMJversJMAstr($date) {
        // reçoit = 2006/08/12
        // renvoi = 12/08/2006
        return substr($date, 8, 2) . "/" . substr($date, 5, 2) . "/" . substr($date, 0, 4);
    }

    public static function dateMoisEnLettreversJMA($dateBrute) {

        $mois_lettreMin = array(
            "janvier" => "01",
            "février" => "02",
            "mars" => "03",
            "avril" => "04",
            "mai" => "05",
            "juin" => "06",
            "juillet" => "07",
            "août" => "08",
            "septembre" => "09",
            "octobre" => "10",
            "novembre" => "11",
            "décembre" => "12"
        );

        foreach ($mois_lettreMin as $moisLettre => $mois2chiffres) {
            $position = strpos($dateBrute, $moisLettre);
            if ($position !== false) {
                $jour = substr($dateBrute, 0, 2);
                if (substr($jour, 1, 1) == ' ') {
                    $jour = '0' . substr($jour, 0, 1);
                }
                $dateAMJ = substr($dateBrute, $position + strlen($moisLettre) + 1, 4) . '/' . $mois2chiffres . '/' . $jour;
                return $dateAMJ;
            }
        }

        $mois_lettreMaj = array(
            "Janvier" => "01",
            "Février" => "02",
            "Mars" => "03",
            "Avril" => "04",
            "Mai" => "05",
            "Juin" => "06",
            "Juillet" => "07",
            "Août" => "08",
            "Septembre" => "09",
            "Octobre" => "10",
            "Novembre" => "11",
            "Décembre" => "12"
        );

        foreach ($mois_lettreMaj as $moisLettre => $mois2chiffres) {
            $position = strpos($dateBrute, $moisLettre);
            if ($position !== false) {
                $dateAMJ = substr($dateBrute, strlen($dateBrute) - 4) . '/' . $mois2chiffres . '/' . substr($dateBrute, 0, 2);
                return $dateAMJ;
            }
        }
        return null;
    }

    /**
     * Format date.
     * @param DateTime $date date to be formatted
     * @return string formatted date Jour/mois Année
     */
    public static function formatDateFrancaise(DateTime $date = null) {
        if ($date === null) {
            return '';
        }
        return $date->format('d/m/Y');
    }

    // Renvoie une date en français à partir d'une date SQL (YYYY-MM-DD)
    // Code proposé par Le Caphar http://www.lepotlatch.org

    public static function date_fr($date, $court = FALSE) {
        // Format court 12/08/2006
        if ($court == TRUE) {
            return substr($date, 8, 2) . "/" . substr($date, 5, 2) . "/" . substr($date, 0, 4);
        }

        // Format long 12 août 2006
        $mois_conv = array(
            "01" => "janvier",
            "02" => "février",
            "03" => "mars",
            "04" => "avril",
            "05" => "mai",
            "06" => "juin",
            "07" => "juillet",
            "08" => "août",
            "09" => "septembre",
            "10" => "octobre",
            "11" => "novembre",
            "12" => "décembre"
        );
        if (substr($date, 8, 2) != "00") {
            $datefr['jour'] = substr($date, 8, 2);
        }
        if (substr($date, 5, 2) != "00") {
            // Majuscule au mois s'il n'y a pas de jour défini (00)
            if (!$datefr['jour']) {
                $datefr['mois'] = ucfirst($mois_conv[substr($date, 5, 2)]);
            } else {
                $datefr['mois'] = $mois_conv[substr($date, 5, 2)];
            }
        }
        if (substr($date, 0, 4) != "0000") {
            $datefr['annee'] = substr($date, 0, 4);
        }

        if ($datefr) {
            $date = join(" ", $datefr);
            return $date;
        }
    }

}

?>
