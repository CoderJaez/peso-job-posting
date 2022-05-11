<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">


            <div class="row">
                <div class="col-lg-10 col-sm-10 col-xs-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">User accounts</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>

                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-condensed table-hover" id="UserAccountList">
                                    <thead>
                                        <th><input type="checkbox" name="selectAll"></th>
                                        <th>Fullname</th>
                                        <th>Position</th>
                                        <th>Username</th>
                                        <th>Date registered</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="UserAccount_list">
                                    </tbody>
                                </table>
                            </div><br>
                            <div class="btn-group pull-right">
                                <button class="btn btn-small btn-primary" id="btnAddUserAccount">Add new
                                    account</button>
                                <button class="btn btn-small btn-warning" id="btnSetActiveInactiveUserAccount">Set
                                    active/inactive</button>
                                <button class="btn btn-smal btn-danger"
                                    id="btnDeleteSelectedUserAccount">Delete</button>
                            </div>
                        </div>
                        <!-- Table -->
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