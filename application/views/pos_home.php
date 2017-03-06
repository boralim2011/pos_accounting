
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BIS | <?php echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/bootstrap/css/bootstrap.min.css">


    <link rel="stylesheet" href="<?php echo base_url();?>template/style/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>template/style/ionicons.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/datatables/responsive.bootstrap.min.css">


    <!-- daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/iCheck/all.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/timepicker/bootstrap-timepicker.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/select2/select2.min.css">
    <!-- Bootstrap Date Picker -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/datepicker/datepicker3.css">


    <!--Dream Alert-->
    <!--<link href="--><?php //echo base_url();?><!--template/plugins/dreamalert/jquery.dreamalert.css" media="screen" rel="stylesheet" type="text/css" />-->
    <!-- Toast message  -->
    <link href="<?php echo base_url();?>template/plugins/toastr/toastr.css" rel="stylesheet" type="text/css" />
    <!-- Toast message -->

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <!--<link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">-->
    <link rel="stylesheet" href="<?php echo base_url();?>template/dist/css/skins/_all-skins.min.css">

    <!-- Custom style-->
    <link rel="stylesheet" href="<?php echo base_url();?>template/style/custom_style.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
        if(navigator.onLine)
        {
            //alert("Online");
        }
        else
        {
            //alert("Offline");
        }
    </script>


    <style type="text/css">

        .app-menu { list-style: none; border: 1px solid red; clear: both; }
        .app-menu li { display: inline-block; margin: 25px; float: left; }
        .btn-app-menu {width: 160px; height: 160px; border-radius: 20px; font-size: 100px; }

        @media screen and (max-width: 768px)
        {
            .btn-app-menu{width: 70px; height: 70px; border-radius: 10px; font-size: 35px !important;}
            .app-menu li { display: inline-block; margin: 15px; float: left; }
        }



    </style>
    

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">

<div class="bg-blue">

    <ul class="app-menu">
        <li> <a href="#" class="btn btn-warning btn-app-menu"> <i class="fa fa-dollar"></i> </a></li>
        <li> <a href="#" class="btn btn-success btn-app-menu"> <i class="fa fa-gear"></i> </a></li>
        <li> <a href="#" class="btn btn-info btn-app-menu"> <i class="fa fa-shopping-basket"></i> </a></li>
        <li> <a href="#" class="btn btn-primary btn-app-menu"> <i class="fa fa-rocket"></i> </a></li>
        <li> <a href="#" class="btn btn-default btn-app-menu"> <i class="fa fa-rocket"></i> </a></li>
    </ul>

    <ul class="app-menu">
        <li> <a href="#" class="btn btn-warning btn-app-menu"> <i class="fa fa-user"></i> </a></li>
        <li> <a href="#" class="btn btn-success btn-app-menu"> <i class="fa fa-user"></i> </a></li>
        <li> <a href="#" class="btn btn-info btn-app-menu"> <i class="fa fa-user"></i> </a></li>
        <li> <a href="#" class="btn btn-primary btn-app-menu"> <i class="fa fa-user"></i> </a></li>
    </ul>

    <div class="clearfix"></div>
</div>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url();?>template/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo base_url();?>template/bootstrap/js/bootstrap.min.js"></script>

<!-- DataTables -->
<script src="<?php echo base_url();?>template/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>template/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>template/plugins/datatables/responsive.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>template/plugins/datatables/dataTables.responsive.min.js"></script>


<!-- Select2 -->
<script src="<?php echo base_url();?>template/plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="<?php echo base_url();?>template/plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?php echo base_url();?>template/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo base_url();?>template/plugins/input-mask/jquery.inputmask.extensions.js"></script>

<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="<?php echo base_url();?>template/plugins/daterangepicker/daterangepicker.js"></script>
<!-- bootstrap color picker -->
<script src="<?php echo base_url();?>template/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url();?>template/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- bootstrap date picker -->
<script src="<?php echo base_url();?>template/plugins/datepicker/bootstrap-datepicker.js"></script>

<!-- SlimScroll -->
<script src="<?php echo base_url();?>template/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url();?>template/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>template/plugins/fastclick/fastclick.min.js"></script>

<!-- Toastr message -->
<!-- Dream alert -->
<!--<script src="--><?php //echo base_url();?><!--template/plugins/dreamalert/jquery.dreamalert.js" type="text/javascript"></script>-->
<script src="<?php echo base_url();?>template/plugins/toastr/toastr.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo base_url();?>template/dist/js/app.min.js"></script>

<!-- Pagination -->
<script src="<?php echo base_url();?>template/plugins/pagination/jquery.bootpag.min.js"></script>



<script>


    $(document).ready(function(){

        $("#btn-full-screen").on("click", function(event){
            event.preventDefault();

            launchIntoFullscreen(document.documentElement);
        });

    });


    function launchIntoFullscreen(element)
    {
        if(element.requestFullscreen) {
            element.requestFullscreen();
        } else if(element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if(element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if(element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    }



</script>


<script type="text/javascript">

    function change_skin(skin)
    {
        $("body").removeClass (function (index, css) {
            return (css.match (/(^|\s)skin-\S+/g) || []).join(' ');
        });
        $("body").addClass(skin);
    }

    function go_to_login()
    {
        window.location = '<?php echo base_url();?>login';
    }

    function on_hash_change()
    {
        var $hash = location.hash.toString();

        if(typeof $hash === typeof undefined || $hash==='' || $hash==='#') $hash = '#home';

        var $a = $('a[href="'+$hash+'"]:first');

        //for menu selection
        if($a.parent().parent().hasClass('sidebar-menu') || $a.parent().parent().hasClass('treeview-menu'))
        {
            $("ul.sidebar-menu li").removeClass('active');
            $a.parent().addClass('active');
            if($a.parent().parent().hasClass('treeview-menu')) {
                $a.parent().parent().parent().addClass('active');
            }
        }

        var $root = '<?php echo base_url()?>';
        var $roots = [
            $root,
            $root + '<?php echo SELF;?>',
            $root + '<?php echo SELF;?>/',
            $root + '<?php echo SELF;?>/home',
            $root + '<?php echo SELF;?>/home/',
            $root + '<?php echo SELF;?>/home<?php echo $this->config->item('url_suffix');?>',
            $root + '<?php echo SELF;?>/home<?php echo $this->config->item('url_suffix');?>/',
            $root + 'home',
            $root + 'home/',
            $root + 'home<?php echo $this->config->item('url_suffix');?>',
            $root + 'home<?php echo $this->config->item('url_suffix');?>/',
        ];

        var $url = $root + $hash.replace('#','');
        var $current_url = window.location.toString();
        $current_url = $current_url.replace($hash,'');
        if($roots.indexOf($current_url)< 0 ) $url = $root+'home/show_404';

        var $data = $a.attr('data-json');
        if (typeof $data === typeof undefined || $data === false)
        {
            $data = true;
        }

        // Send the data using post
        //$(selector).post(URL,data,function(data,status,xhr),dataType)
        var posting = $.post(
            $url,
            {ajax : $data },
            function( data, status, xhr )
            {
                if(data==521)
                {
                    go_to_login();
                }
                else
                {
                    $("#display-content").empty().append(data);
                }
            }
        );
    }

    $(document).ready(function()
    {
        on_hash_change();

        window.onhashchange = function(event)
        {
            event.preventDefault();

            on_hash_change();
        };

        //ajax button
        $(document).off('click','.btn-refresh');
        $(document).on('click','.btn-refresh',function(event)
        {
            event.preventDefault();

            on_hash_change();
        });
    });


</script>

<script type="text/javascript">

    $(document).ready(function()
    {
        function setModalMaxHeight(element) {
            this.$element     = $(element);
            this.$content     = this.$element.find('.modal-content');
            var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
            var dialogMargin  = $(window).width() < 768 ? 20 : 60;
            var contentHeight = $(window).height() - (dialogMargin + borderWidth);
            var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
            var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
            var maxHeight     = contentHeight - (headerHeight + footerHeight);

            this.$content.css({
                'overflow': 'hidden'
            });

            this.$element
                .find('.modal-body').css({
                    'max-height': maxHeight,
                    'overflow-y': 'auto'
                });
        }

        $(document).off('show.bs.modal', '.modal');
        $(document).on('show.bs.modal', '.modal', function() {
            $(this).show();
            setModalMaxHeight(this);
        });

        $(window).resize(function() {
            if ($('.modal.in').length != 0) {
                setModalMaxHeight($('.modal.in'));
            }
        });

    });

</script>

<script type="text/javascript">

    function show_message(message, display)
    {
        var sms = JSON.parse(message);
        var type = sms.type;
        var types = { Error: "error", Success:"success", Warning:"warning", Info:"info" };
        if(typeof type === typeof undefined || !(type in types)) type = 'Success';

        var title = sms.title;
        if(typeof title === typeof undefined) title = type;

        toastr.clear();

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-bottom-full-width",
            //"positionClass": "toast-bottom-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "3000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        Command: toastr[types[type]]("<h4>" + title + "</h4><div>" + sms.text + "</div>");


//        $.post(
//            '<?php //echo base_url()?>//user/show_message',
//            { message: message },
//            function(data, status, xhr){
//                //$("#message").empty().append(data);
//
//                display.empty().append(data);
//                display.fadeIn();
//                setTimeout(function(){
//                    display.fadeOut();
//                }, 4000);
//            }
//        );
    }

    function show_dialog_message(message, type)
    {
        var types = { Error: "modal-warning", Success:"modal-primary" };
        if(typeof type === typeof undefined || !(type in types)) type = 'Success';

        $("#dialog-message").removeClass('modal-warning');
        $("#dialog-message").removeClass('modal-primary');
        $("#dialog-message").addClass(types[type]);

        $("#dialog-message .modal-title").empty().append(type);
        $("#dialog-message .modal-body").empty().append(message);

        $("#dialog-message").modal({
            backdrop: "static" // true | false | "static" => default is true
        });

        ////clear form and refresh main page when close dialog
        //$(document).off('hidden.bs.modal', '#dialog-message');
        //$(document).on('hidden.bs.modal', '#dialog-message', function() {
        //    $("#dialog-message").removeClass('modal-warning');
        //    $("#dialog-message").removeClass('modal-primary');
        //});
    }

    function confirm_message(message)
    {
        $("#dialog-confirm .modal-body").empty().append(message);

        $("#dialog-confirm").modal({
            backdrop: "static" // true | false | "static" => default is true
        });

        //$(document).off("click", "#dialog-confirm #btn-ok");
        //$(document).on("click", "#dialog-confirm #btn-ok", function(event){
        //    event.preventDefault();
        //    $("#dialog-confirm").modal("hide");
        //});
    }

</script>


<!-- Optionally, you can add Slimscroll and FastClick plugins.
 Both of these plugins are recommended to enhance the
 user experience. Slimscroll is required when using the
 fixed layout. -->

<div id="display-script"></div>
<?php

if(isset($script_view) && $script_view!='' ) $this->load->view($script_view);

?>


</body>
</html>