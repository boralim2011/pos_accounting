<!--<div class="modal modal-success fade in" id="dialog-item_class">-->
<div class="modal fade in" id="dialog-item_class">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Item_class</h4>
            </div>
            <div class="modal-body">
                <form id="item_class-form" role="form" action="<?php echo base_url()?>item_class/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="item_class_id" name="item_class_id" value="0"/>

                    <div class="form-group">
                        <label for="item_class_name">Item_class Name</label>
                        <input type="text" class="form-control" id="item_class_name" name="item_class_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="item_class_name">Item_class Name KH</label>
                        <input type="text" class="form-control" id="item_class_name_kh" name="item_class_name_kh" placeholder="Enter Name KH">
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-item_class"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        //add item_class
        $(document).off("click","#btn-new-item_class");
        $(document).on("click","#btn-new-item_class", function(event){
            event.preventDefault();

            $("#dialog-item_class .modal-title").html("New Item_class");
            $("#item_class-form").attr('action', '<?php echo base_url()?>item_class/add' );
            $("#item_class_id").val(0);

            $("#dialog-item_class").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-item_class');
        $(document).on('hidden.bs.modal', '#dialog-item_class', function() {
            $("#item_class-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit item_class to service to save
        $(document).off("click","#btn-add-item_class");
        $(document).on("click","#btn-add-item_class", function(event){
            event.preventDefault();


            var form = $("#item_class-form");
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
                            //$("#item_class_id").val(data.model.item_class_id);
                            $("#item_class-form")[0].reset();
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