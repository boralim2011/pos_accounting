
            <?php
                $i = isset($index)?$index:'';
                $var_name = isset($var_name)? $var_name:'address';
                $add = $$var_name;
            ?>

            <div class="box-header with-border">
                <h3 class="box-title"><?php echo isset($contact_title)?$contact_title: 'Contact Address';?> :</h3>
                <div class="box-tools pull-right">
                    <!--<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
                    <!--<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                </div>
            </div>
            <div class="box-body">

                <div class="row">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label>Address</label>
                        <textarea id="address" name="address" class="form-control"><?php echo $add->address;?></textarea>
                    </div>
                </div>

                <div class="row">
<!--                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--                        <label for="house_no--><?php //echo $i;?><!--">House No.</label>-->
<!--                        <div class="input-group">-->
<!--                            <div class="input-group-addon">-->
<!--                                #-->
<!--                            </div>-->
<!--                            <input type="text" class="form-control" id="house_no--><?php //echo $i;?><!--" name="house_no--><?php //echo $i;?><!--" placeholder="Ex: 9A" value="--><?php //echo $add->house_no; ?><!--">-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--                        <label for="street_no--><?php //echo $i;?><!--">Street No.</label>-->
<!--                        <div class="input-group">-->
<!--                            <div class="input-group-addon">-->
<!--                                @-->
<!--                            </div>-->
<!--                            <input type="text" class="form-control" id="street_no--><?php //echo $i;?><!--" name="street_no--><?php //echo $i;?><!--" placeholder="Ex: Norodom Blvd" value="--><?php //echo $add->street_no; ?><!--">-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Country</label>
                        <div class="input-group">
                            <select class="select2" id="country<?php echo $i;?>" name="country_id<?php echo $i;?>" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($add->country_id!=0){?>
                                    <option value="<?php echo $add->country_id; ?>" selected="selected"><?php echo $add->country;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary btn-new-country" data-toggle="tooltip" title="New Country">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Province/City</label>
                        <div class="input-group">
                            <select class="select2" id="province<?php echo $i;?>" name="province_city_id<?php echo $i;?>" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($add->province_city_id!=0){?>
                                    <option value="<?php echo $add->province_city_id; ?>" selected="selected"><?php echo $add->province;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary btn-new-province" data-toggle="tooltip" title="New Province">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>District/Khan</label>
                        <div class="input-group">
                            <select class="select2" id="district<?php echo $i;?>" name="district_khan_id<?php echo $i;?>" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($add->district_khan_id!=0){?>
                                    <option value="<?php echo $add->district_khan_id; ?>" selected="selected"><?php echo $add->district;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary btn-new-district" data-toggle="tooltip" title="New District">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Commune/Sangkat</label>
                        <div class="input-group">
                            <select class="select2" id="commune<?php echo $i;?>" name="commune_sangkat_id<?php echo $i;?>" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($add->commune_sangkat_id!=0){?>
                                    <option value="<?php echo $add->commune_sangkat_id; ?>" selected="selected"><?php echo $add->commune;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary btn-new-commune" data-toggle="tooltip" title="New Commune">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <label>Village</label>
                        <div class="input-group">
                            <select class="select2" id="village<?php echo $i;?>" name="village_id<?php echo $i;?>" data-placeholder="--Select--" style="width: 100%;">
                                <option></option>
                                <?php if($add->village_id!=0){?>
                                <option value="<?php echo $add->village_id; ?>" selected="selected"><?php echo $add->village;?></option>
                                <?php }?>
                            </select>
                            <a href="#" class="input-group-addon btn btn-primary btn-new-village" data-toggle="tooltip" title="New Village">
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                    </div>

                    <input type="hidden" id="address_id<?php echo $i;?>" name="address_id<?php echo $i;?>" value="<?php echo $add->address_id;?>" />

                </div>


            </div>

            <script type="text/javascript">

                $("#country<?php echo $i;?>").select2({
                    ajax: {
                        url: "<?php echo base_url()?>location/get_combobox_items",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term, // search term
                                type: '1',
                                parent: 0
                            };
                        },
                        processResults: function (data) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });

                $("#country<?php echo $i;?>").change(function(event){
                    //alert($(this).val());
                });

                $("#province<?php echo $i;?>").select2({
                    ajax: {
                        url: "<?php echo base_url()?>location/get_combobox_items",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {

                            var parent = $("#country<?php echo $i;?>").val();

                            return {
                                q: params.term, // search term
                                type: '2,3',
                                parent: parent ? parent : 0
                            };
                        },
                        processResults: function (data) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });

                $("#district<?php echo $i;?>").select2({
                    ajax: {
                        url: "<?php echo base_url()?>location/get_combobox_items",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {

                            var parent = $("#province<?php echo $i;?>").val();

                            return {
                                q: params.term, // search term
                                type: '4,5',
                                parent: parent ? parent : 0
                            };
                        },
                        processResults: function (data) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });

                $("#commune<?php echo $i;?>").select2({
                    ajax: {
                        url: "<?php echo base_url()?>location/get_combobox_items",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {

                            var parent = $("#district<?php echo $i;?>").val();

                            return {
                                q: params.term, // search term
                                type: '6,7',
                                parent: parent ? parent : 0
                            };
                        },
                        processResults: function (data) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });

                $("#village<?php echo $i;?>").select2({
                    ajax: {
                        url: "<?php echo base_url()?>location/get_combobox_items",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {

                            var parent = $("#commune<?php echo $i;?>").val();

                            return {
                                q: params.term, // search term
                                type: '8',
                                parent: parent ? parent : 0
                            };
                        },
                        processResults: function (data) {
                            // parse the results into the format expected by Select2.
                            // since we are using custom formatting functions we do not need to
                            // alter the remote JSON data
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });

            </script>


