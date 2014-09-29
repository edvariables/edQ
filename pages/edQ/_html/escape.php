<fieldset><legend>entrez un texte ou du code</legend>
	<textarea style="width: 100%; " rows="9"></textarea>
	<button onclick="var $input = $(this).prevAll('textarea:first');
				  $input.after($input.clone().val(htmlEntities($input.val())));">
		puis cliquez pour escape()</button>
	<button onclick="var $input = $(this).prevAll('textarea:first');
					 $('<pre></pre>').html($input.val())
					 	.dialog({'width':'auto', 'height':'auto'});">
		afficher</button>
</fieldset>
<script>
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
		/*.replace(/"/g, '&quot;')*/
	;
}
</script>