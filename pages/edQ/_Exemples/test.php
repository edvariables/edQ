<?php
		  $nom_init = explode(" ", "Marielle et Xavier SCHILDKNECHT RABILLOUD");
		  var_dump($nom_init);
			  if(count($nom_init) > 1){
				  $prenom = $nom_init[0];
				  for($i = 1; $i < count($nom_init) - 1; $i++){
					if($nom_init[$i] == null)
						continue;
					  else if($nom_init[$i] === strtoupper($nom_init[$i])){
					   	echo(" ==== "); var_dump($nom_init[$i]);
						   break;
					  }
					else
					   $prenom .= ' ' . $nom_init[$i];
					  
					  var_dump($prenom, $nom_init[$i]);
				  }
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
		  
		  var_dump($line);
?>