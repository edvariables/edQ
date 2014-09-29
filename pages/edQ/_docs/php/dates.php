<?php
?>
<h2><?=$node['nm']?> en php</h2>

<ul class="edq-doc"><a href="http://php.net/manual/fr/function.date.php" target="_blank">date</a>
	<li><h3>string date ( string $format [, int $timestamp = time() ] )</h3>
		<ul>
			<li>Retourne une date sous forme d'une chaîne</li>
			<li>Exemples : <pre>
	return date('D d F Y H:i:s');
	return date('now');
	
	$today = date("F j, Y, g:i a");                   // March 10, 2001, 5:16 pm
	$today = date("m.d.y");                           // 03.10.01
	$today = date("j, n, Y");                         // 10, 3, 2001
	$today = date("Ymd");                             // 20010310
	$today = date('h-i-s, j-m-y, it is w Day');       // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
	$today = date('\i\t \i\s \t\h\e jS \d\a\y.');     // It is the 10th day (10ème jour du mois).
	$today = date("D M j G:i:s T Y");                 // Sat Mar 10 17:16:18 MST 2001
	$today = date('H:m:s \m \e\s\t\ \l\e\ \m\o\i\s'); // 17:03:18 m est le mois
	$today = date("H:i:s");                           // 17:16:18
	$today = date("Y-m-d H:i:s");                     // 2001-03-10 17:16:18 (le format DATETIME de MySQL)
</pre></li>
		</ul></li>
</ul>
<ul class="edq-doc"><a href="http://php.net/manual/fr/function.mktime.php" target="_blank">mktime</a>
	<li><h3>int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )</h3>
		<ul>
			<li>Retourne le timestamp UNIX d'une date</li>
			<li>Exemples : <pre>
	$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
	$lastmonth = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
	$nextyear  = mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+1);</pre></li>
		</ul></li>
</ul>
<ul class="edq-doc"><a href="http://fr2.php.net/manual/fr/class.datetime.php" target="_blank">DateTime</a>
	<li><h3>int mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )</h3>
		<ul>
			<li>Retourne le timestamp UNIX d'une date</li>
			<li>Exemples : <pre>
	$date  = new DateTime("2012-07-08 11:14:15.638276");
	
	$now   = new DateTime;
	$clone = clone $now;    
	$clone->modify( '-1 day' );</pre></li>
		</ul></li>
</ul>