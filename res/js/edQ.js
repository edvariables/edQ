(function(){
	window.edQ = jQuery.extend(function(){
		}
		, {
		/* eval_functions
			Parses object properties and converts string like 'function(...}' to function by eval()
			eval_functions({
				'my_func' : "function(obj){ return obj; }"
			})
			returns
			{
				'my_func' : function(obj){
					return obj;
				}
			}
			Allows to json_encode a php array with a javascript function to a javascript object
			$params = array( 'title' => 'name'
					, 'render' => 'function ( data, type, full ) {
						  return data;
					}'
			)
			//injects object in javascript
			echo "edQ.eval_functions( " . json_encode( $params ) . " )";
		*/
		'eval_functions' : function(obj){
			switch(typeof obj){
			case "string" :
				if((typeof obj === "string")
				&& /^function\s*\([\s\S]*[}]\s*$/.test(obj)){
					try {
						return eval( "(" + obj + ")");
					}
					catch(ex){
						alert("Erreur de fonction de colonne : " + ex);
					}
				}
				break;
			case "object": 
				if(obj.length)
					for(var item = 0; item < obj.length; item++)
						obj[item] = arguments.callee.call(this, obj[item]);//recursive
				else
					for(var prop in obj){
						obj[prop] = arguments.callee.call(this, obj[prop]);//recursive
					}
				break;
			default:
				break;
			}
			return obj;
		}
	});
})();
