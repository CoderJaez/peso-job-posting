<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-sm-10 col-xs-12">
                    <div class="box box-danger box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Applicants</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <!-- Table -->
                                <table class="table table-hover table-striped  table-condensed" id="ReferralList">
                                    <thead>
                                        <th width="3%"><input type="checkbox" id="cbSelectAllReferral" name="selectAll">
                                        </th>
                                        <th width="10%">No</th>
                                        <th width="15%">Applicant Name</th>
                                        <th>Gender</th>
                                        <th width="20%">Address</th>
                                        <th>Contact</th>
                                        <th width="15%">Referred Placemerit</th>
                                        <th>Training</th>
                                        <th>Date referred</th>
                                        <th width="10%">Action</th>
                                    </thead>
                                    <tbody id="referral_list">

                                    </tbody>

                                </table>
                            </div>
                            <br>
                            <div class=" btn-group pull-right">
                                <button class="btn btn-warning btn-sm printSelectedReferral"><i class="fa fa-print"></i>
                                    Print Selected Referrals </button>
                                <?php foreach ($this->session->access_rights as $key => $row) : ?>
                                <?php if ($row->modules == 'placement') : ?>
                                <?php if ($row->_add) : ?>
                                <button class="btn btn-primary btn-sm referApplicant"><i class="fa fa-plus"></i>
                                    Referrals </button>
                                <?php endif; ?>
                                <?php if ($row->_delete) : ?>
                                <button class="btn btn-danger btn-sm btnDeleteSelectedReferrals"><i
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
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?></strong> All rights
    reserved.
</footer>
</div>