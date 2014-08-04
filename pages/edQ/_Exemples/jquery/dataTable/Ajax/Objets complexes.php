<?php
$dataUrl = page::file_url(':data', __FILE__);
$uid = uniqid('datatable');
$args = array();
$args[ 'uid' ] = $uid;
page::call(':html', $args, __FILE__);

?><script>
$(document).ready(function() {
    $('#<?=$uid?>').dataTable( {
        "language": {
            "url": "jquery/dataTables/lang/dataTables.french.json"
        },
        "columns": [
            { "data": "name" },
            { "data": "hr.position" },
            { "data": "contact.0" },
            { "data": "contact.1" },
            { "data": "hr.start_date" },
            { "data": "hr.salary" }
        ],
        "deferRender": true,
	"ajax" : {
	 	 "url": "<?=$dataUrl?>",
	         "dataSrc": ""//la source fournit le tableau de données directement, sans sous-objet "data"
	 }
    } );
} );
</script>