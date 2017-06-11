
    <label><a href="#" id="btn-search-items"> Search Items <i class="fa fa-search"></i></a></label>
    <div id="search-items">
        <div class="row">
            <div class="form-group col-lg-4 col-sm-4 col-xs-6">
                <select id="item_group" name="item_group" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                    <option value="0" <?php echo isset($item_group_id) && $item_group_id==0 ? 'selected="selected"':'';?> >All Group</option>
                    <?php if(isset($item_groups) && is_array($item_groups))
                        foreach($item_groups as $ut){
                            ?>
                            <option value="<?php echo $ut->item_group_id;?>" <?php echo isset($item_group_id) && $item_group_id==$ut->item_group_id? 'selected="selected"':'';?> ><?php echo $ut->item_group_name;?></option>
                        <?php
                        }
                    ?>
                </select>
                <input type="hidden" id="item_type" name="item_type" value="<?php echo isset($item_type_id)? $item_type_id:0;?>" />
            </div>
            <div class="form-group col-lg-4 col-sm-4 col-xs-6">
                <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                    <option value="item_code" <?php echo isset($search_by) && $search_by=="item_code" ? 'selected="selected"':'';?> >Item Code</option>
                    <option value="item_name" <?php echo isset($search_by) && $search_by=="item_name" ? 'selected="selected"':'';?> >Item Name</option>
                    <option value="item_name_kh" <?php echo isset($search_by) && $search_by=="item_name_kh" ? 'selected="selected"':'';?> >Item Name KH</option>
                    <option value="barcode" <?php echo isset($search_by) && $search_by=="barcode" ? 'selected="selected"':'';?> >Barcode</option>
                </select>
            </div>
            <div class="form-group col-lg-4 col-sm-4 col-xs-12">
                <div class="input-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search" value="<?php echo isset($search)? $search : "";?>"/>
                    <a class="input-group-addon btn btn-primary" name="btn-search" id="btn-search" href="#">
                        <i class="fa fa-search"></i>
                    </a>
                </div>
            </div>
        </div>

        <div id="search_result">
            <?php $this->load->view("item/search_item_result");?>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            //Load search items
            $(document).off("click", "#btn-search-items");
            $(document).on("click", "#btn-search-items", function (event) {
                event.preventDefault();

                $("#search-items").toggle('show');
            });

            //$("#search-items").hide();

        });
    </script>