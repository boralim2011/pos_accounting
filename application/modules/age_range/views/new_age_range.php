<!--<div class="modal modal-success fade in" id="dialog-age_range">-->
<div class="modal fade in" id="dialog-age_range">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Age_range</h4>
            </div>
            <div class="modal-body">
                <form id="age_range-form" role="form" action="<?php echo base_url()?>age_range/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="age_range_id" name="age_range_id" value="0"/>

                    <div class="form-group">
                        <label for="age_range_name">Age_range Name</label>
                        <input type="text" class="form-control" id="age_range_name" name="age_range_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="age_range_name">Age_range Name KH</label>
                        <input type="text" class="form-control" id="age_range_name_kh" name="age_range_name_kh" placeholder="Enter Name KH">
                    </div>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-age_range"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        //add age_range
        $(document).off("click","#btn-new-age_range");
        $(document).on("click","#btn-new-age_range", function(event){
            event.preventDefault();

            $("#dialog-age_range .modal-title").html("New Age_range");
            $("#age_range-form").attr('action', '<?php echo base_url()?>age_range/add' );
            $("#age_range_id").val(0);

            $("#dialog-age_range").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-age_range');
        $(document).on('hidden.bs.modal', '#dialog-age_range', function() {
            $("#age_range-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit age_range to service to save
        $(document).off("click","#btn-add-age_range");
        $(document).on("click","#btn-add-age_range", function(event){
            event.preventDefault();


            var form = $("#age_range-form");
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
                            //$("#age_range_id").val(data.model.age_range_id);
                            $("#age_range-form")[0].reset();
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