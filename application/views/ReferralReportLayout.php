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
                            <h3 class="box-title">Refferal report</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                                <button class="btn btn-primary pull-right btn-sm"
                                    onclick="print_referral_letter('printArea','landscape')">
                                    print</button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="printArea" id="printArea">
                                <style>
                                .rr-table th,
                                td {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                }

                                .rr-table,
                                th,
                                td {
                                    padding: 5px;
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
                                                            <img src="<?= base_url('assets/images/dole_header.jpg') ?>"
                                                                alt="">
                                                            <span>For the month of: <strong>September 25, 2019 to
                                                                    October 25,
                                                                    2019</strong></span>
                                                        </div>
                                                    </th>
                                                </tr>

                                                <th rowspan="2" width="10">No</th>
                                                <th rowspan="2" width="150px">Name</th>
                                                <th colspan="2"> Gender </th>
                                                <th colspan="2">Contact Details</th>
                                                <th colspan="3">Referred placemerit</th>
                                                <th colspan="3">Referred for Training <br> INDICATE TYPE / NATURE OF
                                                    TRAINING </th>
                                                <tr>
                                                    <th>M</th>
                                                    <th>F</th>
                                                    <th width="100px">Address</th>
                                                    <th>Contact</th>
                                                    <th>Name of Company/Position
                                                        Employer <br> (Local)
                                                    </th>
                                                    <th>Name of Company/Position
                                                        Employement <br> (Government)</th>
                                                    <th>Name of Agency/Company/
                                                        Principal/Position Employer <br> (Overseas)</th>
                                                    <th>Training <br> (TESDA)</th>
                                                    <th>Livelihood <br> (DOLE RO)</th>
                                                    <th>Remarks</th>
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