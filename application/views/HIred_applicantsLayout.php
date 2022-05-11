<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!-- Main content -->
        <section class="content">
            <div class="box box-danger ">
                <div class="box-header with-border">
                    <h3 class="box-title">Hired applicants</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-condensed" id="hired_applicant_list">
                                <thead>
                                    <th width="30px"><input type="checkbox" name="selectAll"></th>
                                    <th>Applicant Name</th>
                                    <th>Establishment/Company</th>
                                    <th>Position</th>
                                    <th>Date hired</th>
                                    <th>Remarks</th>
                                    <th>Referred</th>
                                    <th>Action</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="btn-group pull-right">
                        <?php foreach ($this->session->access_rights as $key => $row) : ?>
                        <?php if ($row->modules == 'placement') : ?>
                        <?php if ($row->_add) : ?>
                        <button class="btn btn-primary btn-sm" id="btnAddNewHiredApplicant">Add new </button>
                        <?php endif; ?>
                        <?php if ($row->_delete) : ?>
                        <button class="btn btn-danger btn-sm" id="btnSelectedDeleteHiredApplicant">Delete Selected
                            Applicant</button>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
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