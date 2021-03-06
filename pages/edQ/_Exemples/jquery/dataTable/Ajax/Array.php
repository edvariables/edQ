<?php
helpers::need_plugin('dataTables');
$dataUrl = page::file_url(':data', __FILE__);
$uid = uniqid('datatable');
$args = array( 'uid' => $uid );
page::call(':html', $args, __FILE__);
?><script>
$(document).ready(function() {
    $('#<?=$uid?>').dataTable( {
            "language": {
                "url": "res/jquery/dataTables/lang/dataTables.french.json"
            }
	 , "ajax" : "<?=$dataUrl?>"
        } );
} );
</script>