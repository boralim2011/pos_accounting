<!DOCTYPE html>
<html http://www.w3.org/1999/xhtml>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Print Invoice</title>
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


    <style type="text/css">
        @page
        {
            size: auto;  /* auto|A4 is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        @media print {
            html, body, th, td {
            }
        }

        /*@page{orphans:4; widows:2;}*/
        .page-break  { display: block; page-break-before: always; height: 60px; }


        .receipt{
            display: block;
            max-width: 7.8cm;
            min-width: 7.8cm;
            width: 7.8cm;
            margin: 10px auto;
            padding: 10px;
            font-family: "Arial";
            font-size: 8pt !important;
        }

        .receipt td, th{
            font-size: 8pt !important;
        }

        .receipt .title {
            font-size: 16pt !important;
            margin: 0;
            font-family: "Times New Roman";
        }

        .receipt .sub-title {
            border-bottom: 1px dotted darkgray;
            margin-bottom: 5px;
            font-size: 7pt !important;
        }

        .receipt th{
            font-family: "Khmer OS System";
        }

        .receipt table.border th, .receipt table.border td {
            border-bottom: 1px dotted darkgray;
        }

        .receipt table.border th.no-border, .receipt table.border td.no-border{
            border: none !important;
        }

        .receipt table.items {
            margin: 10px 0;
        }

        .receipt-footer{
            text-align: center;
            font-family: "Khmer OS System";
            font-size: 6pt !important;
            margin: 10px 0;
            padding-top: 5px;
            border-top: 1px dotted darkgray;
        }

    </style>


</head>
<body onload="window.print();">

<!--<div id="content">-->
<!--<div id="pageFooter"></div>-->
<!--multi-page content here...-->
<!--</div>-->

<div class="wrapper" >

    <!-- Main content -->
    <section class="receipt">

        <h5 class="text-center title">BISS Co., Ltd.</h5>
        <p class="text-center sub-title" >
            #13Eo, St.15 Borey New World, Sambour Village, Sangkat DoungKor, Khan DoungKor, Phnom Penh <br>
            Tel : 092 605 838/071 333 9978
        </p>

        <?php
            $date = strtotime($sale->journal_date);
            $sale_date = Date("Y-m-d H:i",$date);
        ?>

        <div class="row">
            <div class="col-xs-6">
                <div ><span style="display: inline-block; width: 45px;">Seller</span> : Admin </div>
                <div ><span style="display: inline-block; width: 45px;">Customer</span> : General</div>
                <div ><span style="display: inline-block; width: 45px;">Payment</span> : Cash</div>
            </div>
            <div class="col-xs-6">
                <div class="text-right">Date : <span style="display: inline-block; width: 85px;"><?php echo $sale_date;?></span> </div>
                <div class="text-right">Inv # : <span style="display: inline-block; width: 85px;"><?php echo $sale->journal_no;?></span></div>
                <div class="text-right">Rate : <span style="display: inline-block; width: 85px;">$1=R<?php echo $sale->exchange_rate;?></span></div>
            </div>
        </div>

        <table width="100%" class="border items">
            <thead>
                <tr>
                    <th width="40%" class="text-left" colspan="2">ទំនិញ<br>Item</th>
                    <th width="18%" class="text-center">បរិមាណ<br>Qty</th>
                    <th width="18%" class="text-right">តម្លៃ<br>Price</th>
                    <th width="18%" class="text-right">សរុប<br>Total</th>
                </tr>
            </thead>
            <tbody>

                <?php
                if(isset($sale_items) && is_array($sale_items)){
                    $no=0;
                    foreach($sale_items as $row){
                        $no++;
                        ?>
                        <tr>
                            <td class="text-left"><?php echo $no;?></td>
                            <td class="text-left"><?php echo $row->item_name;?></td>
                            <td class="text-center"><?php echo $row->qty;?></td>
                            <td class="text-right">$ <?php echo $row->price;?></td>
                            <td class="text-right">$ <?php echo  $row->get_amount();?></td>
                        </tr>
                    <?php
                    }
                }
                ?>
            </tbody>

        </table>

        <div class="row">
            <div class="col-xs-5">

            </div>
            <div class="col-xs-7">
                <table width="100%" class="border text-bold">
                    <tfoot>
                    <tr >
                        <td class="no-border"> Amount (USD)<span class="pull-right">:</span></td>
                        <td class="text-right no-border" >$<?php echo $sale->total;?></td>
                    </tr>
                    <tr>
                        <td class=""> Discount Rate<span class="pull-right">:</span></td>
                        <td class="text-right"><?php echo $sale->discount;?>%</td>
                    </tr>
                    <tr>
                        <td class="no-border"> Total (USD)<span class="pull-right">:</span></td>
                        <td class="text-right  no-border">$<?php echo $sale->get_total_after_discount();?></td>
                    </tr>
                    <tr>
                        <td class=""> Total (KHR)<span class="pull-right">:</span></td>
                        <td class="text-right">$<?php echo $sale->get_total_after_discount() * $sale->exchange_rate;?></td>
                    </tr>
                    <tr>
                        <td class="no-border"> Receive (USD)<span class="pull-right">:</span></td>
                        <td class="text-right  no-border">$<?php echo $sale->get_total_after_discount();?></td>
                    </tr>
                    <tr>
                        <td class=""> Receive (KHR)<span class="pull-right">:</span></td>
                        <td class="text-right">$0.00</td>
                    </tr>
                    <tr>
                        <td class="no-border"> Refund (USD)<span class="pull-right">:</span></td>
                        <td class="text-right  no-border">$0.00</td>
                    </tr>
                    <tr>
                        <td class="no-border"> Refund (KHR)<span class="pull-right">:</span></td>
                        <td class="text-right no-border">$0.00</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>


        <div class="receipt-footer">
            ទំនិញដែលទិញរួច មិនអាចប្ដូរជាប្រាក់បានទេ<br>
            អរគុណចំពោះការទិញទំនិញនៅហាងយើខ្ញុំ សូមអញ្ជើញមកម្ដងទៀត!
        </div>

    </section><!-- /.content -->

</div><!-- ./wrapper -->

<!-- AdminLTE App -->
<script src="<?php echo base_url()?>template/dist/js/app.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>


</body>
</html>
