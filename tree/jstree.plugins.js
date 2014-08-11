
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
		classname : 'jstree-favpanel',
		
		/**
		 * an array defining the drag grid steps
		 * @name $.jstree.defaults.favpanel.grid
		 * @plugin favpanel
		 */
		grid: [ 8, 8 ],
		
		/**
		 * le nom du domain de sauvegarde des préférences utilisateur
		 * @name $.jstree.defaults.favpanel.userpref_domain
		 * @plugin favpanel
		 */
		userpref_domain: 'jstree-favpanel-nodes'
					
	};
	$.jstree.plugins.favpanel = function (options, parent) {
		
		/* tree instance */
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
						
						self.deselect_all();
						
						var $node = self.get_node($dom.attr('node_id'), true);
						if($node){
							if(self.select_node($node))
								return;
						}
						$.get('tree/db.php?operation=get_view'
							+ '&id=' + $dom.attr('node_id')
							+ '&vw=viewers'
							+ (self.settings.design ? '&design=true' : '')
							, function (d) {
								$('#data .default').html(d.content).show();
							}
						);
					})
				)
				.css({
					"position" : "absolute" //!important
					, "left" : node.x + "px"
					, "top" : node.y + "px"
				})
				.attr({
					'node_id': node.id
					, 'role': 'treeitem'
				})
				.draggable({
					revert: false,
					distance: 4,
					grid: self.settings.favpanel.grid,
					start: function() {
						$(this).addClass('noclick');
					},
					stop: function() {
						var $this = $(this);
						var $div = $this.parent();
						var page = { x : $div.scrollLeft(), y : $div.scrollTop() };
						var pos = $this.position();
						if((page.y + pos.top + $this.height()) <= 14
						|| (page.x + pos.left) <= 4)
							$this.css("color", "red");
						else
							$this.css("color", "black");
						self.save($div);
					}
				})
			);
		};
		
		/* load
		*/
		this.load = function(){
			var $panels = $(this.settings.favpanel.selector);
			if($panels.length === 0) return;
			
			this.toolbar($panels);
			
			$.post('view.php?id=/_System/Utilisateur/Preferences/get', { 
					domain : this.settings.favpanel.userpref_domain
				})
				.done(function(data){
					if(!data) return;
					if(typeof data === 'string')
						try{
							data = eval('(' + data + ')');
						}
						catch(ex){
							alert("Erreur dans jstree.plugins.js load('/_System/Utilisateur/Preferences/get') : " + ex);
						}
					// pour chaque panneau sauvegarde
					for(var panel in data){
						if(!isNaN(panel)) panel = parseInt(panel);
						// pour le dom correspondant au param
						$panels.filter( data[panel].param ).each(function(){
							$droppable = $(this);
							if(!data[panel].value)
								data[panel].value = {};
							else if(typeof data[panel].value === "string")
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
		
		/* toolbar
		 * create and return
		*/
		this.toolbar = function($panels){
			if(!$panels && $panels)
				$panels = $(this.settings.favpanel.selector);
			if($panels.length === 0) return;
			var $toolbar = $panels.children('.tree-favpanel-toolbar');
			if($toolbar.length == 0){
				/* see edQ.css @ .tree-favpanel-toolbar */
				$toolbar = $('<div class="tree-favpanel-toolbar"></div>')
					.append($('<a class="tree-favpanel-toolbar-trash"></a>')
						.html('<span class="ui-icon ui-icon-trash" title="déplacer ici un élément"> </span>')
						.css({
							'top' : '2px'
						})
						.click(function(){
							if(!confirm('Le panneau va etre efface et la page rechargee.'))
								return;
							self.save($panels, 'reset', function(data){
								if(data) return;
								document.location.reload();
								return true;
							} );
							return false;
						})
						.droppable({
							accept: ".jstree-node",
							over: function( event, ui ){
								$(this).css({
									'border': '2px outset gray'
									, 'background-color': '#F0F0F0'
									, 'margin' : '0',
									'opacity': '1'
								});
								ui.draggable.css({
									'color': 'red'
								});
							},
							out: function( event, ui ){
								$(this).css({
									'border': 'none'
									, 'background-color': 'transparent'
									, 'margin' : '2px',
									'opacity': '0.5'
								})
								ui.draggable.css({
									'color': 'inherit'
								});
							},
							drop : function( event, ui ){
								ui.draggable.remove();
								$(this).droppable('option', 'out')
									.call(this, event, ui);
								self.save($droppable);
							}
						})
					)
					.append($('<a class="tree-favpanel-toolbar-prev"></a>')
						.html('<span class="ui-icon ui-icon-arrowthick-1-w" title="precedent (en dev)"> </span>')
						.css({
							 'top' : '22px'
						})
						.click(function(){
							self.load();
							return false;
						})
					)
					.css({
						'position': 'absolute'
						, 'background-color': '#F7F7F7'
						, top: '2px'
						, left: '2px'
					})
					.prependTo($panels)
				;
			}
			return $toolbar;
		};
		
		/* save nodes
		*/
		this.save = function($dom, data, callback){
			if(!data){
				data = {};
				var page = { x : $dom.scrollLeft(), y : $dom.scrollTop() };
				$dom.children('.jstree-node').each(function(){
					var $this = $(this);
					var pos = $this.position();
					if((page.y + pos.top + $this.height()) <= 14)
						return;
					data[this.getAttribute('node_id')] = {
						h: Math.round($this.height())
						, w: Math.round($this.width())
						, x: Math.round(page.x + pos.left)
						, y: Math.round(page.y + pos.top)
						, text: $this.text()
						, icon: $this.find('> a > i').attr('class').replace(/(\s*\jstree[^\s]+(\s|$))/g, '')
					};
				});
			}
			
			$.post('view.php?id=/_System/Utilisateur/Preferences/set', { 
					domain : this.settings.favpanel.userpref_domain,
					param : $dom[0].tagName + '#' + $dom.attr('id'),
					value : typeof data === "object" ? JSON.stringify(data) : data,
					'get' : false
				})
				.done(function(data){
					if(typeof callback === 'function')
						if(callback.call(this, data))
							return;
						
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
					$('<div>Erreur de sauvegarde</div>')
						.append(data)
						.dialog();
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
						x = data.event.offsetX==undefined ? data.event.pageX - 32 : data.event.offsetX - 24,
						y = data.event.offsetY==undefined ? data.event.pageY - 8 : data.event.offsetY + 8
						;
						var grid = data.data.origin.settings.favpanel.grid;
						data.data.origin.add_node($droppable, {
							'id': data.data.nodes[0]
							, "x" : Math.round((x) / grid[0]) * grid[0] + page.x
							, "y" : Math.round((y) / grid[1]) * grid[1] + page.y
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

