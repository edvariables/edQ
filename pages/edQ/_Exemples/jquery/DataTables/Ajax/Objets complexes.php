<?php
$dataUrl = substr(__FILE__, 0, strlen(__FILE__) - 4);
$root = preg_replace('/\\$/', '', $_SERVER['DOCUMENT_ROOT']);
$dataUrl = '/' . str_replace('\\', '/', substr($dataUrl, strlen($root))) . '/data.php';
?>
<?php include(substr(__FILE__, 0, strlen(__FILE__) - 4) . '/html.php');?>
<script>
$(document).ready(function() {
    $('#example').dataTable( {
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