$(document).ready(function() {

    $("#datatable").DataTable(),
    $("#datatable1").DataTable(),
    $("#datatable2").DataTable(),
    $("#datatable3").DataTable(),
    $("#datatable4").DataTable(),
    $("#datatable5").DataTable(),
    $("#datatable6").DataTable(),
        $("#datatable-buttons")
            .DataTable({
                lengthChange: !1,
                buttons: ["copy", "excel", "pdf"]
            })
            .buttons()
            .container()
            .appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"),
           

        $(".dataTables_length select").addClass("form-select form-select-sm");
        
});
