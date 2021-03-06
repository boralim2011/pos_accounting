
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


    <style type="text/css">

        .app-menu { list-style: none; text-indent: 0; padding: 0; margin: 0; clear:both; }
        .app-menu li { display: inline-block; margin: 30px 20px; float: left; }
        /*.app-menu li:hover .btn-flat { outline: 8px solid #cccccc; }*/
        /*.app-menu li:hover .btn-flat.btn-info { outline-color: #00acd6; }*/
        /*.app-menu li:hover .btn-flat.btn-success { outline-color: #00a65a; }*/
        /*.app-menu li:hover .btn-flat.btn-primary { outline-color: #3c8dbc; }*/
        /*.app-menu li:hover .btn-flat.btn-warning { outline-color: #f39c12; }*/
        /*.app-menu li:hover .btn-flat.btn-danger { outline-color: #dd4b39; }*/

        .app-menu-btn { width: 160px; height: 160px; text-align: center; position: relative; }
        .app-menu-btn.btn-round { border-radius: 30px; -moz-border-radius: 30px; -webkit-border-right: 30px; -khtml-border-radius: 30px; }
        .app-menu-btn.btn-flat { padding: 5px; }
        .app-menu-btn.big-icon { font-size: 100px; }

        /*.app-menu-btn img { display: block; margin: 5px auto; width: 120px; height: 90px; border-radius: 30%; -moz-border-radius: 30%; -webkit-border-right: 30%;  -khtml-border-radius: 30%; }*/
        /*.app-menu-btn span { display: block; margin: 5px auto; white-space: pre-line; overflow: hidden; max-height: 40px; }*/
        /*.app-menu-btn .price {  padding: 3px 10px; right:-10px; top:-15px; position: absolute; border-radius: 3px; background-color: #1ab7ea; color: #ffffff; opacity: 0.85; -khtml-opacity: 0.85; -moz-opacity:0.85; filter: alpha(opacity=85); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=85)"; }*/
        /*.app-menu-btn .price {  padding: 3px 10px; right:-10px; top:-15px; position: absolute; border-radius: 3px; background-color: #333333; color: #ffffff; opacity: 0.85; -khtml-opacity: 0.85; -moz-opacity:0.85; filter: alpha(opacity=85); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=85)";}*/
        /*.app-menu-btn .caption { height: 40px; max-height: 40px; }*/
        /*.app-menu-btn.big-icon .caption { position: absolute; left: 0; bottom: -30px; font-size: 16px; color: #0000ff; text-align: center; width: 100%; height: auto;  }*/

        .app-menu-btn img { margin: 0; width: 100%; height: 100%; }
        .app-menu-btn span { display: block; white-space: pre-line; overflow: hidden; color: #ffffff; }
        .app-menu-btn.btn-default span { background-color: #666666; }
        .app-menu-btn.btn-primary span { background-color: #3c8dbc; }
        .app-menu-btn.btn-info span { background-color: #00acd6; }
        .app-menu-btn.btn-success span { background-color: #00a65a; }
        .app-menu-btn.btn-warning span { background-color: #f39c12; }
        .app-menu-btn.btn-danger span { background-color: #dd4b39; }

        .app-menu-btn .price {  padding: 3px 10px; right: -10px; top: -10px; position: absolute; border-radius: 3px; min-width:20px; opacity: 0.85; -khtml-opacity: 0.85; -moz-opacity:0.85; filter: alpha(opacity=85); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=85)"; }
        .app-menu-btn .caption { position: absolute; bottom: 0; left: 0; max-height: 70px; text-align: left; padding: 5px;  width: 100%; opacity: 0.85; -khtml-opacity: 0.85; -moz-opacity:0.85; filter: alpha(opacity=85); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=85)"; }
        .app-menu-btn.big-icon .caption { position: absolute; left: 0; bottom: -30px; font-size: 16px; color: #003399; background-color: transparent; text-align: center; width: 100%; height: auto;  }



        @media screen and (max-width: 768px)
        {
            .app-menu li { display: inline-block; margin: 10px; float: left; }
            .app-menu li:hover .btn-flat { outline-width: 4px; }

            .app-menu-btn{width: 70px; height: 70px; }
            .app-menu-btn.btn-round { border-radius: 10px; -moz-border-radius: 10px; -webkit-border-right: 10px; -khtml-border-radius: 10px; }
            .app-menu-btn.btn-flat { padding: 1px; }
            .app-menu-btn.big-icon { font-size: 40px !important; }

            /*.app-menu-btn img { display: block; margin: 3px auto; width: 45px; height: 45px; }*/
            /*.app-menu-btn span { display: none; }*/
            /*.app-menu-btn .price { display: block; padding: 2px 5px; }*/
            /*.app-menu-btn .caption { display: block; width: 70px; height: auto; max-height: 60px; padding: 2px 5px; left: -1px; bottom:-20px; position: absolute; border-radius: 3px; background-color: #1ab7ea; color: #ffffff; opacity: 0.85; -khtml-opacity: 0.85; -moz-opacity:0.85; filter: alpha(opacity=85); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=85)"; }*/
            /*.app-menu-btn .caption { display: block; width: 70px; height: auto; max-height: 60px; padding: 2px 5px; left: -1px; bottom:-20px; position: absolute; border-radius: 3px; background-color: #333333; color: #ffffff; opacity: 0.85; -khtml-opacity: 0.85; -moz-opacity:0.85; filter: alpha(opacity=85); -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=85)"; }*/
            /*.app-menu-btn.big-icon .caption { display: none; }*/

            .app-menu-btn .price { display: block; padding: 0px; }
            .app-menu-btn .caption { max-height: 40px; padding: 0px; }
            .app-menu-btn.big-icon .caption { display: none; }

        }


    </style>




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

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url();?>template/plugins/jQuery/jQuery-2.1.4.min.js"></script>


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

<div class="wrapper " >

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo base_url();?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>POS</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>POS</b> Accounting</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
<!--            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">-->
<!--                <span class="sr-only">Toggle navigation</span>-->
<!--            </a>-->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?php echo $this->UserSession->photo_path; //base_url().$DefaultUserImage; ?>" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php echo $this->UserSession->user_name;?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?php echo $this->UserSession->photo_path;//base_url().$DefaultUserImage;?>" class="img-circle" alt="User Image">
                                <p>
                                    <?php echo $this->UserSession->user_name;?> - <?php echo $this->UserSession->user_group_name;?>
                                    <small>Member since <?php echo Date("Y-m-d", (new DateTime($this->UserSession->created_date))->getTimestamp()) ;?></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div>
                                    <a href="<?php echo base_url();?>user/change_password" ><i class="fa fa-edit"></i> Change Password</a>
                                </div>
                                <!--<div class="col-xs-4 text-center">-->
                                <!--<a href="#">Sales</a>-->
                                <!--</div>-->
                                <!--<div class="col-xs-4 text-center">-->
                                <!--<a href="#">Friends</a>-->
                                <!--</div>-->
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#user/edit/<?php echo $this->UserSession->user_id;?>" class="btn btn-default btn-flat" data-json='<?php echo json_encode($this->UserSession);?>' ><i class="fa fa-user"></i> Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url();?>login/logout" class="btn btn-default btn-flat"><i class="fa fa-power-off"></i> Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <!-- Language -->
                    <!--                    <li class="dropdown notifications-menu language-menu">-->
                    <!--                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">-->
                    <!--                            <img src="--><?php //echo base_url();?><!--template/dist/img/khmer.png" class="no-margin"/>-->
                    <!--                        </a>-->
                    <!--                        <ul class="dropdown-menu">-->
                    <!--                            <li>-->
                    <!--                                <ul class="menu">-->
                    <!--                                    <li><a href="#" class="font-khmer"><img src="--><?php //echo base_url();?><!--template/dist/img/khmer.png"/> ភាសាខ្មែរ</a></li>-->
                    <!--                                    <li><a href="#"><img src="--><?php //echo base_url();?><!--template/dist/img/english.png"/> English </a></li>-->
                    <!--                                </ul>-->
                    <!--                            </li>-->
                    <!--                        </ul>-->
                    <!--                    </li>-->

                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" id="btn-full-screen" data-toggle="tooltip" title="Full Screen"><i class="fa fa-window-maximize"></i></a>
                    </li>
                    <li>
                        <!--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gear"></i></a>-->
                        <a href="<?php echo base_url("admin");?>" title="Setting"><i class="fa fa-gear"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="content-wrapper no-margin" id = "display-content">

        <?php if( isset($view) && $view!='') $this->load->view($view); ?>

    </div>


</div>


<!--Dialog message-->
<!--<div class="modal modal-success fade in" id="dialog-user-type">-->
<div class="modal modal-primary fade in" id="dialog-confirm">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirm Message!</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-margin">
                    <button type="button" class="btn btn-outline" id="btn-ok"><i class="fa fa-check"></i> Yes</button>
                    <button type="button" class="btn btn-outline" data-dismiss="modal"><i class="fa fa-close"></i> No</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- REQUIRED JS SCRIPTS -->


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

        if(typeof $hash === typeof undefined || $hash==='' || $hash==='#') return; //$hash = '#pos';

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
            $root + '<?php echo SELF;?>/pos',
            $root + '<?php echo SELF;?>/pos/',
            $root + '<?php echo SELF;?>/pos<?php echo $this->config->item('url_suffix');?>',
            $root + '<?php echo SELF;?>/pos<?php echo $this->config->item('url_suffix');?>/',
            $root + 'pos',
            $root + 'pos/',
            $root + 'pos<?php echo $this->config->item('url_suffix');?>',
            $root + 'pos<?php echo $this->config->item('url_suffix');?>/',
        ];

        var $url = $root + $hash.replace('#','');
        var $current_url = window.location.toString();
        $current_url = $current_url.replace($hash,'');
        if($roots.indexOf($current_url)< 0 )
        {
            //$url = $root+'pos/show_404';
            window.location = '<?php echo base_url("pos/show_404");?>';
            return;
        }

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
                else if(data==522)
                {
                    window.location = '<?php echo base_url("pos/show_404");?>';
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