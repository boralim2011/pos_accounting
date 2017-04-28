<div class="row content">

    <div class="row">
        <div class="form-group ccol-lg-6 col-sm-6 col-xs-12">
            <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                <option value="0" <?php echo isset($search_by) && $search_by=="" ? 'selected="selected"':'';?> >All Type</option>
                <option value="1" <?php echo isset($search_by) && $search_by=="1" ? 'selected="selected"':'';?> >Normal</option>
                <option value="2" <?php echo isset($search_by) && $search_by=="2" ? 'selected="selected"':'';?> >VIP</option>
            </select>
        </div>
        <div class="form-group col-lg-6 col-sm-6 col-xs-12">
            <div class="input-group">
                <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="<?php echo isset($search)? $search : "";?>"/>
                <a class="input-group-addon btn btn-primary" name="btn-search" id="btn-search" href="#">
                    <i class="fa fa-search"></i>
                </a>
            </div>
        </div>
    </div>


    <div class="row" >
        <div class="col-lg-12">
            <ul class="app-menu">
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-default">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">ItemAItemA ItemAItemA ItemAItemA</span>
                        <span class="price">$100.00</span>
                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-default">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>
                        <span class="price">$100.00</span>
                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-default">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>
                        <span class="price">$100.00</span>
                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-default">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>
                        <span class="price">$100.00</span>
                    </a>
                </li>

            </ul>

            <div class="clearfix"></div>
        </div>
    </div>

    <div class="row" >
        <div class="col-lg-12">
            <ul class="app-menu">
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-default">
                        <img src="http://localhost:8080/pos_accounting/files/items/ddd.png"/>
                        <span class="caption">Item AItem AItem AItem AItem AItem AItem AItem A</span>
                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-primary">
                        <img src="http://localhost:8080/pos_accounting/files/items/ddd.png"/>
                        <span class="caption">Item AItem AItem AItem AItem AItem AItem AItem A</span>
                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-info">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>

                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-success">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>

                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-warning">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>

                    </a>
                </li>
                <li data-toggle="tooltip" title="Item A (#00001)">
                    <a href="<?php echo base_url("home");?>" class="btn app-menu-btn btn-flat btn-danger">
                        <img src="http://localhost:8080/pos_accounting/files/item_no_image.png"/>
                        <span class="caption">Item A</span>

                    </a>
                </li>
            </ul>

            <div class="clearfix"></div>
        </div>
    </div>
  
</div>

<script type="text/javascript">

    $(document).ready(function()
    {
        $('#search').keyup(function() {
            clearTimeout($.data(this, 'timer'));
            var wait = setTimeout(search, 500);
            $(this).data('timer', wait);
        });

        function search() {

            var keyword = $('#search').val();

            if(keyword.length) alert(keyword);

//            $.post("stuff.php", {nStr: "" + $('#mySearch').val() + ""}, function(data){
//                if(data.length > 0) {
//                    $('#suggestions').show();
//                    $('#autoSuggestionsList').html(data);
//                }else{
//                    $('#suggestions').hide();
//                }
//            });
        }
    });

</script>