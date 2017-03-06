<!--<div class="modal modal-success fade in" id="dialog-lot">-->
<div class="modal fade in" id="dialog-lot">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Lot</h4>
            </div>
            <div class="modal-body">
                <form id="lot-form" role="form" action="<?php echo base_url()?>warehouse/add_lot" method="post" accept-charset="utf-8">

                    <input type="hidden" id="lot_id" name="warehouse_id" value="0"/>

                    <div class="form-group">
                        <label>Warehouse</label>
                        <select class="form-control select2" id="parent_id" name="parent_id" data-placeholder="Warehouse" style="width: 100%; display: none;">
                            <option></option>
                            <option value="1" selected="selected">Warehouse</option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="warehouse_name">Lot Name</label>
                        <input type="text" class="form-control" id="lot_name" name="warehouse_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="warehouse_name">Lot Name KH</label>
                        <input type="text" class="form-control" id="lot_name_kh" name="warehouse_name_kh" placeholder="Enter Name KH">
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-lot"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    function initialize_select2()
    {
        $("#parent_id").select2({
            ajax: {
                url: "<?php echo base_url()?>warehouse/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                        is_warehouse : 0
                    };
                },
                processResults: function (data) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }

    $(document).ready(function(){

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        initialize_select2();

        //add warehouse
        $(document).off("click","#btn-new-lot");
        $(document).on("click","#btn-new-lot", function(event){
            event.preventDefault();

            $("#dialog-lot .modal-title").html("New Lot");
            $("#lot-form").attr('action', '<?php echo base_url()?>warehouse/add_lot' );
            $("#warehouse_id").val(0);

            $("#dialog-lot").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-lot');
        $(document).on('hidden.bs.modal', '#dialog-lot', function() {
            $("#lot-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit warehouse to service to save
        $(document).off("click","#btn-add-lot");
        $(document).on("click","#btn-add-lot", function(event){
            event.preventDefault();


            var form = $("#lot-form");
            var formData = new FormData(form[0]);
            var url = form.attr('action').toString();

            //var formData = new FormData();
            formData.append('submit', 1);
            // Main magic with files here
            //formData.append('file', $('input[type=file]')[0].files[0]);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType:"json",
                //async: false,
                cache: false,
                beforeSend: function(xhr){
                    //alert('Before send');
                },
                success: function(data, status, xhr)
                {
                    if(data==521){
                        go_to_login();
                    }
                    else{
                        if(data.success===true){
                            //$("#warehouse_id").val(data.model.warehouse_id);
                            $("#lot-form")[0].reset();
                        }
                        //alert(data.message);
                        show_message(data.message, $("#modal-message"));
                    }
                },
                error: function(xhr,status,error){
                    //alert('error' + error);
                    var message = '{"text":"'+error+'","type":"Error","title":"Error"}';
                    show_message(message, $("#modal-message"));
                },
                complete: function(xhr,status){
                    //alert(status);
                },
                contentType: false,
                processData: false
            });

            return false;
        });

    });
</script>