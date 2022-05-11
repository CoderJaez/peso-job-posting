<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">
            <div class="box box-solid box-danger">
                <div class="box-header">
                    <h4 class="box-title">Your Job Applications</h4>
                </div>
                <div class="box-body">
                    <table class="ui-content ui-header">
                        <tbody>
                            <tr>
                                <th class="header-padding col-sm-3">Position</th>
                                <th class="header-padding col-sm-2">Location</th>
                                <th class="header-padding col-sm-2">Company</th>
                                <th class="header-padding col-sm-2">Date Applied</th>
                                <th class="header-padding col-sm-1">Job Status</th>
                            </tr>
                        </tbody>
                    </table>

                    <?php if ($application_list) : ?>
                    <?php foreach ($application_list as $key => $row) : ?>
                    <table class="ui-content ui-application ">
                        <tbody>
                            <tr>
                                <td class="data-padding col-sm-3 ui-title"><?= $row->position ?></td>
                                <td class="data-padding col-sm-2"><?= ucwords(strtolower($row->company_address)) ?></td>
                                <td class="data-padding col-sm-2"><?= ucwords(strtolower($row->company)) ?></td>
                                <td class="data-padding col-sm-2"><?= date('M d, Y', strtotime($row->dateApplied)) ?>
                                </td>
                                <td class="data-padding col-sm-1" style="font-weight: bold">
                                    <?= ($row->referred) ? '<span class="text-success"> Referred</span>' : '<span class="text-warning">Pending</span>' ?>
                                </td>

                            </tr>
                        </tbody>
                    </table>

                    <?php endforeach; ?>
                    <?php endif; ?>
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