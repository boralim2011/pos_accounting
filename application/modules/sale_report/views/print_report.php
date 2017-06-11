<!DOCTYPE html>
<html http://www.w3.org/1999/xhtml>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Print Report</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/dist/css/AdminLTE.min.css">

    <!-- Custom style-->
    <link rel="stylesheet" href="<?php echo base_url();?>template/style/custom_style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style type="text/css" media="print">
        @page
        {
            size: auto;  /* auto|A4 is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        @media print {
            html, body, th, td {
                /*width: 210mm;*/
                /*height: 297mm;*/
                font-size: 9px !important;
            }
        }

        /*@page{orphans:4; widows:2;}*/
        .page-break  { display: block; page-break-before: always; height: 60px; }

        html
        {
            background-color: #FFFFFF;
            margin: 0px;  /* this affects the margin on the html before sending to printer */
        }

        body
        {
            /*border: solid 1px blue ;*/
            margin: 10mm 10mm; /* margin you want for the content */
        }

    </style>


</head>
<body onload="window.print();">

<!--<div id="content">-->
<!--<div id="pageFooter"></div>-->
<!--multi-page content here...-->
<!--</div>-->

<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">

        <?php $this->load->view("sale_report/sale_report"); ?>

    </section><!-- /.content -->

</div><!-- ./wrapper -->

<!-- AdminLTE App -->
<script src="<?php echo base_url()?>template/dist/js/app.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


</body>
</html>
