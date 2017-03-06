<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Manage Company
        <!--<small>Optional description</small>-->
    </h1>
    <ol class="breadcrumb">
    <li><a href="#home"><i class="fa fa-home"></i> Home</a></li>
    <li class="active">Manage Company</li>
    </ol>
</section>
<!-- Main content -->
<section class="content no-margin-height">

    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header">
                    <h4>Search info :</h4>
                    <form id="search-form" role="form" action="<?php echo base_url();?>company" method="post" accept-charset="utf-8">
                        <div class="row">
<!--                            <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">-->
<!--                                <select id="company_type_id" name="company_type_id" class="form-control select2" data-placeholder="Member type"  style="width: 100%;">-->
<!--                                    <option></option>-->
<!--                                    <option value="0" --><?php //echo isset($company_type_id) && $company_type_id==0 ? 'selected="selected"':'';?><!-- >All</option>-->
<!--                                    --><?php //if(isset($company_types) && is_array($company_types))
//                                        foreach($company_types as $ut){
//                                            ?>
<!--                                            <option value="--><?php //echo $ut->company_type_id;?><!--" --><?php //echo isset($company_type_id) && $company_type_id==$ut->company_type_id? 'selected="selected"':'';?><!-- >--><?php //echo $ut->company_type_name;?><!--</option>-->
<!--                                            --><?php
//                                        }
//                                    ?>
<!--                                </select>-->
<!--                            </div>-->
                            <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Company name" value="<?php echo isset($contact_name)? $contact_name:'';?>">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php echo isset($email)? $email:'';?>"/>

                                    <a class="input-group-addon btn btn-primary" name="btn-search" id="btn-search" href="#">
                                        <i class="fa fa-search"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <a href="#company/add" class="btn btn-primary" ><i class="fa fa-plus"></i> Add</a>
                    <a href="#company" class="btn btn-primary btn-refresh"><i class="fa fa-refresh"></i> Refresh</a>
                </div><!-- /.box-header -->
                <div class="box-body" id="display-list">
                    <table id="company-table" class="table table-bordered table-hover data-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($companies) && is_array($companies)){
                            foreach($companies as $row){
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo isset($row->contact_name)? $row->contact_name:"";?></td>
                                    <td><?php echo isset($row->email)? $row->email:"";?></td>
                                    <td><?php echo isset($row->phone_number)? $row->phone_number:"";?></td>
                                    <td><?php echo isset($row->created_date)? $row->created_date:"";?></td>
                                    <td>
                                        <a href="#company/edit/<?php echo $row->contact_id;?>" data-json='<?php echo json_encode($row);?>' class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>
                                        <a href="#" data-json='<?php echo json_encode($row);?>' class="inline-button btn-delete" data-toggle="tooltip" title="Delete" url="<?php echo base_url();?>company/delete"> <i class="fa fa-trash-o text-red"></i> </a>
                                    </td>
                                </tr>
                            <?php
                            }
                        }
                        ?>
                        </tbody>

                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->


        </div><!-- /.col -->
    </div><!-- /.row -->

</section><!-- /.content -->



<script type="text/javascript">

    $(document).ready(function(){

        var t = $('#company-table').DataTable( {
            "scrollX": true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets": 0
            } ],
            "order": [[ 1, 'asc' ]]//,
            //"createdRow": function ( row, data, index ) {

            //    var contact_id =  0; row[index].contact_id;

            //    var btn_delete = '<a href="#" onclick="delete_company('+ contact_id +')" class="inline-button" data-toggle="tooltip" title="Delete"> <i class="fa fa-trash-o text-red"></i> </a>';
            //    var btn_edit = '<a href="#" onclick="edit_company('+contact_id+')" class="inline-button" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil text-orange"></i> </a>';

            //    $('td', row).eq(5).html(btn_edit + " " + btn_delete);
            //    if(data[3] > 1){
            //        $('td', row).eq(3).addClass('blue text-bold');
            //    }
            //}
        } );

        t.on( 'order.dt search.dt', function () {
            t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();


        $(".select2").select2();

    });

</script>

<script type="text/javascript">

    $(document).ready(function(){

        //delete company type
        $(document).off("click",".btn-delete");
        $(document).on("click",".btn-delete", function(event){
            event.preventDefault();

            var btn = $(this);

            confirm_message('Are you sure to delete');

            $(document).off("click", "#dialog-confirm #btn-ok");
            $(document).on("click", "#dialog-confirm #btn-ok", function(event){
                event.preventDefault();

                $("#dialog-confirm").modal("hide");

                var model = JSON.parse(btn.attr('data-json'));
                var formData = new FormData();
                formData.append('submit', 1);
                formData.append('contact_id', model.contact_id);

                $.ajax({
                    //url: '<?php //echo base_url();?>company_group/delete',
                    url: btn.attr("url"),
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
                            if(data.success===true)
                            {
                                if ($(".btn-refresh").length) $(".btn-refresh").trigger('click');
                            }
                            show_message(data.message, $("#message"));
                        }
                    },
                    error: function(xhr,status,error){
                        //alert('error'+ error);
                        var message = '{"text":"'+error+'","type":"Error","title":"Error"}';
                        show_message(message, $("#message"));
                    },
                    complete: function(xhr,status){
                        //alert(status);
                    },
                    contentType: false,
                    processData: false
                });
            });

        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){

        $(document).off('click', '#btn-search');
        $(document).on('click', '#btn-search', function(event){
            event.preventDefault();

            if($('#search-form').length) $('#search-form').submit();
        });


        $(document).off('submit', '#search-form');
        $(document).on('submit', '#search-form', function(event){
            event.preventDefault();

            var form = $(this);
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
                //dataType:"json",
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
                        $("#display-content").empty().append(data);
                    }
                },
                error: function(xhr,status,error){
                    //alert('error'+ error);
                    var message = '{"text":"'+error+'","type":"Error","title":"Error"}';
                    show_message(message, $("#message"));
                },
                complete: function(xhr,status){
                    //alert(status);
                },
                contentType: false,
                processData: false
            });
        });
    });
</script>
