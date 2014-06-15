<?php
function get_callstack() {
  $dt = debug_backtrace();
  $cs = '';
  foreach ($dt as $t) {
    $cs .= $t['file'] . ' line ' . $t['line'] . ' function ' . $t['function'] . "()\n";
  }

  return $cs;
}
echo('<pre>');
print_r( get_callstack() );
echo('</pre>');

?>