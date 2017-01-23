<!--<div class="modal modal-success fade in" id="dialog-item_type">-->
<div class="modal fade in" id="dialog-item_type">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Item_type</h4>
            </div>
            <div class="modal-body">
                <form id="item_type-form" role="form" action="<?php echo base_url()?>item_type/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="item_type_id" name="item_type_id" value="0"/>

                    <div class="form-group">
                        <label >Parent</label>
                        <select id="parent_id" name="parent_id" class="form-control select2" data-placeholder="Parent"  style="width: 100%; display: none;">
                            <option value="1" <?php echo isset($parent_id) && $parent_id=="1" ? 'selected="selected"':'';?> >Product</option>
                            <option value="2" <?php echo isset($parent_id) && $parent_id=="2" ? 'selected="selected"':'';?> >Service</option>
                            <option value="3" <?php echo isset($parent_id) && $parent_id=="3" ? 'selected="selected"':'';?> >Set</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item_type_name">Item_type Name</label>
                        <input type="text" class="form-control" id="item_type_name" name="item_type_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="item_type_name">Item_type Name KH</label>
                        <input type="text" class="form-control" id="item_type_name_kh" name="item_type_name_kh" placeholder="Enter Name KH">
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
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-item_type"><i class="fa fa-save"></i> Save</button>
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

        //add item_type
        $(document).off("click","#btn-new-item_type");
        $(document).on("click","#btn-new-item_type", function(event){
            event.preventDefault();

            $("#dialog-item_type .modal-title").html("New Item_type");
            $("#item_type-form").attr('action', '<?php echo base_url()?>item_type/add' );
            $("#item_type_id").val(0);

            $("#dialog-item_type").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-item_type');
        $(document).on('hidden.bs.modal', '#dialog-item_type', function() {
            $("#item_type-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit item_type to service to save
        $(document).off("click","#btn-add-item_type");
        $(document).on("click","#btn-add-item_type", function(event){
            event.preventDefault();


            var form = $("#item_type-form");
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
                            //$("#item_type_id").val(data.model.item_type_id);
                            $("#item_type-form")[0].reset();
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