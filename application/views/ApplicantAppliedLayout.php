<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-sm-10 col-xs-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Applicants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-condensed"
                                    id="ApplicantAppliedList">
                                    <thead>
                                        <th><input type="checkbox" id="cbSelectAllApplicants" name="selectAll"></th>
                                        <th>Applicant Name</th>
                                        <th>Gender</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Contact no.</th>
                                        <th>Establishment/Position</th>
                                        <th>Date applied</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="applicant_list">

                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class=" btn-group pull-right">
                                <?php foreach ($this->session->access_rights as $key => $row) : ?>
                                <?php if ($row->modules == 'applicant') : ?>
                                <?php if ($row->_delete) : ?>
                                <button class="btn btn-danger btn-sm btnDeleteSelectedApplicants"><i
                                        class="fa fa-trash"></i>
                                    Delete</button>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <!-- Table -->
                        </div>
                    </div>

                </div>
            </div>
        </section>
</div>

<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <!-- <b>Version</b> 2.4.0 -->
        <strong>Copyright &copy; <?php echo date('Y'); ?></strong> All rights
        reserved.
    </div>

</footer>
</div>