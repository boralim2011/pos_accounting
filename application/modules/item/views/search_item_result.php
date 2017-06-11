
        <div class="row" >
            <div class="col-lg-12">
                <ul class="app-menu">

                    <?php if(isset($items) && is_array($items)){
                        foreach($items as $row){
                            $image = $row->get_image();
                            $image = $image!="" ? $image: $blank_item;
                            ?>
                            <li data-toggle="tooltip" title="<?php echo isset($row->item_name)? $row->item_name:"";?>">
                                <!--<a href="#item" data-json='{"item_id":"<?php //echo "$row->item_id";?>"}' class="btn app-menu-btn btn-flat btn-warning">-->
                                <a href="#" data-json='<?php echo json_encode($row);?>' class="btn app-menu-btn btn-flat btn-default btn-choose-item">
                                    <img src="<?php echo $image;?>"/>
                                    <span class="caption"><?php echo isset($row->item_name)? $row->item_name:"";?></span>
                                    <span class="price"><?php echo isset($row->selling_price)? $row->selling_price:"";?></span>
                                </a>
                            </li>
                        <?php
                        }
                    }
                    ?>
                </ul>

                <div class="clearfix"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-3">
                <select id="display" name="display"  data-placeholder="10" style="margin-top: 20px; padding: 5px;" >
                    <option value="5" <?php echo $display==5? 'selected="selected"':''; ?>>5</option>
                    <option value="10" <?php echo $display==10? 'selected="selected"':''; ?>>10</option>
                    <option value="20" <?php echo $display==20? 'selected="selected"':''; ?> >20</option>
                    <option value="30" <?php echo $display==30? 'selected="selected"':''; ?>>30</option>
                    <option value="50" <?php echo $display==50? 'selected="selected"':''; ?>>50</option>
                    <option value="100" <?php echo $display==100? 'selected="selected"':''; ?>>100</option>
                    <option value="200" <?php echo $display==200? 'selected="selected"':''; ?>>200</option>
                    <option value="300" <?php echo $display==300? 'selected="selected"':''; ?>>300</option>
                    <option value="500" <?php echo $display==500? 'selected="selected"':''; ?>>500</option>
                </select>
            </div>
            <div class="col-xs-9">
                <div id="page-selection" class="pull-right"> </div>
                <div style="margin-top: 20px; padding: 5px 20px;" class="pull-right">
                    Total:  <span style="font-weight: bold;"> <?php echo $records; ?> </span> records
                </div>
            </div>
        </div>



<script type="text/javascript">

    $(document).ready(function()
    {
        $(document).off("keyup", "#search");
        $(document).on("keyup",'#search', function() {
            clearTimeout($.data(this, 'timer'));
            var wait = setTimeout(post_search, 500);
            $(this).data('timer', wait);
        });

        //$('#search').keyup(function (e)
        //{
        //    if(e.keyCode==13) post_search();
        //});

        $(document).off("change", "#item_group");
        $(document).on("change", "#item_group",function(event){
            event.preventDefault();

            post_search();
        });

        $(document).off("click","#btn-search");
        $(document).on("click","#btn-search", function(event){
            event.preventDefault();

            post_search();
        });

        $(document).off('change','#display');
        $(document).on('change','#display', function(event){
            event.preventDefault();

            post_search();
        });

        //Pagination
        $('#page-selection').bootpag({
            total: <?php echo $pages;?>,
            page: <?php echo $page;?>,
            maxVisible: 5,
            leaps: true,
            firstLastUse: true,
            //first: '<span aria-hidden="true">&larr;</span>',
            //last: '<span aria-hidden="true">&rarr;</span>',
            first:'<i class="fa fa-fast-backward" style="font-size:10px;"></i>',
            last:'<i class="fa fa-fast-forward" style="font-size:10px;"></i>',
            prev:'<i class="fa fa-backward" style="font-size:10px;"></i>',
            next:'<i class="fa fa-forward" style="font-size:10px;"></i>',
            wrapClass: 'pagination',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on("page", function(event, num){
            event.preventDefault();

            post_search( num);

            return false;
        });


        function post_search( page=1)
        {
            var count = $("#display").val();
            var search = $("#search").val();
            var search_by = $("#search_by").val();
            var item_group_id = $("#item_group").val();
            var item_type_id = $("#item_type").val();

            var url = "<?php echo base_url()?>item/search";

            var posting = $.post(
                url,
                { submit: 1, item_type_id:item_type_id, item_group_id:item_group_id, search_by:search_by, search: search, page: page, display: (count ? count : 5) },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        //$("#display-content").empty().append(data);
                        $("#search_result").empty().append(data);
                        //alert("");
                    }
                }
            );
        }

        /*
        $(document).off("click",".btn-choose-item");
        $(document).on("click",".btn-choose-item", function(event){
            event.preventDefault();

            var model = JSON.parse($(this).attr('data-json'));
            //var formData = new FormData();
            //formData.append('submit', 1);
            //formData.append('item_id', model.item_id);
            //formData.append('lot_id', model.default_lot_id);

            var url = "<?php echo base_url()?>sale/choose_item";

            var posting = $.post(
                url,
                { ajax: 1, item_id:model.item_id, lot_id:model.default_lot_id, item_model : model },
                function (data, status, xhr) {
                    if (data == 521) {
                        go_to_login();
                    }
                    else {
                        $("#sale-items").empty().append(data);
                    }
                }
            );
        });
        */

    });


</script>