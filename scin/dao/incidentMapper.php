<?php

/**
 * Cette classe récupère un curseur résultant d'1 requete sous forme d'un tableau
 * et alimente l'objet correspondant.
 *
 * @author jm
 */
final class IncidentMapper {

    /**
     * Maps array to the given {@link Todo}.
     * <p>
     * Propriétés:
     * <ul>
     *   <li>id</li>
     *   <li>installation</li>
     *   <li>libelleInstallation</li>
     *   <li>gravite</li>
     *   <li>titre</li>
     *   <li>dateIncident</li>
     *   <li>texte</li>
     *   <li>texteDetail</li>
     *   <li>fichierTelecharge</li>
     *   <li>url</li>
     *   <li>page</li>
     *   <li>dateCapture</li>
     *   <li>origine</li>
     * </ul>
     * @param Incident $incident
     * @param array $properties
     */
    public static function map(Incident $incident, array $proprietes) {
        if (array_key_exists('id', $proprietes)) {
            $incident->setId($proprietes['id']);
        }
        if (array_key_exists('installation', $proprietes)) {
            $incident->setInstallation($proprietes['installation']);
        }
        if (array_key_exists('libelleInstallation', $proprietes)) {
            $incident->setLibelleInstallation($proprietes['libelleInstallation']);
        }
        if (array_key_exists('gravite', $proprietes)) {
            $incident->setGravite($proprietes['gravite']);
        }
        if (array_key_exists('titre', $proprietes)) {
            $incident->setTitre($proprietes['titre']);
        }
        if (array_key_exists('dateIncident', $proprietes)) {
            $incident->setDateIncident($proprietes['dateIncident']);  
        }
        if (array_key_exists('texte', $proprietes)) {
            $incident->setTexte(trim($proprietes['texte']));
        }
        if (array_key_exists('texteDetail', $proprietes)) {
            $incident->setTexteDetail(trim($proprietes['texteDetail']));
        }
        if (array_key_exists('fichierTelecharge', $proprietes)) {
            $incident->setFichierTelecharge(trim($proprietes['fichierTelecharge']));
        }
        if (array_key_exists('url', $proprietes)) {
            $incident->setUrl(trim($proprietes['url']));
        }
        if (array_key_exists('page', $proprietes)) {
            $incident->setPage(trim($proprietes['page']));
        }
        if (array_key_exists('dateCapture', $proprietes)) {
            $incident->setDateCapture($proprietes['dateCapture']);  
        }
        if (array_key_exists('origine', $proprietes)) {
            $incident->setOrigine(trim($proprietes['origine']));
        }
    }

    public static function mapToArray(Incident $incident) {
        $proprietes = array ();
        $proprietes['id'] = $incident->getId();
        $proprietes['installation'] = $incident->getInstallation();
        $proprietes['libelleInstallation'] = $incident->getLibelleInstallation();
        $proprietes['gravite'] = $incident->getGravite();
        $proprietes['titre'] = $incident->getTitre();
        $proprietes['dateIncident'] = $incident->getDateIncident();
        $proprietes['texte'] = $incident->getTexte();
        $proprietes['texteDetail'] = $incident->getTexteDetail();
        $proprietes['fichierTelecharge'] = $incident->getFichierTelecharge();
        $proprietes['url'] = $incident->getUrl();
        $proprietes['page'] = $incident->getPage();
        $proprietes['dateCapture'] = $incident->getDateCapture();
        $proprietes['origine'] = $incident->getOrigine();
        $proprietes['_source'] = 'Incident';
        return $proprietes;        
    }

}

?>
