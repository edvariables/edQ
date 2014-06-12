// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// ED
// ----------------------------------------------------------------------------
// dataSource tags
// ----------------------------------------------------------------------------
// 
// ----------------------------------------------------------------------------
mySettings_dataSource = mySettings = (function(){
	var helpers = {
		regex: function(name){ return new RegExp("(^|\\n\\s*)(\\$" + name + "\\s*=\\s*)(.*?)((\\s*;?)\\s*(\\r?\\n|$))"); },
			
		replace: function(markitup, name, defaultValue){
			var value = helpers.get(markitup, name)
			, empty = !value;
			if(empty)
				value = '"' + (defaultValue ? defaultValue : '') + '"';
				
			if(!(value = prompt("Script pour " + name, value)))
				return false;
			if(empty){
				markitup.textarea.value = markitup.textarea.value
					+ '\r\n$' + name + ' = ' + value + ';';
			}
			else
				markitup.textarea.value = markitup.textarea.value.replace(helpers.regex(name)
					, "$1$2" + value + "$5$1//$2$3$4");
		},
		get: function(markitup, name){
			var match = helpers.regex(name).exec(markitup.textarea.value);
			if(match != null){
				return match[3];
			}
			else
				return '';
			
		}
	};
	return {
		onTab:		{keepDefault:false, openWith:'	 '},
		markupSet: [
			{name:'Type', className:'edit', replaceWith:function(markitup) {
				return helpers.replace(markitup, "DBTYPE", "mysql" );
			} },
			{name:'Serveur', className:'edit', replaceWith:function(markitup) {
				return helpers.replace(markitup, "DBSERVER", "localhost" );
			} },
			{name:'Base', className:'edit', replaceWith:function(markitup) {
				return helpers.replace(markitup, "DBNAME", "your_db_name" );
			} },
			{name:'Utilisateur', className:'edit', replaceWith:function(markitup) {
				return helpers.replace(markitup, "DBSUSER", "root" );
			} },
			{name:'Mot de passe', className:'edit', replaceWith:function(markitup) {
				return helpers.replace(markitup, "DBPASSWORD", "" );
			} },
			{name:'Port', className:'edit', replaceWith:function(markitup) {
				return helpers.replace(markitup, "DBPORT", "" );
			} },
			{name:'Commentaires', className:'comment', replaceWith:"/* */" },
			{separator:'---------------' },
			{name:'Nettoyer', className:'clean', replaceWith:function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } },
			{name:'Tester', className:'preview', call:'preview' }
		]
	};
})();