<!--<div class="modal modal-success fade in" id="dialog-room">-->
<div class="modal fade in" id="dialog-room">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Room</h4>
            </div>
            <div class="modal-body">
                <form id="room-form" role="form" action="<?php echo base_url()?>room/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="room_id" name="room_id" value="0"/>

                    <div class="form-group">
                        <label >Room Type</label>
                        <select id="room_type_id" name="room_type_id" class="form-control select2" data-placeholder="Room Type"  style="width: 100%; display: none;">
                            <option value="1" selected="selected">Normal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="room_name">Room Name</label>
                        <input type="text" class="form-control" id="room_name" name="room_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="room_name">Room Name KH</label>
                        <input type="text" class="form-control" id="room_name_kh" name="room_name_kh" placeholder="Enter Name KH">
                    </div>


                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-room"><i class="fa fa-save"></i> Save</button>
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
        $("#room_type_id").select2({
            ajax: {
                url: "<?php echo base_url()?>room_type/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
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

        //add room
        $(document).off("click","#btn-new-room");
        $(document).on("click","#btn-new-room", function(event){
            event.preventDefault();

            $("#dialog-room .modal-title").html("New Room");
            $("#room-form").attr('action', '<?php echo base_url()?>room/add' );
            $("#room_id").val(0);

            $("#dialog-room").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-room');
        $(document).on('hidden.bs.modal', '#dialog-room', function() {
            $("#room-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit room to service to save
        $(document).off("click","#btn-add-room");
        $(document).on("click","#btn-add-room", function(event){
            event.preventDefault();


            var form = $("#room-form");
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
                            //$("#room_id").val(data.model.room_id);
                            $("#room-form")[0].reset();
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