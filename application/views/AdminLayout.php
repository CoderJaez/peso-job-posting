<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><img src="<?= base_url('assets/images/referral.png') ?>"
                                alt=""></span>

                        <div class="info-box-content">
                            <span class="info-box-text">REFERRALS</span>
                            <span class="info-box-number"><?= $referrals ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><img src="<?= base_url('assets/images/placement_.png') ?>"
                                alt=""></span>

                        <div class="info-box-content">
                            <span class="info-box-text">PLACEMENTS</span>
                            <span class="info-box-number"><?= $placements ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">REGISTERED APPLICANTS</span>
                            <span class="info-box-number"><?= $applicants ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><img src="<?= base_url('assets/images/job-fair.png') ?>"
                                alt=""></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Jobs Solicited</span>
                            <span class="info-box-number"><?= $js; ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>

            <div class="box  box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Weekly placements for the year <?= date('Y') ?> </h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-8 col-xs-12">
                            <canvas id="weeklyPlacement" height="100px"></canvas>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <p class="text-center">
                                <strong>Job Solicited</strong>
                            </p>
                            <div id="js_status">

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="box box-solid bg-teal-gradient">
                        <div class="box-header">
                            <h3 class="box-title">Weekly referrals for the year <?= date('Y') ?> </h3>
                        </div>
                        <div class="box-body border-radius-none">

                            <canvas id="weeklyReferral" height="100"></canvas>
                        </div>
                    </div>
                </div>



            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <!-- <b>Version</b> 2.4.0 -->
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?></strong> All rights
    reserved.
</footer>

<!-- Control Sidebar -->

<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
   immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->