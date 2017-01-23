<!--<div class="modal modal-success fade in" id="dialog-maker">-->
<div class="modal fade in" id="dialog-maker">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Maker</h4>
            </div>
            <div class="modal-body">
                <form id="maker-form" role="form" action="<?php echo base_url()?>maker/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="maker_id" name="maker_id" value="0"/>

                    <div class="form-group">
                        <label for="maker_name">Maker Name</label>
                        <input type="text" class="form-control" id="maker_name" name="maker_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="maker_name">Maker Name KH</label>
                        <input type="text" class="form-control" id="maker_name_kh" name="maker_name_kh" placeholder="Enter Name KH">
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-maker"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        //add maker
        $(document).off("click","#btn-new-maker");
        $(document).on("click","#btn-new-maker", function(event){
            event.preventDefault();

            $("#dialog-maker .modal-title").html("New Maker");
            $("#maker-form").attr('action', '<?php echo base_url()?>maker/add' );
            $("#maker_id").val(0);

            $("#dialog-maker").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-maker');
        $(document).on('hidden.bs.modal', '#dialog-maker', function() {
            $("#maker-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit maker to service to save
        $(document).off("click","#btn-add-maker");
        $(document).on("click","#btn-add-maker", function(event){
            event.preventDefault();


            var form = $("#maker-form");
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
                            //$("#maker_id").val(data.model.maker_id);
                            $("#maker-form")[0].reset();
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