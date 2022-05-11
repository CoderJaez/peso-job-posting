<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">
            <div class="col-sm-5">
                <div class="box box-solid box-danger">
                    <div class="box-header">
                        <h4 class="box-title">Account settings</h4>
                    </div>
                    <div class="box-body">
                        <h4>Account Information</h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Email:</td>
                                    <td><?= $this->session->userdata('email') ?></td>
                                </tr>
                                <tr>
                                    <td>Date created:</td>
                                    <td><?= date('F d, Y', strtotime($this->session->userdata('dateCreated'))) ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="pull-right btn btn-sm btn-warning btnEditAcc">Edit</button>
                    </div>
                </div>
            </div>

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


<div class="modal fade" id="UserAccountModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Update User Account</h3>
            </div>
            <div class="modal-body">
                <?= form_open('Resumes/update_account', array('id' => 'updateUser')); ?>
                <div class="form-group"><label for="">Email:</label><input type="email"
                        value="<?= $this->session->userdata('email') ?>" name="email" class="form-control">
                </div>
                <div class="form-group"><label for="">Password:</label><input type="password" name="password"
                        class="form-control">
                    <span style="font-style:italic;font-size:9pt">Password length should be at least 6 characters.
                    </span>
                </div>
                <div class="form-group"><label for="">Confirm Password:</label><input type="password"
                        name="confirm_password" class="form-control"></div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-warning">Update</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>