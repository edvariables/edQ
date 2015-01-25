<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>Evènements des installations nucléaires</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <LINK REL="stylesheet" TYPE="text/css" HREF="outils/default.css">
                <!--
                <LINK REL="stylesheet" TYPE="text/css" HREF="outils/rezo.css">
                -->
                </head>

                <body>
                    <?php
                    ?>
                    <div class="tete">SCIN - Surveillance Citoyenne des Installations Nucléaires</div>
                    
                    <h1>Evènements des installations nucléaires<br />(stockés dans notre base de données, sources EDF et ASN)</h1>
                    <p><a href="base/appelEvtUneinstallation.php" target="_blank">Tous les évènements d'une installation (choix)</a></p>
                    <p><a href="base/appelEvtSurDates.php" target="_blank">Evènements par date</a></p>
                   
                <!--
                    <p><a href="base/appelDecUneinstallation.php" target="_blank">Décisions ASN par installation (choix)</a></p>
                    <p><a href="base/appelDecSurDates.php" target="_blank">Décisions ASN par dates</a></p>
                -->
                
                     <p></p>   
                <!--					
                    <p>&nbsp;&nbsp;Mises à jour
                        <a href="stockage/appelStockerDernEvtToutesCentrales.php" target="_blank">des derniers évènements EDF</a>- 
                        <a href="stockage/appelStockerDernIncidentASN.php" target="_blank">des derniers incidents ASN</a>-
                        <a href="stockage/appelStockerDernDecisions.php" target="_blank">des dernières décisions ASN</a>
                    </p> 
                -->

                    <h1>Informations concernant les installations nucléaires<br />(depuis les sites d'origine)</h1>
                    <h2>EDF :</h2>
                    <p>(energie.edf.com)</p>
                    <p><a href="appel/appelEvtCentrales.php" target="_blank">Derniers évènements des centrales</a></p>
                    <p><a href="appel/appelEvtUneCentrale.php" target="_blank">Tous les évènements d'une centrale (choix)</a></p>

                    <h2>ASN :</h2>
                    <p>(www.asn.fr)</p>                            
                    <p><a href="appel/appelASNActualites.php" target="_blank">Actualités</a></p>                           
                    <p><a href="appel/appelASNArretReacteurs.php" target="_blank">Arrêts de réacteurs</a> 
                        <small>ou<a href="appel/appelASNArretReacteursUneCentrale.php" target="_blank">Arrêts de réacteurs d'une centrale (choix)</a>
                        </small></p>  
                    <p><a href="appel/appelASNAvisDincidents.php" target="_blank">Avis d'incidents des installations nucléaires</a></p>          
                    <p><a href="appel/appelASNAvisASN.php" target="_blank">Avis de l'ASN</a></p>                    
                    <p><a href="appel/appelASNCourrierDePosition.php" target="_blank">Courriers de position</a></p>
                    <p><a href="appel/appelASNDecisions.php" target="_blank">Décisions de l'ASN</a>
                        <small>(ou dans la base<a href="base/appelDecUneinstallation.php" target="_blank">par installation (choix)</a>
                        ou<a href="base/appelDecSurDates.php" target="_blank">par dates</a>)</small>
                    </p>  
                    <p><a href="appel/appelASNSuiteDinspection.php" target="_blank">Lettres de suite d'inspection des installations nucléaires</a></p>
                   

                    <p><a href="appel/appelASNConsultPublic.php" target="_blank">Les consultations du public en cours</a></p>
                   
                    <p><a href="appel/appelASNAvisDispoPublic.php" target="_blank">Avis de mise à disposition du public organisée par les exploitants</a></p>
                   
                    <p><a href="appel/appelASNDeliberations.php" target="_blank">Délibérations de l'ASN</a></p>
                   
                    <!-- 
                    <p><a href="appel/appelASNrss.php" target="_blank">ASN : Actus : flux RSS (fichier XML brut)</a></p>                  

                    
                    <p><a href="base/appelTest.php" target="_blank">Test</a></p>
                    
                    <h3>ASN : Actualités…</h3>
                    <h3>ASN : Arrêt de réacteurs</h3>
                    <p><a href="appel/appelEssai.php target="_blank"">Essai</a></p>
                    
                    <h2>RTE :</h2>
                    <p><a href="appel/x.php" target="_blank">RTE : <em>Eco2mix</em> - téléchargement des fichiers de données quotidiens</a></p>

                    
                    <h3>ASN : Arrêt de réacteurs</h3>
                                        
                    <h1>tests</h1>
                    
                    -->
                          
                    <h1>&nbsp;</h1>
                    <p></p>
                    <small><a href="indexmaj.php">Mises à jour des tables</a></small>

                    <!--h1>&nbsp;</h1>
                    <p></p>
                    <p></p>
                    <p></p>
                    <pre><i><small>if faudrait voir si les flux RSS de l'ASN sont utilisables.
                    <a href="http://www.asn.fr/rss/feed/bulletin_asn">http://www.asn.fr/rss/feed/bulletin_asn</a>
                    </small></i></pre-->
                </body>
                </html>
