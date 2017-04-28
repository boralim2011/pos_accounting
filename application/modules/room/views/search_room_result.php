
        <div class="row" >
            <div class="col-lg-12">
                <ul class="app-menu">

                    <?php if(isset($rooms) && is_array($rooms)){
                        foreach($rooms as $row){
                            $image = isset($row->image_path)? $row->image_path: $blank_item;
                            ?>
                            <li data-toggle="tooltip" title="<?php echo isset($row->room_name)? $row->room_name:"";?>">
                                <!--<a href="#" data-json='{"room_id":"<?php echo "$row->room_id";?>"}' class="btn app-menu-btn btn-flat btn-success btn-choose-room">-->
                                <a href="#item" data-json='{"room_id":"<?php echo "$row->room_id";?>"}' class="btn app-menu-btn btn-flat btn-success">
                                    <img src="<?php echo $image;?>"/>
                                    <span class="caption"><?php echo isset($row->room_name)? $row->room_name:"";?></span>

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
            <div class="col-xs-4">
                <select id="display" name="display"  data-placeholder="10" style="margin-top: 20px; padding: 5px;" >
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
            <div class="col-xs-8">
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

        $(document).off("change", "#room_type_id");
        $(document).on("change", "#room_type_id",function(event){
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
            var room_type_id = $("#room_type_id").val();

            var url = "<?php echo base_url()?>room/search/1";

            var posting = $.post(
                url,
                { ajax: 1, room_type_id:room_type_id, search_by:search_by, search: search, page: page, display: (count ? count : 10) },
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


        $(document).off("click",".btn-choose-room");
        $(document).on("click",".btn-choose-room", function(event){
            event.preventDefault();

            var model = JSON.parse($(this).attr('data-json'));
            var formData = new FormData();
            formData.append('submit', 1);
            formData.append('room_id', model.room_id);

            alert(model.room_id);
        });

    });


</script>