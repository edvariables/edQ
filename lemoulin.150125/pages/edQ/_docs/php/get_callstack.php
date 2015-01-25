<?php
function get_callstack($skip = 0, $max = INF) {
  $dt = debug_backtrace();
  $cs = '';
  foreach ($dt as $t)
		if($skip-- > 0)
			continue;
		elseif ($max-- < 1)
	  		break;
  		else {
	  		$cs .= $t['file'] . ' line ' . $t['line'] . ' function ' . $t['function'] . "()\n";
  		}

  return $cs;
}
echo('<pre>');
print_r( get_callstack() );
echo('</pre>');

?>