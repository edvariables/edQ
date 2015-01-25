<?php

/*
 * Liste des installations prioritairement surveillées
 * par le SCIN
 */

final class InstallationsPrioritaires {

    public static function genereListeCentralesEnFonction() {
        $lstCtrlEnFonction = array();
        $lstCtrlEnFonction[0] = 'Belleville-sur-Loire';
        $lstCtrlEnFonction[1] = 'Blayais';
        $lstCtrlEnFonction[2] = 'Bugey';
        $lstCtrlEnFonction[3] = 'Cattenom';
        $lstCtrlEnFonction[4] = 'Chinon';
        $lstCtrlEnFonction[5] = 'Chooz';
        $lstCtrlEnFonction[6] = 'Civaux';
        $lstCtrlEnFonction[7] = 'Cruas-Meysse';
        $lstCtrlEnFonction[8] = 'Dampierre-en-Burly';
        $lstCtrlEnFonction[9] = 'Fessenheim';
        $lstCtrlEnFonction[10] = 'Flamanville';
        $lstCtrlEnFonction[11] = 'Golfech';
        $lstCtrlEnFonction[12] = 'Gravelines';
        $lstCtrlEnFonction[13] = 'Nogent-sur-Seine';
        $lstCtrlEnFonction[14] = 'Paluel';
        $lstCtrlEnFonction[15] = 'Penly';
        $lstCtrlEnFonction[16] = 'Saint-Alban';
        $lstCtrlEnFonction[17] = 'Saint-Laurent-des-Eaux';
        $lstCtrlEnFonction[18] = 'Tricastin';
        return $lstCtrlEnFonction;
    }
    public static function genereListeCentralesDemanteles() {
        $lstCtrlDemantele = array();
        $lstCtrlDemantele[0] = 'Brennilis';
        $lstCtrlDemantele[1] = 'Bugey 1';
        $lstCtrlDemantele[2] = 'Chinon A';
        $lstCtrlDemantele[3] = 'Chooz A';
        $lstCtrlDemantele[4] = 'Phénix'; // réacteur
        $lstCtrlDemantele[5] = 'St Laurent A';
        $lstCtrlDemantele[6] = 'Creys-Malville'; // Super-Phénix
        return $lstCtrlDemantele;
    }

    public static function genereListeAutresInstallations() {
        $lstAutresInstall = array();
        // CEA avec préfixe
        $lstAutresInstall[0] = 'CEA Cadarache';
        $lstAutresInstall[1] = 'CEA Fontenay-aux-Roses';
        $lstAutresInstall[2] = 'CEA Marcoule';
        $lstAutresInstall[3] = 'CEA Saclay';
        $lstAutresInstall[4] = 'CEA Valduc';
        // CEA SANS préfixe (juste le site)
        $lstAutresInstall[5] = 'Cadarache';
        $lstAutresInstall[6] = 'Fontenay-aux-Roses';
        $lstAutresInstall[7] = 'Marcoule'; // site (de Phénix)
        $lstAutresInstall[8] = 'Saclay';
        // autres sites
        $lstAutresInstall[9] = 'Valduc';
        $lstAutresInstall[10] = 'Centraco';
        $lstAutresInstall[11] = 'Centre de stockage de l\'Aube';
        $lstAutresInstall[12] = 'Centre de stockage de la Manche';
        $lstAutresInstall[13] = 'Comurhex Malvési';
        $lstAutresInstall[14] = 'Comurhex Pierrelatte';
        $lstAutresInstall[15] = 'FBFC'; // Romans-sur-Isère';
        $lstAutresInstall[16] = 'Georges Besse I';
        $lstAutresInstall[17] = 'Georges Besse II';
        $lstAutresInstall[18] = 'ICEDA';
        $lstAutresInstall[19] = 'ITER';
        $lstAutresInstall[20] = 'Laboratoire de Bure';
        $lstAutresInstall[21] = 'Bure'; // seul
        $lstAutresInstall[22] = 'La Hague';
        $lstAutresInstall[23] = 'Melox';
        $lstAutresInstall[24] = 'Socatri';
        return $lstAutresInstall;
    }

    public static function genereListeToutesInstallations() {
        $i=0;
        $lstInstallations = array();
        
        // les plus spécifiques d'abord => l'ordre est voulu
        
        $lstInstallations[$i] = 'Flamanville 3 - EPR';
        
        $lstCtrlDemantele = self::genereListeCentralesDemanteles();
        foreach ($lstCtrlDemantele as $installation) {
            $i++;
            $lstInstallations[$i] = $installation;
        }

        $lstCtrlEnFonction = self::genereListeCentralesEnFonction();
        foreach ($lstCtrlEnFonction as $installation) {
            $i++;
            $lstInstallations[$i] = $installation;
        }
        
        $lstAutresInstall = self::genereListeAutresInstallations();
        foreach ($lstAutresInstall as $installation) {
            $i++;
            $lstInstallations[$i] = $installation;
        }
        return $lstInstallations;
    }
    
public static function genereListeCtrlDemantelesPourEvts() {
        $lstDemEvt[0] = 'Brennilis';
        $lstDemEvt[1] = 'Creys-Malville';
        return $lstDemEvt;
    }
}

?>
