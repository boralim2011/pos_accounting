<!--<div class="modal modal-success fade in" id="dialog-user-role">-->
<div class="modal fade in" id="dialog-user-role">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New user role</h4>
            </div>
            <div class="modal-body">
                <form id="user-role-form" role="form" action="<?php echo base_url()?>user_role/add" method="post" accept-charset="utf-8">
                    <div class="form-role">
                        <label for="user_role_name">User Role</label>
                        <input type="text" class="form-control" id="user_role_name" name="user_role_name" placeholder="Enter name">
                        <input type="hidden" id="user_role_id" name="user_role_id" value="0"/>
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-role no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-user-role"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<script type="text/javascript">

    $(document).ready(function(){

        //add user type
        $(document).off("click","#btn-new-user-role");
        $(document).on("click","#btn-new-user-role", function(event){
            event.preventDefault();

            $("#dialog-user-role .modal-title").html("New User Role");
            $("#user-role-form").attr('action', '<?php echo base_url()?>user_role/add' );
            $("#user_role_id").val(0);

            $("#dialog-user-role").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-user-role');
        $(document).on('hidden.bs.modal', '#dialog-user-role', function() {
            $("#user-role-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit user type to service to save
        $(document).off("click","#btn-add-user-role");
        $(document).on("click","#btn-add-user-role", function(event){
            event.preventDefault();


            var form = $("#user-role-form");
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
                            //$("#user_role_id").val(data.model.user_role_id);
                            $("#user-role-form")[0].reset();
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