<!--<div class="modal modal-success fade in" id="dialog-payment">-->
<div class="modal fade in" id="dialog-payment">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New Payment</h4>
            </div>
            <div class="modal-body">
                <form id="payment-form" role="form" action="<?php echo base_url()?>payment/add" method="post" accept-charset="utf-8">

                    <input type="hidden" id="payment_id" name="payment_id" value="<?php echo $payment->payment_id;?>" '/>
                    <input type="hidden" id="journal" name="journal" value='<?php echo json_encode($journal);?>' />

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="payment_name">Payment Method</label>
                                <select class="form-control" name="payment_method_id"  id="payment_method_id">
                                    <?php
                                    if(isset($payment_methods) && is_array($payment_methods))
                                    {
                                        foreach($payment_methods as $key=>$row){
                                            ?>
                                            <option value="<?php echo $row->payment_method_id;?>" <?php if($payment->payment_method_id==$row->payment_method_id) echo 'selected="selected"';?> > <?php echo $row->payment_method_name;?></option>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6" id="input_card">
                            <div class="form-group">
                                <label for="payment_name">Card Number</label>
                                <input type="password" class="form-control" name="card_number"  id="card_number" value="<?php echo $payment->card_number;?>"/>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th>
                                <span class="text-blue">1USD = <?php echo $payment->exchange_rate;?>KHR</span>
                            </th>
                            <th class="text-right">
                                USD
                            </th>
                            <th class="text-right">
                                KHR
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <label for="payment_name">Total Amount</label>
                            </td>
                            <td class="text-right">
                                <?php echo $journal->format_total();?>
                            </td>
                            <td class="text-right">
                                <?php echo $journal->format_total_riel();?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="payment_name">Discount</label>
                            </td>
                            <td class="text-right">
                                <?php echo $journal->discount;?>%
                            </td>
                            <td class="text-right">

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="payment_name">Balance</label>
                            </td>
                            <td class="text-right">
                                <?php echo $journal->format_total_after_discount();?>
                            </td>
                            <td class="text-right">
                                <?php echo $journal->format_total_riel_after_discount();?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="payment_name">Receive</label>
                            </td>
                            <td>
                                <input type="text" class="form-control text-right" id="receive_amount" name="receive_amount" placeholder="0.00">
                            </td>
                            <td>
                                <input type="text" class="form-control text-right" id="receive_amount_riel" name="receive_amount_riel" placeholder="0">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="payment_name">Refund</label>
                            </td>
                            <td class="text-right">
                                $<span id="refund">0.00</span>
                            </td>
                            <td class="text-right">
                                ៛$<span id="refund_riel">0</span>
                            </td>
                        </tr>
                    </table>

                    <div class="clearfix"></div>
                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">
                    <button type="button" class="btn btn-primary pull-left" id="btn-add-payment"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    $(document).ready(function(){

        <?php if($payment->payment_method_id!=2){
        ?>
        $("#input_card").hide();
        <?php
        }?>

        $(document).off('change', '#payment_method_id');
        $(document).on('change', '#payment_method_id', function(e) {
            e.preventDefault();

            var id = $(this).val();
            if(id==2) $("#input_card").show();
            else $("#input_card").hide();

            if(id==1)
            {
                $('#receive_amount').attr("readonly", false);
                $('#receive_amount_riel').attr("readonly", false);

                $('#receive_amount').val(0);
                $('#receive_amount_riel').val(0);
            }
            else
            {
                $('#receive_amount').attr("readonly", true);
                $('#receive_amount_riel').attr("readonly", true);

                var journal =JSON.parse($("#journal").val());
                var balance = journal.total * ( 1 - journal.discount /100);

                $('#receive_amount').val(balance);
                $('#receive_amount_riel').val(0);
            }
        });

        $(document).off('keyup', '#receive_amount');
        $(document).on('keyup', '#receive_amount', function(e) {
            e.preventDefault();

            var amount = $(this).val();
            var refund = 0;
            var journal =JSON.parse($("#journal").val());
            var balance = journal.total * ( 1 - journal.discount /100);

            if(amount>balance) refund = amount - balance;

            $("#refund").html(refund);

        });

        //add payment
        $(document).off("click","#btn-new-payment");
        $(document).on("click","#btn-new-payment", function(event){
            event.preventDefault();

            $("#dialog-payment .modal-title").html("New Payment");
            $("#payment-form").attr('action', '<?php echo base_url()?>payment/add' );
            $("#payment_id").val(0);

            $("#dialog-payment").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-payment');
        $(document).on('hidden.bs.modal', '#dialog-payment', function() {
            $("#payment-form")[0].reset();
            $("#modal-message").empty();
            $(".btn-refresh").trigger('click');
        });

        //submit payment to service to save
        $(document).off("click","#btn-add-payment");
        $(document).on("click","#btn-add-payment", function(event){
            event.preventDefault();


            var form = $("#payment-form");
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
                            //$("#payment_id").val(data.model.payment_id);
                            $("#payment-form")[0].reset();
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