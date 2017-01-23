
<?php $this->load->view('location/new_location'); ?>

<script type="text/javascript">

    $(document).ready(function()
    {
        //add country
        $(document).off("click", ".btn-new-country>");
        $(document).on("click", ".btn-new-country", function (event) {
            event.preventDefault();

            $("#location-form").attr('action', '<?php echo base_url()?>location/add');
            $("#location_id").val(0);

            $('#location_type').empty().append('<option></option><option value="1" selected="selected">Country</option>');
            $('#parent_location').empty().append('<option></option><option value="1" selected="selected">None</option>');

            initialize_select2();

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //add country
        $(document).off("click", ".btn-new-province");
        $(document).on("click", ".btn-new-province", function (event) {
            event.preventDefault();

            $("#location-form").attr('action', '<?php echo base_url()?>location/add');
            $("#location_id").val(0);

            $('#location_type').empty().append('<option></option><option value="2" selected="selected">Province</option>');
            $('#parent_location').empty().append('<option></option><option value="2" selected="selected">Cambodia</option>');

            initialize_select2();

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //add country
        $(document).off("click", ".btn-new-district");
        $(document).on("click", ".btn-new-district", function (event) {
            event.preventDefault();

            $("#location-form").attr('action', '<?php echo base_url()?>location/add');
            $("#location_id").val(0);

            $('#location_type').empty().append('<option></option><option value="4" selected="selected">District</option>');
            $('#parent_location').empty().append('<option></option>');

            initialize_select2();

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })


        //add country
        $(document).off("click", ".btn-new-commune");
        $(document).on("click", ".btn-new-commune", function (event) {
            event.preventDefault();

            $("#location-form").attr('action', '<?php echo base_url()?>location/add');
            $("#location_id").val(0);

            $('#location_type').empty().append('<option></option><option value="7" selected="selected">Commune</option>');
            $('#parent_location').empty().append('<option></option>');

            initialize_select2();

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

        //add village
        $(document).off("click", ".btn-new-village");
        $(document).on("click", ".btn-new-village", function (event) {
            event.preventDefault();

            $("#location-form").attr('action', '<?php echo base_url()?>location/add');
            $("#location_id").val(0);

            $('#location_type').empty().append('<option></option><option value="8" selected="selected">Village</option>');
            $('#parent_location').empty().append('<option></option>');

            initialize_select2();

            $("#dialog-location").modal({
                backdrop: "static" // true | false | "static" => default is true
            });
        })

    });
</script>