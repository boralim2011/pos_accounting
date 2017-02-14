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

                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-6 col-xs-6">
                            <label >From Currency</label>
                            <select id="from_currency_id" name="from_currency_id" class="form-control select2" data-placeholder="From Currency"  style="width: 100%; display: none;">
                                <?php
                                    if(isset($from_currencies) && is_array($from_currencies)) {
                                        foreach($from_currencies as $fc){
                                ?>
                                    <option value="<?php echo $fc->currency_id;?>" ><?php echo $fc->currency_name; ?></option>
                                <?php   }
                                    }?>
                            </select>
                        </div>
                        <div class="form-group col-lg-6 col-sm-6 col-xs-6">
                            <label >To Currency</label>
                            <select id="to_currency_id" name="to_currency_id" class="form-control select2" data-placeholder="To Currency"  style="width: 100%; display: none;">
                                <?php
                                if(isset($to_currencies) && is_array($to_currencies)) {
                                    foreach($to_currencies as $tc){
                                        ?>
                                        <option value="<?php echo $tc->currency_id;?>" ><?php echo $tc->currency_name; ?></option>
                                    <?php } }?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6 col-sm-6 col-xs-6">
                            <label for="bit_rate">Bit</label>
                            <input type="text" class="form-control" id="bit_rate" name="bit_rate" placeholder="Bit Rate" >
                        </div>
                        <div class="form-group col-lg-6 col-sm-6 col-xs-6">
                            <label for="ask_rate">Ask</label>
                            <input type="text" class="form-control" id="ask_rate" name="ask_rate" placeholder="Ask Rate" >
                        </div>
                    </div>
                    <div class="form-group">
                        <!--<label for="manage_stock">&nbsp;</label>-->
                        <div>
                            <label><input type="checkbox" class="minimal form-control" id="is_inverse" name="is_inverse" > Inverse</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exchange_rate_name">Display</label>
                        <input type="text" class="form-control" id="rate_display" name="rate_display" readonly="readonly">
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

        $("#from_currency_id").select2();
        $("#to_currency_id").select2();

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