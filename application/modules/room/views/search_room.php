<div class="row content">

    <div class="row">
        <div class="form-group col-lg-3 col-sm-3 col-xs-6">
            <select id="room_type_id" name="room_type_id" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                <option value="0" <?php echo isset($room_type_id) && $room_type_id==0 ? 'selected="selected"':'';?> >All Type</option>
                <?php if(isset($room_types) && is_array($room_types))
                    foreach($room_types as $ut){
                        ?>
                        <option value="<?php echo $ut->room_type_id;?>" <?php echo isset($room_type_id) && $room_type_id==$ut->room_type_id? 'selected="selected"':'';?> ><?php echo $ut->room_type_name;?></option>
                    <?php
                    }
                ?>
            </select>
        </div>
        <div class="form-group col-lg-3 col-sm-3 col-xs-6">
            <select id="search_by" name="search_by" class="form-control select2" data-placeholder="Search By"  style="width: 100%;">
                <option value="room_name" <?php echo isset($search_by) && $search_by=="1" ? 'selected="selected"':'';?> >Room Name</option>
                <option value="room_name_kh" <?php echo isset($search_by) && $search_by=="2" ? 'selected="selected"':'';?> >Room Name KH</option>
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

    <div id="search_result">

        <?php if(!$result_view) $this->load->view("room/search_room_result"); ?>

    </div>


</div>
