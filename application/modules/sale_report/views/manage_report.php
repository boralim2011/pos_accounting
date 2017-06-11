<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Sale Report
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Sale Report</li>
    </ol>
</section>
<!-- Main content -->
<section class="content no-margin-height">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">

            <?php
                $this->load->view("sale_report/search_info");
            ?>

            <div class="box box-info">
                <div class="box-header">
<!--                    <div class="row">-->
<!--                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">-->
<!--                            <label>Select Report</label>-->
<!--                            <select id="report_name" name="report_name" class="form-control select2" data-placeholder="--Select Report--"  style="width: 100%; display: none;">-->
<!--                                <option value="register_list_thai_report" --><?php //echo isset($report_name) && $report_name=='register_list_thai_report' ? 'selected="selected"':'';?><!-- >Register List (Thai)</option>-->
<!--                                <option value="name_list_thai_report" --><?php //echo isset($report_name) && $report_name=='name_list_thai_report' ? 'selected="selected"':'';?><!-- >Name List (Thai)</option>-->
<!--                                <option value="name_list_thai_director_report" --><?php //echo isset($report_name) && $report_name=='name_list_thai_director_report' ? 'selected="selected"':'';?><!-- >Name List (Thai) for Director</option>-->
<!--                                <option value="mfa_list_thai_report" --><?php //echo isset($report_name) && $report_name=='mfa_list_thai_report' ? 'selected="selected"':'';?><!-- >MFA List(Thai)</option>-->
<!--                                <option value="mfa_list_thai_director_report" --><?php //echo isset($report_name) && $report_name=='mfa_list_thai_director_report' ? 'selected="selected"':'';?><!-- >MFA (Thai) for Director</option>-->
<!--                                -->
<!--                            </select>-->
<!--                        </div>-->
                        <div class="form-group col-xs-12 col-sm-6 col-md-4 col-lg-4">
<!--                            <label>Report No</label>-->
<!--                            <div class="input-group">-->
<!--                                <input type="text" class="form-control" id="report_no" name="report_no" placeholder="" value="--><?php //echo isset($report_no)? $report_no:'';?><!--"/>-->
                                <a class=" btn btn-primary" id="btn-print" href="#">
                                    <i class="fa fa-print"></i>
                                </a>
<!--                            </div>-->
                        </div>
<!--                    </div>-->
                </div><!-- /.box-header -->
                <div class="box-body" id="display-list">

                    <?php
                        $this->load->view("sale_report/sale_report");
                    ?>

                </div><!-- /.box-body -->
            </div><!-- /.box -->


        </div><!-- /.col -->
    </div><!-- /.row -->

</section><!-- /.content -->



<script type="text/javascript">

    $(document).ready(function() {

        $('#search').keyup(function (e)
        {
            if(e.keyCode==13) post_search();
        });

        $(document).off("click", "#btn-search");
        $(document).on("click", "#btn-search", function (event) {
            event.preventDefault();

            post_search();
        });

        $(document).off('submit', '#search-form');
        $(document).on('submit', '#search-form', function(event){
            event.preventDefault();

            post_search();
        });

        $(document).off('change', '#report_name');
        $(document).on('change', '#report_name', function(event){
            event.preventDefault();

            post_search();
        });

        function post_search()
        {
            var url = "<?php echo base_url()?>sale_report";

            var data = $("#search-form").serializeArray();
            data.push({name:'submit', value: 1});
            //data.push({name:'report_name', value: $("#report_name").val()});
            //data.push({name:'report_no', value: $("#report_no").val()});

            $.post(url, data , function(data, status, xhr)
            {
                alert(data); return;
                if (data == 521) {
                    go_to_login();
                }
                else {
                    $("#display-content").empty().append(data);
                }
            });

        }

        $(document).off("click", "#btn-print");
        $(document).on("click", "#btn-print", function(event){
            event.preventDefault();

            var data = $("#search-form").serializeArray();
            data.push({name:'submit', value: 1});
            //data.push({name:'report_name', value: $("#report_name").val()});
            //data.push({name:'report_no', value: $("#report_no").val()});
            data.push({name:'print', value: 1});

            var url = '<?php echo base_url()?>sale_report/manage_report';

            openWindow(url, data);
        })

    });

    var popup_data;
    function openWindow(url, data) {
        $.post(url, data, function(result) {
            popup_data = result;
            window.open('<?php echo base_url().'application/views/popup.html';?>', 'Print Report','height='+screen.height+', width='+screen.width+', left=-1, top=-1, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes');
        });
    }

</script>




