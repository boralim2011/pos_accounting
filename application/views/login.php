<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>BIS Management System | Log in</title>
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
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url();?>template/plugins/iCheck/square/blue.css">

    <!--Dream Alert-->
    <!--<link href="--><?php //echo base_url();?><!--template/plugins/dreamalert/jquery.dreamalert.css" media="screen" rel="stylesheet" type="text/css" />-->
    <!-- Toast message  -->
    <link href="<?php echo base_url();?>template/plugins/toastr/toastr.css" rel="stylesheet" type="text/css" />
    <!-- Toast message -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo base_url();?>"><b>BIS</b>Management System</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Login Form</p>
        <form action="<?php echo base_url();?>login" method="post" id="login-form">
            <div class="form-group has-feedback">
                <input name="email" type="text" class="form-control" placeholder="User Name or Email" value="<?php if(isset($email)) echo $email;?>">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="Password" value="<?php if(isset($password)) echo $password;?>">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">

                    <a href="<?php echo base_url();?>login/reset_password">I forgot my password</a><br>
                    <div class="checkbox icheck">
<!--                        <label>-->
<!--                            <input type="checkbox"> Remember Me-->
<!--                        </label>-->
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" name="login" id="login" class="btn btn-primary btn-block"><i class="fa fa-lock"></i> Login </button>
                </div><!-- /.col -->
            </div>
        </form>

        <!--        <div class="social-auth-links text-center">-->
        <!--          <p>- OR -</p>-->
        <!--          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>-->
        <!--          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>-->
        <!--        </div>--><!-- /.social-auth-links -->

        <!--<a href="<?php //echo base_url();?>login/reset_password">I forgot my password</a><br>-->
        <!--        <a href="register.html" class="text-center">Register a new membership</a>-->

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo base_url();?>template/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo base_url();?>template/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>template/plugins/iCheck/icheck.min.js"></script>

<!-- Toastr message -->
<!-- Dream alert -->
<!--<script src="--><?php //echo base_url();?><!--template/plugins/dreamalert/jquery.dreamalert.js" type="text/javascript"></script>-->
<script src="<?php echo base_url();?>template/plugins/toastr/toastr.js"></script>



<script>


    function show_message(message)
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

    }

    function go_to(controller='')
    {
        window.location = '<?php echo base_url();?>'+ controller;
    }

    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>


<script type="text/javascript">

    $(document).ready(function()
    {
        $(document).off("submit","#login-form");
        $(document).on("submit","#login-form", function( event )
        {
            event.preventDefault();

            var form = $("#login-form");
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
                        go_to("login");
                    }
                    else
                    {
                        if(data.success===true){
                            go_to(''); //base_utl();
                        }
                        //alert(data.message);
                        show_message(data.message);
                    }
                },
                error: function(xhr,status,error){
                    var message = '{"text":"'+error+'","type":"Error","title":"Error"}';
                    show_message(message);
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



</body>
</html>
