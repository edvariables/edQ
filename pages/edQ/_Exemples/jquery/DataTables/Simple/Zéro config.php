﻿<?php include(substr(__FILE__, 0, strlen(__FILE__) - 4) . '/data.php');?>
<script>
$(document).ready(function() {
    $('#example').dataTable( {
            "language": {
                "url": "jquery/dataTables/lang/dataTables.french.json"
            }
        } );
} );
</script>