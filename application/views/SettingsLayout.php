<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!-- Main content -->
        <!-- Company settings -->
        <?php if (isset($company) && $company == true) : ?>
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-xs-12">
                    <div class="box box-danger box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Companies</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>

                        </div>
                        <div class="box-body">

                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-condensed" id="companyList">
                                    <thead>
                                        <th><input type="checkbox" name="selectAll"></th>
                                        <th>Placemerit</th>
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>Manager</th>
                                        <th>Date Registered</th>
                                        <th>Action</th>
                                    </thead>
                                    <tbody id="company_list">
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class=" btn-group pull-right">
                                <button class="btn btn-primary btn-sm addCompany"><i class="fa fa-plus"></i>
                                    Company</button>

                                <button class="btn btn-danger btn-sm btnDeleteSelectedCompany"><i
                                        class="fa fa-trash"></i>
                                    Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php $this->load->view("modals/Modal_CompanyForm"); ?>
        <?php endif; ?>
        <!-- Company settings -->





        <!-- Main content -->
        <!-- Add new letter -->
        <?php if (isset($job_position) && $job_position == true) : ?>
        <?php
                $companyList = $this->db->select('id,name')->where('deleted = false')->get('tbl_company')->result();
                ?>
        <section class="content">
            <div class="row">
                <div class="col-lg-8 col-sm-8 col-xs-12">
                    <div class="box box-danger box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Job positions</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-reponsive">
                                <table class="table table-condensed table-striped table-hover" id="positionList">
                                    <thead>
                                        <th><input type="checkbox" name="cbSelectPosition"></th>
                                        <th>Company</th>
                                        <th>Position</th>
                                        <th>Date Registered</th>
                                        <th>action</th>
                                    </thead>
                                    <tbody id="position_list"></tbody>

                                </table>
                            </div><br>
                            <div class=" btn-group pull-right">
                                <button class="btn btn-danger btn-sm btnDeleteSelectedPosition"><i
                                        class="fa fa-trash"></i>
                                    Delete selected</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 col-xs-12">
                    <div class="box box-danger box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add new Job position</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <?= form_open('Settings/save_position', array('id' => 'positionForm')) ?>

                            <div class="form-group"><label for="" class="form-control-label">Company:</label>
                                <select name="companyID" class="form-control">
                                    <option value="" selected="true" disabled></option>
                                    <?php foreach ($companyList as $key => $row) : ?>
                                    <option value="<?= $row->id ?>"><?= strtoupper($row->name) ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                            <div class="form-group"><label for="" class="form-control-label">Position:</label><input
                                    type="text" name="position" id="" class="form-control"></div>
                            <div class="pull-right">
                                <button class="btn btn-small btn-primary" id="btnSavePosition">Save</button>
                                <button class="btn btn-small btn-default" id="btnCancelPosition">Cancel</button>
                            </div>
                            <?= form_close() ?>
                        </div>

                    </div>
                </div>

            </div>
        </section>
        <!-- Positions settings -->
        <?php endif; ?>
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