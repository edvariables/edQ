<?php
/***
 *  node([$search], [$refers_to], [[$options], ][$method], [$method_options])
 *    function globale d'accès à un noeud
 *
 *    node(':details', $node, 'call')
 *    node('/_System/debug/phpinfo', $node, 'call')
 *    node('/_System/debug/phpinfo', $node, 'html')
 */

function node($search = null, $refers_to = null, $options = null, $method = null, $method_options = null){
    
    // hack : décalage des arguments si $options est une fonction ou de type string
    if(is_callable($options)
    || is_string($options)){
        $method_options = $method;
        $method = $options;
        $options = null;
    }
    
    // $search
    if(($search == null) || !$search || $search == '.'){
        if($refers_to == null){
                $dt = debug_backtrace();
                for($i = 0; $i < count($dt); $i++)
                        if($dt[$i]['file'] != __FILE__)
                                break;
                $refers_to = $dt[$i]['file'];
        }
        if(is_string($refers_to)){
            $search = substr($refers_to, strlen( page::get_root_path() ));
            // supprime le .php et remplace \ par /
            $search = str_replace('\\', '/', substr( $search, 0, strlen($search) - 4));
            $search = substr( $search, strlen(TREE_ROOT_NAME) + 1 );
        }
    }
    
    // recherche
    $node = tree::get_node_by_name($search, $refers_to, $options);
    
    // $method
    if(is_callable( $method ) && $method != 'file'){
        return $method ( $node, $method_options );
    }
    elseif(is_string($method)){
        // methodes statiques de page
        switch($method){
            case "call":
            case "execute":
                return page::execute($node, $refers_to, '.php', $method_options);
            
            case "url":
                return page::url($node, $refers_to, $method_options);
            case "html":
                return page::html($node, $refers_to, $method_options);
            case "view":
                $viewer = is_string($method_options)
                    ? $method_options
                    : (is_array($method_options) && isset($method_options['viewer'])
                        ? $method_options['viewer']
                        : 'file.call');
                return page::view($viewer, $node, $refers_to, $method_options);
            case "viewer":
                return page::viewer($node, $refers_to);
            
            case "file":
                return page::file($node, $refers_to);
            case "file_url":
                return page::file($node, $refers_to);
            case "folder":
                return page::folder($node, $refers_to);
            case "name":
                $method = 'nm';
                break;
            default:
                break;
        }
        $method = $node [ $method ];
        if(is_callable( $method )){
            return $method ( $method_options );
        }
        return $method;
    }
    
    // standard, returns $node
    return $node;
}
?>