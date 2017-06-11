<table id="sale-items-table"
       class="table table-bordered table-hover data-table"
       data-json='<?php echo isset($sale_items) && is_array($sale_items)? json_encode($sale_items):"";?>'
       delete-json='<?php echo isset($delete_sale_items) && is_array($delete_sale_items)? json_encode($delete_sale_items):"";?>'
    >
    <thead>
        <tr>
            <th colspan="2">Item</th>
            <th >Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Amount</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if(isset($sale_items) && is_array($sale_items) && count($sale_items)>0){
            $no=0;
            foreach($sale_items as $row){
                $no ++;
                ?>
                <tr>
                    <td width="15px"><?php echo $no;?></td>
                    <td><?php echo isset($row->item_name)? $row->item_name:"";?></td>
                    <td><span id="<?php echo $no;?>" style="width:30px; display: inline-block;text-align: right"><?php echo isset($row->qty)? $row->qty:"";?></span>
                        <span style="width:40px; display: inline-block;">  <?php echo isset($row->unit_name)? $row->unit_name:"";?> </span>
                        <a href="#<?php echo $no;?>" class="btn-minus"><i class="fa fa-minus-square" style="color:#dd0033; margin: 0 3px;"></i> </a>
                        <a href="#<?php echo $no;?>" class="btn-plus"><i class="fa fa-plus-square" style="color:green; margin: 0 3px;"></i> </a>
                    </td>
                    <td class="text-right">$ <?php echo $row->get_price();?></td>
                    <td class="text-right">$ <?php echo $row->get_amount();?></td>
                    <td>
                        <!--<a href="#" data-json='{"member_id":"<?php //echo $row->member_id;?>"}' class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>-->
                        <a href="#<?php echo $no;?>" class="inline-button btn-remove" data-toggle="tooltip" title="Remove" > <i class="fa fa-trash-o text-red"></i> </a>
                    </td>
                </tr>
            <?php
            }
        }
        else{
            ?>
            <tr>
                <td colspan="6">No item selected!</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <td rowspan="4" colspan="3" >
                <?php echo isset($exchange_rate)? "Rate : ".$exchange_rate->get_display() : "";?>
            </td>
            <td class="text-right text-bold">Amount (USD) : </td>
            <td class="text-right text-bold">$ <span id="amount_usd"><?php echo isset($total_amount)? $total_amount:0; ?></span></td>
            <td></td>
        </tr>
        <tr>
            <td class="text-right text-bold">Discount (%) : </td>
            <td class="text-right text-bold">
                <span id="discount_rate"><?php echo isset($discount)? $discount:0; ?></span> %
                <input type="hidden" id="discount" name="discount" value="<?php echo isset($discount)? $discount:0; ?>" />
            </td>
            <td></td>
        </tr>
        <tr>
            <td class="text-right text-bold" >Total (USD) : </td>
            <td class="text-right text-bold">$ <span id="total_usd"><?php echo isset($total_usd)? $total_usd:0; ?></span></td>
            <td></td>
        </tr>
        <tr>
            <td class="text-right text-bold" >Total (KHR) : </td>
            <td class="text-right text-bold">៛ <span id="total_khr"><?php echo isset($total_khr)? $total_khr:0; ?></span> </td>
            <td></td>
        </tr>
    </tfoot>

</table>


<script type="text/javascript">
    $(document).ready(function()
    {
        $(document).off("click","#sale-items-table .btn-minus");
        $(document).on("click","#sale-items-table .btn-minus", function(event) {
            event.preventDefault();

            /*
            var qtyRef = $(this).attr('href');
            var qty = $(qtyRef).html();
            qty--;
            if(qty<1) qty = 1;
            $(qtyRef).html(qty);

            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            if(sale_items!=null)
            {
                var row = qtyRef.replace("#","");

                sale_items[row].qty =  qty;
                $("#sale-items-table").attr('data-json', JSON.stringify(sale_items));
            }
            */


            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            if(sale_items==null) return;

            var itemRef = $(this).attr('href');
            var row = itemRef.replace("#","");

            var delete_items = $("#sale-items-table").attr('delete-json');
            var delete_sale_items = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_sale_items = JSON.parse(delete_items);

            var rate = $("#exchange_rate_model").val();
            var exchange_rate = null;
            if(typeof rate != typeof undefined && rate!='' ) exchange_rate = JSON.parse(rate);

            var discount = $('#discount').val();

            var posting = $.post(
                '<?php echo base_url();?>sale/change_qty',
                { submit: 1, row: row, qty: -1, sale_items:sale_items, delete_sale_items: delete_sale_items, exchange_rate : exchange_rate, discount:discount },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#sale-items").empty().append(data);
                    }
                },
                'html'
            );

        });

        $(document).off("click","#sale-items-table .btn-plus");
        $(document).on("click","#sale-items-table .btn-plus", function(event) {
            event.preventDefault();

            /*
            var qtyRef = $(this).attr('href');
            var qty = $(qtyRef).html();
            qty++;
            $(qtyRef).html(qty);

            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            if(sale_items!=null)
            {
                var row = qtyRef.replace("#","");

                sale_items[row].qty =  qty;
                $("#sale-items-table").attr('data-json', JSON.stringify(sale_items));
            }
            */

            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            if(sale_items==null) return;

            var itemRef = $(this).attr('href');
            var row = itemRef.replace("#","");

            var delete_items = $("#sale-items-table").attr('delete-json');
            var delete_sale_items = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_sale_items = JSON.parse(delete_items);

            var rate = $("#exchange_rate_model").val();
            var exchange_rate = null;
            if(typeof rate != typeof undefined && rate!='' ) exchange_rate = JSON.parse(rate);

            var discount = $('#discount').val();

            var posting = $.post(
                '<?php echo base_url();?>sale/change_qty',
                { submit: 1, row: row, qty: 1, sale_items:sale_items, delete_sale_items: delete_sale_items, exchange_rate: exchange_rate, discount:discount },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#sale-items").empty().append(data);
                    }
                },
                'html'
            );

        });

        $(document).off("click","#sale-items-table .btn-remove");
        $(document).on("click","#sale-items-table .btn-remove", function(event) {
            event.preventDefault();

            var items = $("#sale-items-table").attr('data-json');
            var sale_items = null;
            if(typeof items != typeof undefined && items!='' ) sale_items = JSON.parse(items);

            if(sale_items==null) return;

            var itemRef = $(this).attr('href');
            var row = itemRef.replace("#","");

            var delete_items = $("#sale-items-table").attr('delete-json');
            var delete_sale_items = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_sale_items = JSON.parse(delete_items);

            var rate = $("#exchange_rate_model").val();
            var exchange_rate = null;
            if(typeof rate != typeof undefined && rate!='' ) exchange_rate = JSON.parse(rate);

            var discount = $('#discount').val();

            var posting = $.post(
                '<?php echo base_url();?>sale/remove_item',
                { submit: 1, row: row, sale_items:sale_items, delete_sale_items: delete_sale_items, exchange_rate: exchange_rate, discount:discount },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#sale-items").empty().append(data);
                    }
                },
                'html'
            );
        });

    });
</script>