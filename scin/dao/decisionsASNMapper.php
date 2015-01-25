<?php

/**
 * Cette classe récupère un curseur résultant d'1 requete sous forme d'un tableau
 * et alimente l'objet correspondant.
 *
 * @author jm
 */
final class DecisionsASNMapper {

    /**
     * Maps array to the given {@link Todo}.
     * <p>
     * Propriétés:
     * <ul>
     *   <li>id</li>     
     *   <li>idASN</li>
     *   <li>installation</li>
     *   <li>dateDecision</li>
     *   <li>datePublication</li>
     *   <li>texte</li>
     *   <li>fichierTelecharge</li>
     *   <li>url</li>
     *   <li>dateCapture</li>
     * </ul>
     * @param Incident $decisionsASN
     * @param array $properties
     * 
      Correspondance : (Incident = objet standard)

      Incidents               decisionsASN
      ---------               ------------
      id                      id
      installation            installation
      gravité
      titre                   *** idASN ***
      dateIncident            *** dateDecision
      texte                   texte
      texteDetail             *** datePublication ***
      fichierTelecharge       fichierTelecharge
      url                     url
      page
      dateCapture             dateCapture
      origine                 (toujours ASN)

     */
    public static function map(DecisionsASN $decisionsASN, array $proprietes) {
        if (array_key_exists('id', $proprietes)) {
            $decisionsASN->setId($proprietes['id']);
        }
        if (array_key_exists('idASN', $proprietes)) {  //  ****
            $decisionsASN->setIdASN($proprietes['idASN']);
        } else {
            if (array_key_exists('titre', $proprietes)) {
                $decisionsASN->setIdASN($proprietes['titre']);
            }
        }
        if (array_key_exists('installation', $proprietes)) {
            $decisionsASN->setInstallation($proprietes['installation']);
        }
        if (array_key_exists('libelleInstallation', $proprietes)) {
            $decisionsASN->setLibelleInstallation($proprietes['libelleInstallation']);
        }
        if (array_key_exists('dateDecision', $proprietes)) {  //  ****
            $decisionsASN->setDateDecision($proprietes['dateDecision']);
        } else {
            if (array_key_exists('dateIncident', $proprietes)) {
                $decisionsASN->setDateDecision($proprietes['dateIncident']);
            }
        }
        if (array_key_exists('datePublication', $proprietes)) {  //  ****
            $decisionsASN->setDatePublication($proprietes['datePublication']);
        } else {
            if (array_key_exists('texteDetail', $proprietes)) {
                $decisionsASN->setDatePublication($proprietes['texteDetail']);
            }
        }
        if (array_key_exists('texte', $proprietes)) {
            $decisionsASN->setTexte(trim($proprietes['texte']));
        }
        if (array_key_exists('fichierTelecharge', $proprietes)) {
            $decisionsASN->setFichierTelecharge(trim($proprietes['fichierTelecharge']));
        }
        if (array_key_exists('url', $proprietes)) {
            $decisionsASN->setUrl(trim($proprietes['url']));
        }
        if (array_key_exists('dateCapture', $proprietes)) {
            $decisionsASN->setDateCapture($proprietes['dateCapture']);
        }
    }

    public static function mapToArray(DecisionsASN $decisionsASN) {
        $proprietes = array();
        $proprietes['id'] = $decisionsASN->getId();
        $proprietes['titre'] = $decisionsASN->getIdASN();
        $proprietes['installation'] = $decisionsASN->getInstallation();
        $proprietes['libelleInstallation'] = $decisionsASN->getLibelleInstallation();
        $proprietes['dateIncident'] = $decisionsASN->getDateDecision();
        $proprietes['texte'] = $decisionsASN->getTexte();
        $proprietes['texteDetail'] = $decisionsASN->getDatePublication();
        $proprietes['fichierTelecharge'] = $decisionsASN->getFichierTelecharge();
        $proprietes['url'] = $decisionsASN->getUrl();
        $proprietes['dateCapture'] = $decisionsASN->getDateCapture();
        $proprietes['_source'] = 'DecisionsASN';
        return $proprietes;
    }

    public static function mapToArraySpecific(DecisionsASN $decisionsASN) {
        $proprietes = array();
        $proprietes['id'] = $decisionsASN->getId();
        $proprietes['idASN'] = $decisionsASN->getIdASN();  //  ****
        $proprietes['libelleInstallation'] = $decisionsASN->getLibelleInstallation();
        $proprietes['dateDecision'] = $decisionsASN->getDateDecision();  //  ****
        $proprietes['datePublication'] = $decisionsASN->getDatePublication();  //  ****
        $proprietes['texte'] = $decisionsASN->getTexte();
        $proprietes['fichierTelecharge'] = $decisionsASN->getFichierTelecharge();
        $proprietes['url'] = $decisionsASN->getUrl();
        $proprietes['dateCapture'] = $decisionsASN->getDateCapture();
        return $proprietes;
    }

}

?>
