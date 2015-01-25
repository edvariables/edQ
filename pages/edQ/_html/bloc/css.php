/*TODO transposer dans le .css global*/
.edq-bloc {
	//border: 2px outset gray;
	margin: 4px 4px;
	padding: 12px;
}
.edq-bloc > .header-footer {
	padding: 4px 6px;
}
.edq-bloc > .header-footer a {
	color: #1c5474;
	padding-right: 2em;
}
.edq-bloc > .header-footer .edq-toolbar {
	display: inline-block;
	float: right;
}
.edq-bloc > .header-footer .edq-toolbar button:selected {
	border: none;
}
.edq-bloc.collapsed {
	background: none;
	border: none;
	margin: 4px 4px;
	padding: 0;
}
.edq-bloc.collapsed > .ui-widget-content {
	display: none;
}