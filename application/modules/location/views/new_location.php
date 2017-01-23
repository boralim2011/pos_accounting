<!--<div class="modal modal-success fade in" id="dialog-location">-->
<div class="modal fade in" id="dialog-location">
    <!--<div class="modal-dialog modal-sm">-->
    <!--<div class="modal-dialog modal-lg">-->
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                <h4 class="modal-title">New Location</h4>
            </div>
            <div class="modal-body">
                <form id="location-form" role="form" action="<?php echo base_url()?>location/add" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label>Location Type</label>
                        <select id="location_type" name="location_type" class="form-control select2" data-placeholder="Select Type"  style="width: 100%; display: none;">
                            <option></option>
                            <option value="9" >Location</option>
                            <option value="1" >Country</option>
                            <option value="2" >Province/City</option>
                            <option value="4" >District/Khan</option>
                            <option value="6" >Commune/Sangkat</option>
                            <option value="8" >Village</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="location_name">Location Name</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label for="location_name_kh">Location Name (Khmer)</label>
                        <input type="text" class="form-control" id="location_name_kh" name="location_name_kh" placeholder="Enter name (Khmer)">
                    </div>
                    <div class="form-group">
                        <label for="location_code">Location Code</label>
                        <input type="text" class="form-control" id="location_code" name="location_code" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label>Parent Location</label>
                        <select id="parent_location" name="parent_location" class="form-control select2" data-placeholder="Select Parent Location"  style="width: 100%; display: none;">
                            <option></option>
                            <option value="1" selected="selected">None</option>
                        </select>
                    </div><!-- /.form-group -->

                    <div class="clearfix"></div>

                    <input type="hidden" id="location_id" name="location_id" value="0"/>
                    <input type="hidden" id="is_deletable" name="is_deletable" value="1"/>

                </form>

            </div>
            <div class="modal-footer">
                <div class="form-group no-margin">


                    <button type="button" class="btn btn-primary pull-left" id="btn-add-location"><i class="fa fa-save"></i> Save</button>
                    <!--<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>-->
                    <button type="button" class="btn btn-default btn-close-location"><i class="fa fa-close"></i> Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
        <div id="modal-message"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

    $(document).ready(function(){
        initialize_select2();
    });

    function initialize_select2()
    {
        //$('#location_type').select2();
        //$('#parent_location').select2();

        //var data = [{ id: 0, text: 'enhancement' },
        //            { id: 1, text: 'bug' },
        //            { id: 2, text: 'duplicate' }];
        //$("#location_type").select2({ data: data});
        //$("#parent_location").select2({data: data});

        $("#location_type").select2({
//            ajax: {
//                url: "<?php //echo base_url()?>//location/get_location_types/0",
//                dataType: 'json',
//                delay: 250,
//                data: function (params) {
//                    return {
//                        q: params.term // search term
//                    };
//                },
//                processResults: function (data) {
//                    // parse the results into the format expected by Select2.
//                    // since we are using custom formatting functions we do not need to
//                    // alter the remote JSON data
//                    return {
//                        results: data
//                    };
//                },
//                cache: true
//            },
//            minimumInputLength: 1
        });

        $("#parent_location").select2({
            ajax: {
                url: "<?php echo base_url()?>location/get_combobox_items",
                dataType: 'json',
                delay: 250,
                data: function (params) {

                    var type = $('#location_type').val();
                    if(typeof type === typeof undefined) type = '9';

                    return {
                        q: params.term, // search term
                        type: type == 2 || type == 3 ? '1' :
                              type == 4 || type == 5 ? '2,3' :
                              type == 6 || type == 7 ? '4,5' :
                              type == 8 ? '6,7' : '9'
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
    }

</script>


<script type="text/javascript">

    $(document).ready(function(){

        $(".btn-close-location").click(function(){
            $("#dialog-location").modal("hide");
        });

        //add user type
        $(document).off("click","#btn-new-location");
        $(document).on("click","#btn-new-location", function(event){
            event.preventDefault();

            $("#dialog-location .modal-title").html("New Location");
            $("#location-form").attr('action', '<?php echo base_url()?>location/add' );
            $("#location_id").val(0);

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //clear form and refresh main page when close dialog
        $(document).off('hidden.bs.modal', '#dialog-location');
        $(document).on('hidden.bs.modal', '#dialog-location', function() {
            $("#location-form")[0].reset();
            //$("#modal-message").empty();
            //if($(".btn-refresh").length) $(".btn-refresh").trigger('click');
            if ($("#btn-search").length) $("#btn-search").trigger('click');
        });

        //submit user type to service to save
        $(document).off("click","#btn-add-location");
        $(document).on("click","#btn-add-location", function(event){
            event.preventDefault();

            var form = $("#location-form");
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
                            //$("#location-form")[0].reset();
                            $("#location_id").val(0);
                            $("#location_name").val("");
                            $("#location_name_kh").val("");
                            $("#location_code").val("");
                            $("#is_deletable").val(1);
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