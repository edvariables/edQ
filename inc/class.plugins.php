<?php
class Plugins {
    public static var $defaults = array(
	'jstree' => true,
	'flot' => true,
	'jqGrid' => true,
	'colorpicker' => true,
	'dataTables' => true,
	'markitup' => true,
	'codemirror' => true,
    );
    
    /* plugins à charger dans index.php
	*
	* d'après index.php
	* d'après la session de l'utilisateur
	* d'après l'url ?q--plugins[]=dataTables,jqGrid
	* */
       public static $plugins = FALSE;
       
       /**
	* initialisation des plugins actifs
	* */
       /* TODO : configurable dans edQ.conf et initialisable d'après variables de session */
       public static function init($plugins = FALSE){
	       $defaults = self::$plugins_defaults;
	       if(!is_array($plugins))
		       $plugins = $defaults;
	       else
		       /* valeurs par défaut (sinon plantage de index.php) */
		       foreach($defaults as $key=>$value)
			       if(!isset($plugins[$key]))
				       $plugins[$key] = $defaults[$key];
	       /* valeurs issues de la session */
	       if(isset($_SESSION)
		  && isset($_SESSION['edq-user'])
		  && isset($_SESSION['edq-user']['plugins'])
	       )
		       foreach($_SESSION['edq-user']['plugins'] as $key=>$value)
			       $plugins[$key] = $value;
			       
	       /* valeurs issues de la requête
		       url = ?q--plugins[]=dataTables,jqGrid
	       */
	       if(isset($_REQUEST['q--plugins'])
		  && is_array($_REQUEST['q--plugins'])
	       ){
		       foreach($_REQUEST['q--plugins'] as $key)
			       if(strpos($key, ',') !== FALSE){
				       foreach(explode(',',$key) as $subkey)
					       $plugins[trim($subkey)] = true;
			       }
			       else
				       $plugins[trim($key)] = true;
	       }
	       /* debug */
	       //$plugins['dataTables'] = false; //debug
	       //$plugins['jqGrid'] = false; //debug
	       
	       /* sauve en session */
	       if(isset($_SESSION)
	       && isset($_SESSION['edq-user']))
		       $_SESSION['edq-user']['plugins'] = $plugins;
	       
	       self::$plugins = $plugins;
	       return $plugins;
	}
	
	/* teste la nécessité d'un plugin parmi ceux chargés dans index.php */
	public static function need($plugin){
		if(!is_array(self::$plugins))
			self::init_plugins();
		if(!isset(self::$plugins[$plugin])
		|| !self::$plugins[$plugin]){
			die( '<script>
			if(confirm("Le plugin ' . $plugin . ' est manquant !\r\nRecharger ?")){
			    var search = document.location.search;
			    var href = document.location.href.replace(search, "");
			    if(!search) search = "?";
			    search += "&q--plugins[]=' . $plugin . '";
			    href += search;
			    //alert(href);
			   document.location.href = href;
			}
			</script>' );
			
		}
		self::$plugins[$plugin] = true;
	}
}
?>