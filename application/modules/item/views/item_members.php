<table id="item-members-table"
       class="table table-bordered table-hover data-table"
       data-json='<?php echo isset($item_members) && is_array($item_members)? json_encode($item_members):"";?>'
       delete-json='<?php echo isset($delete_item_members) && is_array($delete_item_members)? json_encode($delete_item_members):"";?>'
    >
    <thead>
        <tr>
            <th >#</th>
            <th >Item Code</th>
            <th >Item Name</th>
            <th >Qty</th>
            <th >Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if(isset($item_members) && is_array($item_members)){
            $no=0;
            foreach($item_members as $row){
                $no ++;
                ?>
                <tr>
                    <td><?php echo $no;?></td>
                    <td><?php echo isset($row->member_code)? $row->member_code:"";?></td>
                    <td><?php echo isset($row->member_name)? $row->member_name:"";?></td>
                    <td><span id="<?php echo $no;?>" style="width:30px; display: inline-block;text-align: right"><?php echo isset($row->qty)? $row->qty:"";?></span>
                        <a href="#<?php echo $no;?>" class="btn-minus"><i class="fa fa-minus-square" style="color:#dd0033; margin: 0 3px;"></i> </a>
                        <a href="#<?php echo $no;?>" class="btn-plus"><i class="fa fa-plus-square" style="color:green; margin: 0 3px;"></i> </a>
                    </td>
                    <td>
                        <!--<a href="#" data-json='{"member_id":"<?php //echo $row->member_id;?>"}' class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>-->
                        <a href="#<?php echo $no;?>" data-json='{"member_id":"<?php echo $row->member_id;?>"}' class="inline-button btn-remove" data-toggle="tooltip" title="Remove" url="<?php echo base_url();?>item/delete_member"> <i class="fa fa-trash-o text-red"></i> </a>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
    </tbody>

</table>


<script type="text/javascript">
    $(document).ready(function()
    {
        $(document).off("click","#item-members-table .btn-minus");
        $(document).on("click","#item-members-table .btn-minus", function(event) {
            event.preventDefault();

            var qtyRef = $(this).attr('href');

            var qty = $(qtyRef).html();
            qty--;
            if(qty<1) qty = 1;

            $(qtyRef).html(qty);

            var items = $("#item-members-table").attr('data-json');

            var item_members = null;
            if(typeof items != typeof undefined && items!='' ) item_members = JSON.parse(items);

            if(item_members!=null)
            {
                var row = qtyRef.replace("#","");

                item_members[row].qty =  qty;
                $("#item-members-table").attr('data-json', JSON.stringify(item_members));
            }

        });

        $(document).off("click","#item-members-table .btn-plus");
        $(document).on("click","#item-members-table .btn-plus", function(event) {
            event.preventDefault();

            var qtyRef = $(this).attr('href');
            var qty = $(qtyRef).html();
            qty++;

            $(qtyRef).html(qty);

            var items = $("#item-members-table").attr('data-json');
            var item_members = null;
            if(typeof items != typeof undefined && items!='' ) item_members = JSON.parse(items);

            if(item_members!=null)
            {
                var row = qtyRef.replace("#","");

                item_members[row].qty =  qty;
                $("#item-members-table").attr('data-json', JSON.stringify(item_members));
            }

        });

        $(document).off("click","#item-members-table .btn-remove");
        $(document).on("click","#item-members-table .btn-remove", function(event) {
            event.preventDefault();

            var items = $("#item-members-table").attr('data-json');
            var item_members = null;
            if(typeof items != typeof undefined && items!='' ) item_members = JSON.parse(items);

            if(item_members==null) return;

            var itemRef = $(this).attr('href');
            var row = itemRef.replace("#","");

            var delete_items = $("#item-members-table").attr('delete-json');
            var delete_item_members = null;
            if(typeof delete_items != typeof undefined && delete_items!='' ) delete_item_members = JSON.parse(delete_items);

            var posting = $.post(
                '<?php echo base_url();?>item/remove',
                { submit: 1, row: row, item_members:item_members, delete_item_members: delete_item_members },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#item-members").empty().append(data);
                    }
                },
                'html'
            );
        });

    });
</script>