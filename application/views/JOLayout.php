<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Solicitation</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover condensed table-striped" id="job_vacancy">
                            <thead>
                                <th><input type="checkbox" name="selectAll"></th>
                                <th>Establishment / <br> Company</th>
                                <th>Position</th>
                                <th>Job Description</th>
                                <th>Qualification</th>
                                <th>Date Solicited</th>
                                <th>Vacancy/
                                    Hired</th>
                                <th>Referrals/
                                    Referred</th>
                                <th width="70px">Action</th>
                            <tbody></tbody>
                            </thead>
                        </table>
                    </div>
                    <div class="btn-group pull-right"><button class="btn btn-sm btn-primary" id="btnAddJS">Add Job
                            Solicitation</button><button class="btn btn-sm btn-danger" id="btnDeleteJS">Delete
                            Selected
                            JS</button></div>
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