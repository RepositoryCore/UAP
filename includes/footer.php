<script>
    $(document).ready(function() {
        var table = $('#example').DataTable( {
            "scrollY": "1050px",
            "scrollCollapse": true,
            lengthChange: false,
            searching: false,
            paging: false,
            info: false,
            sort: false,
        });            

        var table = $('#PriceSummary').DataTable( {
            lengthChange: false,
            searching: false,
            paging: false,
            info: false,
            sort: false,
        });            

        var table = $('#summary').DataTable({
            lengthChange: false,
            searching: false,
            paging: false,
            info: false,
            sort: false,
            buttons: [
                {
                    extend: 'excel',
                    text: 'CONVERT TO EXCEL'
                }
            ]
        });
        table.buttons().container().appendTo('#summary_wrapper .col-md-6:eq(0)');

        var table = $('#list').DataTable( {
            paging: false,
            searching: false,
            info: false,

        });            
    });
</script>  

<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/dist/js/adminlte.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>



