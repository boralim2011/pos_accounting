<!--<div class="modal modal-success fade in" id="dialog-room_type">-->
<div class="modal fade in" id="dialog-room_type">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Room_type</h4>
            </div>
            <div class="modal-body">
                <form id="room_type-form" role="form" action="<?php echo base_url()?>room_type/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="room_type_id" name="room_type_id" value="0"/>

                    <div class="form-group">
                        <label for="room_type_name">Room_type Name</label>
                        <input type="text" class="form-control" id="room_type_name" name="room_type_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="room_type_name">Room_type Name KH</label>
                        <input type="text" class="form-control" id="room_type_name_kh" name="room_type_name_kh" placeholder="Enter Name KH">
                    </div>
                   

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-room_type"><i class="fa fa-save"></i> Save</button>
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

        //add room_type
        $(document).off("click","#btn-new-room_type");
        $(document).on("click","#btn-new-room_type", function(event){
            event.preventDefault();

            $("#dialog-room_type .modal-title").html("New Room_type");
            $("#room_type-form").attr('action', '<?php echo base_url()?>room_type/add' );
            $("#room_type_id").val(0);

            $("#dialog-room_type").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-room_type');
        $(document).on('hidden.bs.modal', '#dialog-room_type', function() {
            $("#room_type-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit room_type to service to save
        $(document).off("click","#btn-add-room_type");
        $(document).on("click","#btn-add-room_type", function(event){
            event.preventDefault();


            var form = $("#room_type-form");
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
                    //alert(data); return;

                    if(data==521){
                        go_to_login();
                    }
                    else{
                        if(data.success===true){
                            //$("#room_type_id").val(data.model.room_type_id);
                            $("#room_type-form")[0].reset();
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