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
            }
	 , "ajax" : "<?=$dataUrl?>"
        } );
} );
</script>