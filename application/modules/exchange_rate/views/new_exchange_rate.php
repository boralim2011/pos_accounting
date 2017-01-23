<!--<div class="modal modal-success fade in" id="dialog-exchange_rate">-->
<div class="modal fade in" id="dialog-exchange_rate">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Exchange_rate</h4>
            </div>
            <div class="modal-body">
                <form id="exchange_rate-form" role="form" action="<?php echo base_url()?>exchange_rate/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="exchange_rate_id" name="exchange_rate_id" value="0"/>

                    <div class="form-group">
                        <label >Parent</label>
                        <select id="parent_id" name="parent_id" class="form-control select2" data-placeholder="Parent"  style="width: 100%; display: none;">
                            <option value="1" <?php echo isset($parent_id) && $parent_id=="1" ? 'selected="selected"':'';?> >Product</option>
                            <option value="2" <?php echo isset($parent_id) && $parent_id=="2" ? 'selected="selected"':'';?> >Service</option>
                            <option value="3" <?php echo isset($parent_id) && $parent_id=="3" ? 'selected="selected"':'';?> >Set</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exchange_rate_name">Exchange_rate Name</label>
                        <input type="text" class="form-control" id="exchange_rate_name" name="exchange_rate_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="exchange_rate_name">Exchange_rate Name KH</label>
                        <input type="text" class="form-control" id="exchange_rate_name_kh" name="exchange_rate_name_kh" placeholder="Enter Name KH">
                    </div>
                    <div class="form-group">
                        <!--<label for="manage_stock">&nbsp;</label>-->
                        <div>
                            <label><input type="checkbox" class="minimal form-control" id="manage_stock" name="manage_stock" > Manage Stock</label>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-exchange_rate"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });

        $("#parent_id").select2();

        //add exchange_rate
        $(document).off("click","#btn-new-exchange_rate");
        $(document).on("click","#btn-new-exchange_rate", function(event){
            event.preventDefault();

            $("#dialog-exchange_rate .modal-title").html("New Exchange_rate");
            $("#exchange_rate-form").attr('action', '<?php echo base_url()?>exchange_rate/add' );
            $("#exchange_rate_id").val(0);

            $("#dialog-exchange_rate").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-exchange_rate');
        $(document).on('hidden.bs.modal', '#dialog-exchange_rate', function() {
            $("#exchange_rate-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit exchange_rate to service to save
        $(document).off("click","#btn-add-exchange_rate");
        $(document).on("click","#btn-add-exchange_rate", function(event){
            event.preventDefault();


            var form = $("#exchange_rate-form");
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
                            //$("#exchange_rate_id").val(data.model.exchange_rate_id);
                            $("#exchange_rate-form")[0].reset();
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