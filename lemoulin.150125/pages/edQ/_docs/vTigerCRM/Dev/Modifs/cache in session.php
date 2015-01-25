<h2>NE FONCTIONNE PAS</h2>
<h3>\includes\runtime\Cache.php</h3>
Conserve le cache dans la session.
Attention, nécessite le reset : voir plus bas.
<pre><code>
	/* ED140907 cache in session */
    public static $sessionCacheEnable = FALSE; //NE FONCTIONNE PAS
	
 public static function getInstance(){
	if(self::$selfInstance){
	    return self::$selfInstance;
	/* ED140907 cache in session */
	} else if(self::$sessionCacheEnable && isset($_SESSION['rsn_Vtiger_Cache'])){
	    return $_SESSION['rsn_Vtiger_Cache'];
	} else {
	    /*var_dump('self::$selfInstance = new self()');
	    var_dump(self::$sessionCacheEnable);*/
	    self::$selfInstance = new self();
	    if($_SESSION && self::$sessionCacheEnable)/* ED140907 cache in session */
		$_SESSION['rsn_Vtiger_Cache'] = self::$selfInstance;
	    return self::$selfInstance;
	}
}

<b>
	/* ED140907 cache in session
     * Vtiger_Cache::clearSessionCache();
    */
    public static function clearSessionCache(){
	if($_SESSION
	&& isset($_SESSION['rsn_Vtiger_Cache'])){
	    unset( $_SESSION['rsn_Vtiger_Cache'] );
	}
    }</b>
</code></pre>


<h3>\modules\Users\views\Login.php</h3>
Au login, supprime le cache de la session.
<pre><code>
/* ED140907 cache is session */
//echo 'Vtiger_Cache::clearSessionCache';
Vtiger_Cache::clearSessionCache();
</code></pre>



