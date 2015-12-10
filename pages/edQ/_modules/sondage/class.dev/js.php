<?php
$uid = $arguments['uid'];
if(!$uid){
	?>/*Paramètre uid manquant.*/<?php
	return;
}
$isAdmin = $arguments['isAdmin'];
$reponseValues = $arguments['reponseValues'];
if(!is_array($reponseValues))
	$reponseValues = array();
?>
<script>
$(document.body).ready(function(){
	var options = {
		isAdmin : <?= $isAdmin ? 'true' : 'false'?>,
		reponseValues : <?= json_encode($reponseValues)?>
	};
	var methods = {
		//Retourne l'objet jQuery de la form
		getForm : function(){
			return $('#<?=$uid?>');
		},
		//Retourne l'objet jQuery de la table
		getTable : function(){
			return $('#<?=$uid?> > table:first');
		},
		//Calcule l'id d'une nouvelle question
		getNewQuestionId : function(){
			return ("000000" + (Math.random()*Math.pow(36,6) << 0).toString(36)).slice(-6);
		},
		//Calcule l'id d'un nouveau participant
		getNewParticipantId : function(){
			return ("000000" + (Math.random()*Math.pow(36,6) << 0).toString(36)).slice(-6);
		},
		//Ajoute une question
		addQuestion : function(e){
			var $form = methods.getForm()
			, $table = methods.getTable()
			, $trHead = $table.find('thead tr:first')
			, $thParticipants = $trHead.children('.participant')
			, $tBody = $table.children('tbody:first')
			, nQuestion = methods.getNewQuestionId()
			, $trNew;
			$form.find('input[name="question_deleted_' + nQuestion + '"]').remove();
			$trNew = $('<tr class="question"/>')
				.appendTo($tBody)
				.attr('question', nQuestion)
				.append($('<th class="texte"/>')
						.append($('<textarea name="question_' + nQuestion + '" class="has-changed" placeholder="votre question"/>'))
				)
			;
			$thParticipants.each(function(){
				var nParticipant = this.getAttribute("participant");
				$trNew
					.append($('<td participant="' + nParticipant + '"/>').html(methods.replaceWithReponseInput(false, 'reponse_q' + nQuestion + '_p' + nParticipant)))
				;
			}),
			$trNew
				.append($('<th class="score">0</th>')
				   .append('<a class="remove-question" href="#" title="supprimer cette question"><span class="ui-icon ui-icon-trash">&nbsp;</span></a>')
				)
				.find(':input:first').select().focus().end()
			;
		},
		//Ajoute un participant
		addParticipant : function(e){
			var $form = methods.getForm()
			, $table = methods.getTable()
			, $thTotal = $table.find('thead .score:first')
			, nParticipant = methods.getNewParticipantId()
			, $trBody = $table.find('> tbody > tr')
			, $thNew;
			$form.find('input[name="participant_deleted_' + nParticipant + '"]').remove();
			$thNew = $('<th class="participant"/>')
				.attr('participant', nParticipant)
				.insertBefore($thTotal)
				.append($('<input name="participant_' + nParticipant + '" class="has-changed" placeholder="votre nom" size="12"/>'))
				.append('<a class="remove-participant" href="#" title="supprimer ce participant"><span class="ui-icon ui-icon-trash">&nbsp;</span></a>')
			;
			$trBody.each(function(){
				if(!/\bquestion\b/.test(this.className)) return;
				var nQuestion = this.getAttribute("question");
				var $td = $('<td participant="' + nParticipant + '"/>').insertBefore($(this).children('.score:first'))
					.append(methods.replaceWithReponseInput(false, 'reponse_q' + nQuestion + '_p' + nParticipant)
						   .addClass('has-changed')
					)
				;
			});
			$thNew.find(':input:first').select().focus();
		},
		//Construction de l'éditeur de réponse
		replaceWithReponseInput : function($dom, name){
			if($dom && !$dom.jquery)
				$dom = $($dom);
			var value = $dom ? ($dom.is(':input') ? $dom.val() : $dom.text()) : '';
			if(!name && $dom)
				name = $dom.attr('name');
			var $select = $('<select name="' + name + '"/>')
			;
			for(var nReponseValue in options.reponseValues){
				var reponseValue = options.reponseValues[nReponseValue];
				$select.append(
					'<option value="' + reponseValue["id"] + '"'
						+ (reponseValue["id"] == value && !(reponseValue["id"] === '' && value !== '') ? ' selected="selected"' : '')
					+ '>'
					+ (reponseValue["text"] === '' ? '&nbsp;' : (reponseValue["text"]))
					+ '</option>'
				);
			}
			$select.append(
				'<option value=""'
					+ (value === "" ? ' selected="selected"' : '')
					+ '>&nbsp;</option>'
			);
			if($dom)
				$dom.replaceWith($select);
			return $select;
		},
		//remplace les inputs de réponse par des select
		initReponsesInputs : function(e){
			var $table = methods.getTable()
			, $tInputs = $table.find('> tbody input[name]');
			$tInputs.each(function(){
				if(/^reponse_/.test(this.getAttribute('name')))
					methods.replaceWithReponseInput(this);
			});
		},
		//suppression d'une ligne si le texte est vide
		onQuestionChanged : function(e){
			if(this.value.trim() === ''){
				var $trash = $(this).parents('tr:first').find('.remove-question:first');
				if($trash.length)
					methods.removeQuestion.call($trash);
			}
			else
				this.value = this.value.trim();
		},
		//suppression d'une ligne
		removeQuestion : function(e){
			var $tr = $(this).parents('tr:first')
			, isArchived = $tr.hasClass('archived')
			, $form = $(this).parents('form:first')
			, nQuestion = $tr.attr('question')
			, text = $tr.find('th textarea:first').val()
			;
			$("<div><div>Voulez-vous " + (isArchived ? "désarchiver" : "archiver")
			  	+ " ou supprimer cette question ?<div>"
				+ "<div><code>" + text.replace('<', '&lt;').replace('<', '&gt;') + "<code></div></div>").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				position: 'center',
				title: "Suppression d'une question",
				buttons :[
					{
					  text: (isArchived ? "Désarchiver" : "Archiver"),
					  icons: {
						  primary: isArchived ? "ui-icon-plusthick" : "ui-icon-minusthick"
					  },
					  click: function() {
							if(isArchived){
								$form.find('input[name="question_archived_' + nQuestion + '"]').remove();
							  	$tr.removeClass('archived');
								$tr.find(':input').show().parent().children('pre').remove();
						  	}
							else {
								$form.prepend('<input type="hidden" name="question_archived_' + nQuestion + '" value="1"/>');
								$tr.find(':input').hide().each(function(){
									$('<pre></pre>').html(this.value).appendTo(this.parentNode);
								});
								$tr.addClass('archived');
							}
							methods.needSave();
							$( this ).dialog( "close" );
					  }
					},
					{
					  text: "Supprimer",
					  icons: {
						primary: "ui-icon-close"
					  },
					  click: function() {
						$form.find('input[name="question_archived_' + nQuestion + '"]').remove();
						$form.prepend('<input type="hidden" name="question_deleted_' + nQuestion + '" value="1"/>');
						$tr.remove();
						methods.needSave();
						$( this ).dialog( "close" );
					  }
					},
					{
					  text: "annuler",
					  icons: {
						primary: "ui-icon-cancel"
					  },
					  click: function() {
						$( this ).dialog( "close" );
					  }
					}
				  ]
			});
		},
		//suppression d'une colonne si le libellé est vide
		onParticipantChanged : function(e){
			if(this.value.trim() === ''){
				var $trash = $(this).parents('th:first').find('.remove-participant:first');
				if($trash.length)
					methods.removeParticipant.call($trash);
			}
			else
				this.value = this.value.trim();
		},
		//suppression d'une colonne
		removeParticipant : function(e){
			var $form = methods.getForm()
			, $table = methods.getTable()
			, $trHead = $table.find('thead tr:first')
			, $thParticipant = $(this).parents('.participant')
			, nParticipant = $thParticipant.attr('participant')
			, text = $thParticipant.find(':input').val()
			;$("<div><div>Êtes vous sûr de vouloir supprimer ce participant ?<div>"
				+ "<div><code>" + text.replace('<', '&lt;').replace('<', '&gt;') + "<code></div></div>").dialog({
				modal: true,
				width: 'auto',
				height: 'auto',
				position: 'center',
				title: "Suppression d'un participant",
				buttons :[
					{
					  text: "Supprimer",
					  icons: {
						primary: "ui-icon-close"
					  },
					  click: function() {
						$form.prepend('<input type="hidden" name="participant_deleted_' + nParticipant + '" value="1"/>');
						$table.find('[participant=' + nParticipant + ']').remove();
						methods.needSave();
						  
						$( this ).dialog( "close" );
					  }
					},
					{
					  text: "annuler",
					  icons: {
						primary: "ui-icon-cancel"
					  },
					  click: function() {
						$( this ).dialog( "close" );
					  }
					}
				  ]
			});
		},
		//double-clic sur un nom de participant
		onParticipantDoubleClicked : function(e){
			var $th = this.tagName == 'TH' ? $(this) : $(this).parents('th:first')
			, id = $th.attr('participant')
			, $input = $th.find(':input:visible');
			if($input.length) return;
			if(!confirm("Confirmez-vous être '" + $th.text() + "' ?"))
				return;
			$input = $('<input name="participant_' + id + '" value="' + id + '"/>')
				.addClass('has-changed')
				.val($th.text());
			$th
				.html($input)
				.append('<a class="remove-participant" href="#" title="supprimer ce participant"><span class="ui-icon ui-icon-trash">&nbsp;</span></a>');
			alert("Veuillez enregistrer le formulaire pour avoir définitivement accès aux notes du participant.");
		},
		//flag les inputs ayants changés
		onInputChanged : function(e){
			$(this).addClass('has-changed');
			methods.needSave();
		},
		needSave : function(){
			methods.getForm().addClass('has-changed');
		},
		//n'envoie que les données modifiées
		onSubmit : function(e){
			var $table = methods.getTable()
			, $form = methods.getForm()
			, $inputUnchanged = $form.find(':input[name]:visible:not(.has-changed)')
			;
			if(options.isAdmin){
				if($inputUnchanged.length){
					$inputUnchanged
						.fadeOut( 200 )
						.attr('name', '');
					methods.getForm().find(':input[name="full-data"]').attr('name', '');
				}
			}
			else
				$table.fadeOut( 200 );
			if(this.tagName !== 'INPUT')
				$form.submit();
		}
	};
	
	methods.initReponsesInputs();
	methods.getForm()
		.on( "click", ".add-question", methods.addQuestion)
		.on( "click", ".add-participant", methods.addParticipant)
		.on( "click", ".remove-question", methods.removeQuestion)
		.on( "change", "tr.question > th.texte > textarea", methods.onQuestionChanged)
		.on( "click", ".remove-participant", methods.removeParticipant)
		.on( "change", "thead th.participant input", methods.onParticipantChanged)
		.on( "dblclick", "thead th.participant", methods.onParticipantDoubleClicked)
		.on( "change", ":input:visible", methods.onInputChanged)
		.on( "click", 'input[type="submit"], a.submit, button.submit', methods.onSubmit)
	
	;
});
</script>