
/**
 * ### edQ plugin
 *
 * Initialisation pour edQ
 */

	$.jstree.plugins.edQ = function (options, parent) {
		this.bind = function () {
			parent.bind.call(this);
			// étend les paramètres par défaut des plugins
			var defaults = this.settings.defaults
			for(var plugin in defaults)
				if(typeof defaults[plugin] === 'object')
					for(var option in defaults[plugin])
						this.settings[plugin][option] = defaults[plugin][option];
				else
					this.settings[plugin] = defaults[plugin];
		};
	};


/**
 * ### favpanel (favorites panel) plugin
 *
 * Permet de positionner des noeuds sur un div
 
 * cf plugin dnd
 */
	$.jstree.defaults.favpanel = {
		/**
		 * a string indicating the class favorites panel should have
		 * @name $.jstree.defaults.favpanel.classname
		 * @plugin favpanel
		 */
		classname : 'jstree-favpanel'
	};
	$.jstree.plugins.favpanel = function (options, parent) {
		var self = this;
		this.bind = function () {
			parent.bind.call(this);
			
			this.load();
		};
		
		/* add_node
		*/
		this.add_node = function($panel, node){
			$panel.prepend($('<div></div>')
				.addClass('jstree-node')
				.html($('<a></a>')
					.html(node.text)
					.prepend($('<i></i>')
						.addClass('jstree-icon')
						.addClass(node.icon)
					)
					.click(function(){
						var $dom = $(this.parentNode);
						if ($dom.hasClass('noclick')) {
							$dom.removeClass('noclick');
							return;
						}
						$.get('tree/db.php?operation=get_view'
							+ '&id=' + $dom.attr('id')
							+ '&vw=viewers'
							+ (self.settings.design ? '&design=true' : '')
							, function (d) {
								$('#data .default').html(d.content).show();
							});
					})
				)
				.css({
					"cursor" : "pointer"
					, "display" : "inline-block"
					, "position" : "absolute"
					, "background-image" : "none"
					, "left" : node.x + "px"
					, "top" : node.y + "px"
				})
				.attr({
					'id': node.id
					, 'role': 'treeitem'
				})
				.draggable({
					distance: 4,
					grid: [ 4, 4 ],
					start: function() {
						$(this).addClass('noclick');
					},
					stop: function() {
						var page = { x : $droppable.scrollLeft(), y : $droppable.scrollTop() };
						var $this = $(this);
						var pos = $this.position();
						if((page.y + pos.top + $this.height()) <= 14)
							$this.css("color", "red");
						else
							$this.css("color", "black");
						self.save($droppable);
					}
				})
			);
		};
		
		/* load
		*/
		this.load = function(){
			var $panels = $(this.settings.favpanel.selector);
			if($panels.length === 0) return;
			
			$.post('view.php?id=/_System/Utilisateur/Preferences/get', { 
					domain : 'jstree-favpanel-nodes'
				})
				.done(function(data){
					if(!data) return;
					if(typeof data === 'string') data = eval('(' + data + ')');
					// pour chaque panneau sauvegarde
					for(var panel in data){
						if(!isNaN(panel)) panel = parseInt(panel);
						// pour le dom correspondant au param
						$panels.filter( data[panel].param ).each(function(){
							$droppable = $(this);
							if(typeof data[panel].value === "string")
								data[panel].value = eval('(' + data[panel].value + ')');
							// pour chaque noeud enregistré
							for(var nodeId in data[panel].value){
								node = data[panel].value[nodeId];
								node.id = nodeId;
								self.add_node($droppable, node);
							}
						});
					}
				})
				.fail(function(e, data){
					alert('Erreur de sauvegarde');
			});
		};
		
		/* save nodes
		*/
		this.save = function($dom){
			var data = {};
			var page = { x : $dom.scrollLeft(), y : $dom.scrollTop() };
			$dom.children('.jstree-node').each(function(){
				var $this = $(this);
				var pos = $this.position();
				if((page.y + pos.top + $this.height()) <= 14)
					return;
				data[this.id] = {
					h: Math.round($this.height())
					, w: Math.round($this.width())
					, x: Math.round(page.x + pos.left)
					, y: Math.round(page.y + pos.top)
					, text: $this.text()
					, icon: $this.find('> a > i').attr('class').replace(/(\s*\jstree[^\s]+(\s|$))/g, '')
				};
			});
			
			$.post('view.php?id=/_System/Utilisateur/Preferences/set', { 
					domain : 'jstree-favpanel-nodes',
					param : $dom[0].tagName + '#' + $dom.attr('id'),
					value : data
				})
				.done(function(data){
					if(data){
						$('<div></div>').appendTo('body')
							.html(data)
							.dialog({
								width: 'auto'
								, height: 'auto'
							});
					}
				})
				.fail(function(e, data){
					alert('Erreur de sauvegarde');
			});
		}
	};

	$(function() {
		
		// bind only once for all instances
		$(document)
			.bind('dnd_stop.vakata', function (e, data) {
				if(!data.data.jstree) { return; }
				var i, j, nodes = [];
				 /* ED140727 deplacement ailleurs */
				if(data.data.origin.settings.favpanel){
					var $droppable = $(data.event.target),
					ok = $droppable.hasClass(data.data.origin.settings.favpanel.classname);
					if(ok) {
						var page = { x : $droppable.scrollLeft(), y : $droppable.scrollTop() };
						var $a = data.data.obj.children('a:first'),
						x = data.event.offsetX==undefined ? data.event.pageX - 24 : data.event.offsetX,
						y = data.event.offsetY==undefined ? data.event.pageY - 16 : data.event.offsetY
						;
						data.data.origin.add_node($droppable, {
							'id': data.data.nodes[0]
							, "x" : Math.round((x) / 4) * 4 + page.x
							, "y" : Math.round((y) / 4) * 4 + page.y
							, "text" :  $a.text()
							, "icon" : $a.children('i').attr('class')
						});
						//save
						data.data.origin.save($droppable);
					}
				}
			})
		;
	});

	// include the plugin by default
	// $.jstree.defaults.plugins.push("favpanel");

