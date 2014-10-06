<?php
$dataUrl = page::file_url(':data', $node);
$uid = uniqid('datatable');
$args = array( 'uid' => $uid );
page::call(':html', $args, $node);
?><script>
$(document).ready(function() {
    $('#<?=$uid?>').dataTable( {
            "language": {
                "url": "res/jquery/dataTables/lang/dataTables.french.json"
            },
        "columns": [
            { "data": "name" },
            { "data": "position" },
            { "data": "office" },
            { "data": "extn" },
            { "data": "start_date" },
            { "data": "salary" }
        ]
	 , "ajax" : "<?=$dataUrl?>"
    } );
} );
</script>