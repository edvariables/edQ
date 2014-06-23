<?php /* àé */

$uid = uniqid('code');
$file = helpers::get_pagesPath() . '/edQ/TerraFact/Contacts/Liste.php';
?>
<textarea id="<?=$uid?>"><?=htmlentities(utf8_decode(file_get_contents($file)))?></textarea>
<script>
	 var myCodeMirror = CodeMirror.fromTextArea(document.getElementById("<?=$uid?>"), {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true
});
</script>