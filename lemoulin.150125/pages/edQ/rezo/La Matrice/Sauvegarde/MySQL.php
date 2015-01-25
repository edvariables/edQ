<pre><?php

include(dirname(__FILE__) . '/../dataSource.php');

$cmd = "/Applications/MAMP/Library/bin/mysqldump --host $DBSERVER -P $DBPORT -u $DBNAME -p$DBPASSWORD $DBNAME"
	." > ~/Documents/$DBNAME.sql";
?><textarea cols="90" rows="5"><?= $cmd ?></textarea>
</pre>