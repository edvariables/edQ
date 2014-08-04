<?php
$root = page::folder_url('', __FILE__);
$dataUrl = $root . '/data.php';
$cssUrl = $root . '/css.php';

$root = page::folder($node);
?>
<?php $uid = uniqid('datatable');?>
<?php include($root . '/html.php');?>
<style><?php include($root . '/css.php');?></style>
<script>
function format ( d ) {
    return 'Full name: '+d.first_name+' '+d.last_name+'<br>'+
        'Salary: '+d.salary+'<br>'+
        'The child row can contain any data you wish, including links, images, inner tables etc.';
}
 
$(document).ready(function() {
    var dt = $('#<?=$uid?>').DataTable( {
        "language": {
            "url": "jquery/dataTables/lang/dataTables.french.json"
        },
	"ajax" : {
	 	 "url": "<?=$dataUrl?>"
	 },
        "columns": [
            {
                "class":          "details-control",
                "orderable":      false,
                "data":           null,
                "defaultContent": ""
            },
            { "data": "first_name", 
	 	 "render": function(  data, type, row  ) {
	 	 	 return '<b>' + row["first_name"] + '</b>';
	 	 } },
            { "data": "last_name" },
            { "data": "position" },
            { "data": "office" }
        ],
        "order": [[1, 'asc']]
    } );
 
    // Array to track the ids of the details displayed rows
    var detailRows = [];
 
    $('#<?=$uid?> tbody').on( 'click', 'tr td:first-child', function () {
        var tr = $(this).closest('tr');
        var row = dt.row( tr );
        var idx = $.inArray( tr.attr('id'), detailRows );
 
        if ( row.child.isShown() ) {
            tr.removeClass( 'details' );
            row.child.hide();
 
            // Remove from the 'open' array
            detailRows.splice( idx, 1 );
        }
        else {
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();
 
            // Add to the 'open' array
            if ( idx === -1 ) {
                detailRows.push( tr.attr('id') );
            }
        }
    } );
 
    // On each draw, loop over the `detailRows` array and show any child rows
    dt.on( 'draw', function () {
        $.each( detailRows, function ( i, id ) {
            $('#'+id+' td:first-child').trigger( 'click' );
        } );
    } );
} );
</script>