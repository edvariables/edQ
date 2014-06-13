// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// ED
// ----------------------------------------------------------------------------
// sql tags
// http://fr.wikipedia.org/wiki/sql
// ----------------------------------------------------------------------------
// Basic set. Feel free to add more tags
// ----------------------------------------------------------------------------
mySettings_sql = mySettings = {
	onShiftEnter:	{keepDefault:false, replaceWith:'<br />\n'},
	onCtrlEnter:	{keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
	onTab:			{keepDefault:false, openWith:'	 '},
	markupSet: [
		// TODO images
		{name:'SELECT', key:'1', openWith:'SELECT ', closeWith:'\n', placeHolder:'a.id, a.name' },
		{name:'FROM', key:'2', openWith:'FROM (!( table="[![table]!]")!)', closeWith:'\n', placeHolder:'[![table d\'origine]!]' }, //todo COMPRENDRE LES INPUT BOX
		{name:'JOIN', key:'3', openWith:'JOIN (!( table="[![table]!]")!)', closeWith:'\n\tON a.id = [![table]!].id\n', placeHolder:'[![table]!]' },
		{name:'WHERE', key:'4', openWith:'WHERE (!( table="[![table]!]")!)', closeWith:'\n', placeHolder:'[![table]!]' },
		{name:'ORDER BY', key:'5', openWith:'ORDER BY ', closeWith:'\n', placeHolder:'name, id' },
		{name:'LIMIT', key:'6', openWith:'LIMIT 0, (!( nbre de lignes="[![rows]!]")!)', closeWith:'\n', placeHolder:'[![nbre de lignes]!]' },
		{name:'UPDATE', key:'7', openWith:'UPDATE (!( table="[![table]!]")!)', closeWith:'\n\tSET a.name = :NAME\nWHERE a.id = :ID' },
		{name:'INSERT', key:'8', openWith:'INSERT INTO (!( table="[![table]!]")!)', closeWith:' (id, name)\nVALUES(:ID, :NAME)' },
		{name:'DELETE', key:'9', openWith:'DELETE FROM (!( table="[![table]!]")!)', closeWith:'\nWHERE a.id = :ID' },
		{separator:'---------------' },
		{name:'Preview', className:'preview', call:'preview' }
	]
}