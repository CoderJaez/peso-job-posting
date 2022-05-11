<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="<?= base_url('login') ?>"><b>PESO</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?= form_open('User_auth/check_user', array('id' => 'user_form')); ?>
            <div class="form-group has-feedback">
                <input type="username" name="username" class="form-control" placeholder="Username">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            <?= form_close() ?>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->