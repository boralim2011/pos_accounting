<!--<div class="modal modal-success fade in" id="dialog-user-group">-->
<div class="modal fade in" id="dialog-user-group">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New user group</h4>
            </div>
            <div class="modal-body">
                <form id="user-group-form" role="form" action="<?php echo base_url()?>user_group/add" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="user_group_name">User Group</label>
                        <input type="text" class="form-control" id="user_group_name" name="user_group_name" placeholder="Enter name">
                        <input type="hidden" id="user_group_id" name="user_group_id" value="0"/>
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-user-group"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        //add user group
        $(document).off("click","#btn-new-user-group");
        $(document).on("click","#btn-new-user-group", function(event){
            event.preventDefault();

            $("#dialog-user-group .modal-title").html("New User Group");
            $("#user-group-form").attr('action', '<?php echo base_url()?>user_group/add' );
            $("#user_group_id").val(0);

            $("#dialog-user-group").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-user-group');
        $(document).on('hidden.bs.modal', '#dialog-user-group', function() {
            $("#user-group-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit user group to service to save
        $(document).off("click","#btn-add-user-group");
        $(document).on("click","#btn-add-user-group", function(event){
            event.preventDefault();


            var form = $("#user-group-form");
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
                            //$("#user_group_id").val(data.model.user_group_id);
                            $("#user-group-form")[0].reset();
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