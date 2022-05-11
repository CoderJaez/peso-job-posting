<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jQuery-ui.min.js'); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/dataTables.bootstrap.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/datatables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap-timepicker.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/Chart.min.js?v=') . time() ?>"></script>
<script src="<?php echo base_url('assets/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.flash.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jszip.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/pdfmake.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/vfs_fonts.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.print.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/buttons.colVis.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.bootstrap-growl.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.knob.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/ckeditor/ckeditor.js'); ?>"></script>

<script src=<?php echo base_url('assets/js/input-mask/jquery.inputmask.js'); ?>></script>
<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>"
</script>
<script src="<?php echo base_url('assets/js/adminlte.min.js'); ?>"></script>
<!-- AdminLTE App -->

<!-- Custom js -->
<?php if (isset($dashboard) && $dashboard) : ?>
<script src="<?php echo base_url('assets/js/stats_report.js?v=') . time(); ?>"></script>
<?php endif; ?>
<script src="<?php echo base_url('assets/js/Applicant.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/settings.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/applicants_referral.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/user_accounts.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/hired_applicants.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/job_solicitation.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/login_auth.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/report.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/ofw.js?v=') . time(); ?>"></script>
<script src="<?php echo base_url('assets/js/jobs.js?v=') . time(); ?>"></script>


<div id="loading_screen"></div>
<div id="PrintContents"></div>
</body>

</html>