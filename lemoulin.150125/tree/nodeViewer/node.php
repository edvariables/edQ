<?php /* Gestion des propriétés d'un noeud
UTF8 é
*/
if(isset($_POST['op'])
&& $_POST['op'] == 'submit') {

	require_once(dirname(__FILE__) . '/../db.php');	 //TODO load tree only
	global $tree;
	
	$id = isset($_REQUEST['id']) && $_REQUEST['id'] !== '#' ? (int)$_REQUEST['id'] : 0;
	$params = array();
	if(isset($_REQUEST['name']))
		$params["nm"] = $_REQUEST['name'];
	if(isset($_REQUEST['icon']))
		$params["icon"] = $_REQUEST['icon'];
	if(isset($_REQUEST['color']))
		$params["color"] = $_REQUEST['color'];
	if(isset($_REQUEST['type']))
		$params["typ"] = $_REQUEST['type'];
	if(isset($_REQUEST['ulvl']))
		$params["ulvl"] = $_REQUEST['ulvl'];
	if(isset($_REQUEST['ext']))
		$params["ext"] = $_REQUEST['ext'];
	if(isset($_REQUEST['design']))
		$params["design"] = $_REQUEST['design'] == 'on' || (bool)$_REQUEST['design'] ? 1 : 0;
	else
		$params["design"] = 0;
	if(isset($_REQUEST['params']))
		$params["params"] = $_REQUEST['params'];
	if(isset($_REQUEST['ulvl']))
		$params["ulvl"] = $_REQUEST['ulvl'];
	if(isset($_REQUEST['user']))
		$params["user"] = $_REQUEST['user'];
	$rslt = $tree->rn($id, $params, true);
	die("1");
}

require_once(dirname(__FILE__) . '/../helpers.php');
	
class nodeViewer_node extends nodeViewer {
	public $name = 'node';
	public $text = 'Noeud';
	
	public function html($node, $options = false){
		if(!isset($node["typ"])){
			global $tree;
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => true));
		}
		
		// instance de node
		$node = Node::fromClass($this->domain, $node);
		
		$uid = uniqid('form-');
		
		// Type
		$types = Node::get_types();
		$select_type = '<select name="type" value="' . ifNull($node->type, '') . '">';
		foreach($types as $type => $typeObj)
			$select_type .= '<option value="' . $type . '"'
				. ($node->type == $type ? ' selected="selected"' : '')
				. ' icon="' . $typeObj['icon'] . '"'
			 	. '>' 
					. htmlspecialchars($type) . '</option>';
		$select_type .= '</select>';
		
		// icon 
		$icons = Node::get_icons();
		$select_icon = '<select name="icon" value="' . ifNull($node->icon, '') . '">';
		foreach($icons as $icon)
			$select_icon .= '<option value="' . $icon . '"'
					. ($node->icon == $icon ? ' selected="selected"' : '')
				. ' icon="' . $icon . '"'
			 	. '>'
					. preg_replace('/^(jstree|file\sfile)-/', '', $icon)
				. '</option>';
		$select_icon .= '</select>';
		
		// ulvl
		$ulvls = Node::get_ulvls();
		$select_ulvl = '<select name="ulvl" value="' . ifNull($node->ulvl, '') . '">';
		foreach($ulvls as $ulvl => $text)
			$select_ulvl .= '<option value="' . $ulvl . '"'
					. ($node->ulvl == $ulvl ? ' selected="selected"' : '')
			 	. '>'
					. htmlspecialchars ($text)
				. '</option>';
		$select_ulvl .= '</select>';
		
		$select_icon_script = '$("#' . $uid . ' select > option[icon]").each(function(){
				$(this).prepend($(\'<i class="jstree-icon \' + this.getAttribute("icon") + \'"></i>\'));
			})
		';
		
		// color
		$color = ifNull($node->color, '');
		$color_input = '<div id="' . $uid . '--colorPicker" class="colorpicker-holder"><div style="background-color: ' . $color . '">
<input type="hidden" id="' . $uid . '-color" name="color" value="' . $color . '" size="12"/></div></div>';
		$color_script = '$("#' . $uid . '--colorPicker").ColorPicker({
			color: "' . $color . '"
			, onHide: function (colpkr) {
				var colorPicker = document.getElementById("' . $uid . '-color");
				if(typeof(colorPicker.onchange)==="function") colorPicker.onchange();
				return true;
			}
			, onChange: function (hsb, hex, rgb) {
				$("#' . $uid . '--colorPicker > div").css("backgroundColor", "#" + hex);
				document.getElementById("' . $uid . '-color").value = "#" + hex;
			}
		});';
		
		// Form
		$html = '<form id="' . $uid . '" method="post" action="' . $this->get_url($node) . '">'
			. '<fieldset class="q-fields">'
			. '<input type="hidden" name="id" value="' . $node->id . '"/>'
			. '<input type="hidden" name="vw" value="' . __CLASS__ . '"/>'
			. '<input type="hidden" name="op" value="submit"/>'
			. '<div>'
			. '<div><label class="ui-state-default ui-corner-all">Nom</label>'
				. '<input size="40" name="name" value="' . $node->name . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Visiblité</label>'
				. '<label><input type="checkbox" name="design"' . ($node->design ? 'checked="checked"' : '') . '/>visible uniquement en design</label></div>'
			. '<div><label class="ui-state-default ui-corner-all">Icône</label>'
				. $select_icon . $color_input . '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Type</label>'
				. $select_type . '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Clé externe</label>'
				. '<input size="40" name="ext" value="' . ifNull($node->ext, '') . '"/></div>'
			. '<div><label class="ui-state-default ui-corner-all">Paramètres</label>'
				. '<textarea rows="3" name="params" style="width: 90%">' . ifNull($node->params, '') . '</textarea></div>'
			. '<div><label class="ui-state-default ui-corner-all">Sécurité</label>'
				. $select_ulvl . '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Propriétaire</label>'
				. '<input size="40" name="user" value="' . ifNull($node->user, '') . '"/></div>'
			//. '<div><label class="ui-state-default ui-corner-all">Couleur</label>'
			//	. $color_input . '</div>'
			. '</div></fieldset>'
					. '<fieldset>'
					. '<input type="submit" value="Enregistrer"/>'
					. '</fieldset>'
			. '</form>'
			. '<script>
				$(document).ready(function() {'
				. $color_script
				. $select_icon_script
				. '});
			</script>'
			. $this->formScript($uid, null, '
				var value = $("#' . $uid . '").find(\':input[name="name"]\').val();
				$("#tree").jstree("reload_node", ' . $node->id . ');
				//$("#tree").jstree("set_text", ' . $node->id . ', value, false);
			')
		;
		return array(
			"title" => $node->name
			, "content" => $html
		);
	}
}

?>