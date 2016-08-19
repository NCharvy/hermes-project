/**
 * Created by nythe on 28/07/16.
 */
$(document).ready(function() {
    $('#all-table').DataTable();
    $('div.dataTables_filter').addClass('col-sm-offset-3');
    $('.dataTables_wrapper .row:nth-child(3) > div:nth-child(1)').addClass('col-sm-4');
    $('.dataTables_wrapper .row:nth-child(3) > div:nth-child(2)').addClass('col-sm-8');

    $('.dropdown-toggle').dropdown();
} );