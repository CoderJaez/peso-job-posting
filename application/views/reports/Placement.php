<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">


            <div class="row">
                <div class="col-lg-12 col-sm-10 col-xs-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Placement report</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>

                            </div>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group-inline">
                                        <label for="" class="form-control-label col-sm-1">Date From:</label>
                                        <div class="col-sm-2">
                                            <input type="date" name="dateFrom" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group-inline">
                                        <label for="" class="form-control-label col-sm-1">Date To:</label>
                                        <div class="col-sm-2">
                                            <input type="date" name="dateTo" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group-inline">
                                        <label for="" class="form-control-lable col-sm-1">Freqeuncy:</label>
                                        <div class="col-sm-2">
                                            <select name="frequency" id="" class="form-control">
                                                <option value="" disabled selected>SELECT FREQUENCY</option>
                                                <option value="month">MONTH</option>
                                                <option value="week">WEEK</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group-inline">
                                        <label for="" class="form-control-lable col-sm-1">Office:</label>
                                        <div class="col-sm-2">
                                            <select name="office" id="" class="form-control">
                                                <option value="" disabled selected>SELECT OFFICE</option>
                                                <option value="peso">PESO</option>
                                                <option value="dole">DOLE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-sm-offset-10">
                                    <div class=" form-group pull-right">
                                        <button class="btn btn-primary btn-sm btnFilterPlacementReport"><span
                                                class="fa fa-filter">
                                                Filter</span></button>
                                        <button disabled class="btn btn-success  btn-sm btnPrintReferralReport"
                                            onclick="print_referral_letter('printArea','landscape')">
                                            Print</button>
                                    </div>
                                </div>
                            </div>

                            <div class="printArea" id="printArea">
                                <style>
                                table {
                                    width: 100%;
                                    table-layout: auto;
                                }

                                td,
                                thead {
                                    font-size: 8.5pt !important;
                                }

                                .rr-table th,
                                td {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                }

                                .rr-table,
                                th {
                                    padding: 3px;
                                    text-align: center;
                                }

                                .rr.table {
                                    page-break-after: always;

                                }

                                td {
                                    padding: 3px;
                                    text-align: left;
                                }
                                </style>
                                <div class="rr-body">

                                    <div class="rr-table">
                                        <table>
                                            <thead>
                                                <tr style="border-style:none !important">
                                                    <th colspan="12" style="border-style:none !important">
                                                        <div class="rr-header">
                                                            <img id="header"
                                                                src="<?= base_url('assets/images/peso_header.jpg') ?>"
                                                                alt="">
                                                            <span id="placement"></span>
                                                            <br> <span id="frequent"></span>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <th rowspan="2" min-width="10px">No</th>
                                                <th rowspan="2">Date referred</th>
                                                <th rowspan="2" style="min-width: 150px">Name</th>
                                                <th rowspan="2" style="min-width: 150px">Name of Company</th>
                                                <th rowspan="2" style="min-width: 100px">Position</th>
                                                <th rowspan="2" width="30px">Local</th>
                                                <th rowspan="2" width="50px">Government</th>
                                                <th rowspan="2" width="40px">Oveseas</th>
                                                <th colspan="2">Gender</th>
                                                <th rowspan="2">Date Hired</th>
                                                <th rowspan="2" width="30px">Remarks</th>
                                                <tr>
                                                    <th>M</th>
                                                    <th>F</th>
                                                </tr>
                                            </thead>
                                            <tbody id="referral_report">

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
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