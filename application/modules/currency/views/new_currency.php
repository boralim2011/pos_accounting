<!--<div class="modal modal-success fade in" id="dialog-currency">-->
<div class="modal fade in" id="dialog-currency">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Currency</h4>
            </div>
            <div class="modal-body">
                <form id="currency-form" role="form" action="<?php echo base_url()?>currency/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="currency_id" name="currency_id" value="0"/>

                    <div class="form-group">
                        <label for="currency_name">Currency Name</label>
                        <input type="text" class="form-control" id="currency_name" name="currency_name" placeholder="Enter Name">
                    </div>
                    <div class="form-group">
                        <label for="currency_name">Currency Name KH</label>
                        <input type="text" class="form-control" id="currency_name_kh" name="currency_name_kh" placeholder="Enter Name KH">
                    </div>
                    <div class="form-group">
                        <label for="currency_symbol">Symbol</label>
                        <input type="text" class="form-control" id="currency_symbol" name="currency_symbol" placeholder="Enter Symbol">
                    </div>
                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-currency"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        //add currency
        $(document).off("click","#btn-new-currency");
        $(document).on("click","#btn-new-currency", function(event){
            event.preventDefault();

            $("#dialog-currency .modal-title").html("New Currency");
            $("#currency-form").attr('action', '<?php echo base_url()?>currency/add' );
            $("#currency_id").val(0);

            $("#dialog-currency").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-currency');
        $(document).on('hidden.bs.modal', '#dialog-currency', function() {
            $("#currency-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit currency to service to save
        $(document).off("click","#btn-add-currency");
        $(document).on("click","#btn-add-currency", function(event){
            event.preventDefault();


            var form = $("#currency-form");
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
                            //$("#currency_id").val(data.model.currency_id);
                            $("#currency-form")[0].reset();
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