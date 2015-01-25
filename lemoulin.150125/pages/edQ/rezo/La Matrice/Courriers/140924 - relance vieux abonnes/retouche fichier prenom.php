<?php
$filename = "c:\\temp\\141017 - Courrier React Ancs Abos - 2431 adrs.csv";
if(!file_exists($filename))
	throw new Exception("Fichier $filename introuvable");
$filename_dest = $filename . ".2.csv";
$fhdest = fopen("$filename_dest","w");

$counter = 0 ;

   if($fh = fopen("$filename","r")){ 
      while (!feof($fh)){ 
         $line = fgets($fh); 
		 if(!$line) continue;
		  $line  =explode("\t", preg_replace('/[\r\n]$/', '', $line));
		  $nom_init = explode(" ", $line[1]);
		  if($counter == 0){
			  $line[] = "Prénom";
			  $line[] = "Bonjour";
			  $line[] = "1ereLigneDuTexte";
		  }
		  else {
			  if(count($nom_init) > 1){
				  $prenom = $nom_init[0];
				  for($i = 1; $i < count($nom_init) - 1; $i++)
					if($nom_init[$i] && $nom_init[$i] === strtoupper($nom_init[$i]))
					   break;
					else
					   $prenom .= ' ' . $nom_init[$i];
				$prenom = trim($prenom);
				  if(strlen($prenom) < 2 || $prenom == 'Mr' || $prenom == 'Mme')
					  $prenom = '';
			  }
			  else
				  $prenom = "";
			  $line[] = $prenom;
			  $line[] = "Bonjour " . $prenom;
			  $line[] = ($prenom ? ($prenom . ', v') : 'V')
				  . "otre participation peut faire une vraie différence ! Nous comptons sur votre soutien... Merci !";
		  }
		  //var_dump($fhdest, implode("\t", $line));
		  fwrite ($fhdest, implode("\t", $line) . "\r\n");
		//if($counter++ > 20)
		//  break;
		  $counter++;
      } 
      fclose($fh); 
    } 

      fclose($fhdest); 
?>