<?php
$uid = $arguments['uid'];
if(!$uid){
	?>/*Paramètre uid manquant.*/<?php
	return;
}
?><style>
#<?=$uid?> table {
	border: 1px solid gray;
	border-radius : 4px;
}
#<?=$uid?> table caption {
	white-space: pre;
	margin-bottom: 8px;
	padding-bottom: 8px;
	line-height: 1.3em;
}
#<?=$uid?> table caption:first-line{
	font-size: larger;
	font-weight: bold;
	line-height: 3em;
}
#<?=$uid?> thead .add-participant {
	float: right;
}
#<?=$uid?> tfoot .add-question {
	float: left;
	padding: 6px 6px;
}
#<?=$uid?> th {
	min-width : 6em;
	border-bottom: 1px solid gray;
	border-right: 1px solid gray;
	border-radius : 2px;
	background-color: #cfcdef;
}
#<?=$uid?> th.submit {
	border: none;
	background: none;
	padding: 16px;
}
#<?=$uid?> input {
	width: 6em;
}
#<?=$uid?> input[type="submit"] {
	opacity: 0.7;
	background-color: #5bb75b;
	background-image: -moz-linear-gradient(center top , #62c462, #51a351);
	background-repeat: repeat-x;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	padding: 5px;
	width: 7em;
	float: right;
}
#<?=$uid?> thead button.submit, #<?=$uid?> thead a.submit {
	opacity: 0.7;
	width: 21px;
	height: 21px;
	float: left;
	margin-left: 1px;
	padding: 0;
	background-color: #5bb75b;
	background-repeat: repeat-x;
	background-image: -moz-linear-gradient(center top , #62c462, #51a351);
	border: 2px outset;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	border-radius: 3px;
}
#<?=$uid?>.has-changed input[type="submit"]
, #<?=$uid?>.has-changed thead a.submit
, #<?=$uid?>.has-changed thead button.submit {
	opacity: 1;
	font-size: larger;
}

#<?=$uid?> tbody th {
	background-color: #edebee;
	text-align: left;
}
#<?=$uid?> tbody tr > * {
	text-align: center;
	border-bottom: 1px solid gray;
	border-right: 1px solid gray;
	border-radius : 2px;
}

#<?=$uid?> tbody tr.archived > * {
	opacity: 0.7;
}

#<?=$uid?> tbody textarea {
	/*font-size: smaller;*/
	width: 20em;
	height: 2.5em;
}
#<?=$uid?> tbody pre {
	/*font-size: smaller;*/
	font-family: inherit;
	padding-left: 6px;
}
#<?=$uid?> caption textarea {
	width: 90%;
	min-width: 40em;
	height: 3.5em;
}
#<?=$uid?> caption input[name="title"] {
	width: 90%;
	min-width: 40em;
	
}
#<?=$uid?> .ui-icon {
	display: inline-block;
	vertical-align: middle;
	width: 16px;
}
#<?=$uid?> a {
	color: darkblue;
	font-weight: normal;
}
#<?=$uid?> tr > * {
	padding : 4px;
}
#<?=$uid?> .remove-question, #<?=$uid?> .remove-participant {
	float: right;
}
</style>