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
                                <div class="col-sm-9">
                                    <div class="form-group-inline">
                                        <label for="" class="form-control-label col-sm-1">From:</label>
                                        <div class="col-sm-3">
                                            <input type="date" name="dateFrom" id="" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group-inline">
                                        <label for="" class="form-control-label col-sm-1">To:</label>
                                        <div class="col-sm-3">
                                            <input type="date" name="dateTo" id="" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class=" form-group pull-left">
                                        <button class="btn btn-primary btn-sm btnFilterJobSolicitedReport"><span
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
                                .r-body {
                                    width: 816px;
                                    min-height: 1248px;
                                    /* border-style: solid; */
                                    /* border-color: #F97502; */
                                    font-family: 'calibri';
                                }

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
                                <div class="r-body">

                                    <div class="rr-table">
                                        <table>
                                            <thead>
                                                <tr style="border-style:none !important">
                                                    <th colspan="4" style="border-style:none !important">
                                                        <div class="rr-header">
                                                            <img id="header"
                                                                src="<?= base_url('assets/images/peso_header.jpg') ?>"
                                                                alt="">
                                                        </div>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="4" style="font-size:14pt">LIST OF JOB VACANCIES
                                                        SOLICITED FROM DIFERRENT
                                                        ESTABLISHMENTS, THIS CITY FOR THIS <span id="frequent"></span>
                                                    </th>
                                                </tr>
                                                <th width="120px">NAME OF STABLISHMENT</th>
                                                <th width="120px">NAME OF POSTION</th>
                                                <th width="200px">JOB DESCRIPTION</th>
                                                <th width="100px">NO OF VACANCIES</th>

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