<div class="box box-info">
    <div class="box-header">
        <h4>Search info :</h4>
        <form id="search-form" role="form" action="#" method="post" accept-charset="utf-8">
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div>
                        <label style="margin-right: 20px;">Date of</label>
                        <label><input type="checkbox" class="minimal form-control" id="all_date" name="all_date" value="1" <?php if(isset($all_date) && $all_date=='1') echo 'checked="checked"';?>  > All Date</label>
                    </div>
                    <select id="date_of" name="date_of" class="form-control select2" data-placeholder="--Select Agency--"  style="width: 100%;">
                        <option value="register_date" <?php echo isset($date_of) && $date_of=='journal_date' ? 'selected="selected"':'';?> >Sale Date</option>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label>From Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="<?php echo isset($from_date)?$from_date:'' ;?>" <?php if(isset($all_date) && $all_date=='1') echo 'disabled="disabled"';?> >
                    </div>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label>To Date</label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="<?php echo isset($to_date)?$to_date:'' ;?>" <?php if(isset($all_date) && $all_date=='1') echo 'disabled="disabled"';?> >
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label>Cart Type</label>
                    <select id="card_type_id" name="card_type_id" class="form-control select2" data-placeholder="--Select Cart Type--"  style="width: 100%;">
                        <option value="0" <?php echo isset($card_type_id) && $card_type_id==0 ? 'selected="selected"':'';?> >All</option>
                        <?php if(isset($card_types) && is_array($card_types))
                            foreach($card_types as $ct){
                                ?>
                                <option value="<?php echo $ct->card_type_id;?>" <?php echo isset($card_type_id) && $card_type_id==$ct->card_type_id? 'selected="selected"':'';?> ><?php echo $ct->card_type_name;?></option>
                            <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label>Card</label>
                    <select id="card_id" name="card_id" class="form-control select2" data-placeholder="--Select Card--"  style="width: 100%;">
                        <option value="0" <?php echo isset($card_id) && $card_id==0 ? 'selected="selected"':'';?> >All</option>
                        <?php if(isset($cards) && is_array($cards))
                            foreach($cards as $c){
                                ?>
                                <option value="<?php echo $c->card_id;?>" <?php echo isset($card_id) && $card_id==$a->card_id? 'selected="selected"':'';?> ><?php echo $c->card_name;?></option>
                            <?php
                            }
                        ?>
                    </select>
                </div>

                <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <label>User</label>
                    <select id="created_by" name="created_by" class="form-control select2" data-placeholder="--Select User--"  style="width: 100%;">
                        <option value="0" <?php echo isset($created_by) && $created_by==0 ? 'selected="selected"':'';?> >All</option>
                        <?php if(isset($created_bys) && is_array($created_bys))
                            foreach($created_bys as $cb){
                                ?>
                                <option value="<?php echo $cb->user_id;?>" <?php echo isset($created_by) && $created_by==$cb->$user_id? 'selected="selected"':'';?> ><?php echo $cb->user_name;?></option>
                            <?php
                            }
                        ?>
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="form-group col-xs-12 col-sm-5 col-md-4 col-lg-4">
                    <label>Search By</label>
                    <select id="search_by" name="search_by" class="form-control select2" data-placeholder="--Select Agency--"  style="width: 100%;">
                        <option value="contact_name" <?php echo isset($search_by) && $search_by=='journal_no' ? 'selected="selected"':'';?> >Sale No</option>
                        <option value="contact_code" <?php echo isset($search_by) && $search_by=='note' ? 'selected="selected"':'';?> >Note</option>
                    </select>
                </div>
                <div class="form-group col-xs-12 col-sm-7 col-md-8 col-lg-8">
                    <div>
                        <label style="margin-right: 20px;"><input type="radio" class="minimal form-control" id="like" name="search_option" value="like" <?php if(!isset($search_option) || $search_option=='like') echo 'checked="checked"';?>  > Like</label>
                        <label style="margin-right: 20px;"><input type="radio" class="minimal form-control" id="start_with" name="search_option" value="start_with" <?php if(isset($search_option) && $search_option=='start_with') echo 'checked="checked"';?>  > Start With</label>
                        <label style="margin-right: 20px;"><input type="radio" class="minimal form-control" id="exact" name="search_option" value="exact" <?php if(isset($search_option) && $search_option=='exact') echo 'checked="checked"';?>  > Exact</label>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Enter something to search" value="<?php echo isset($search)? $search:'';?>"/>
                        <a class="input-group-addon btn btn-primary" name="btn-search" id="btn-search" href="#">
                            <i class="fa fa-search"></i>
                        </a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        $(".select2").select2();

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });
        //Red color scheme for iCheck
        $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            checkboxClass: 'icheckbox_minimal-red',
            radioClass: 'iradio_minimal-red'
        });
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        //date picker
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"
        });

        //Timepicker
        $(".timepicker").timepicker({
            showInputs: false
        });

        $('#all_date').on('ifChecked', function (event) {
            $('#from_date').attr("disabled", true);
            $('#to_date').attr("disabled", true);
        });
        $('#all_date').on('ifUnchecked', function (event) {
            $('#from_date').attr("disabled", false);
            $('#to_date').attr("disabled", false);
        });


    });

</script>



