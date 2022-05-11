<!-- =============================================== -->


<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-sm-7 col-xs-12">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Personal Information</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="profile-pic">
                                        <img id="user_profile"
                                            src="<?= ($applicant->image_dir) ? base_url($applicant->image_dir) : base_url('assets/uploads/user.png') ?>"
                                            alt="">
                                        <div class="btn-upload">
                                            <button class="btn btn-xs btn-block btn-default btn-uploadPhoto"> <i
                                                    class="fa fa-link"></i>
                                                Upload
                                                photo</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-xs-12">
                                    <div class="edit-info" data-toggle="tooltip" title="Edit info">
                                        <span><i class="fa fa-edit"></i></span>
                                    </div>
                                    <div class="personal-info">
                                        <h2><?= ucwords($applicant->name); ?></h2>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="info-label">Contact No.</td>
                                                    <td> <?= $applicant->contactNo ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="info-label">Email</td>
                                                    <td><?= $applicant->email ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="info-label">Age</td>
                                                    <td>
                                                        <?php if ($applicant->bday != null) : ?>
                                                        <?php
                                                                $date1 = new DateTime(date('Y-m-d'));
                                                                $date2 = new DateTime($applicant->bday);
                                                                $interval = $date1->diff($date2);
                                                                echo "$interval->y years old";
                                                                ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="info-label">Address</td>
                                                    <td><?= ucwords(strtolower("$applicant->address, $applicant->brgyDesc, $applicant->citymunDesc, $applicant->provDesc")) ?>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
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



<!-- Update applicants info form -->
<div class="modal fade " id="applicantForm">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Applicant Information
                </h4>

            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-9">
                        <?= form_open('resume/updatePersonalInfo', array('id' => 'updateApplicanForm', 'class' => 'form-horizontal')); ?>
                        <div class="form-group"><label for="name"
                                class="form-control-label col-sm-4 text-right">Applicant's
                                Name:
                            </label>
                            <div class="col-sm-8"><input type="text" name="name" id="" class="form-control"></div>
                        </div>
                        <div class="form-group"><label for="name" class="form-control-label col-sm-4 text-right">Date of
                                birth
                            </label>
                            <div class="col-sm-8"><input type="date" name="bday" id="" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <label for="gender" class="form-control-label text-right col-sm-4">Gender:
                            </label>
                            <div class="col-sm-8">
                                <select name="gender" class="form-control">
                                    <option selected="true" disabled></option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Email:</label>
                            <div class="col-sm-8"><input type="text" name="email" class="form-control"></div>
                        </div>
                        <div class="form-group">
                            <label for="company_address" class="form-control-label text-right col-sm-4">Contact:
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="contactNo" id="" class="form-control"
                                    data-inputmask='"mask":"9999-9999-999"' data-mask>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="company_address" class="form-control-label text-right col-sm-4">Province:
                            </label>
                            <div class="col-sm-8">
                                <select name="provCode" id="" class="form-control">
                                    <option value="" selected disabled>SELECT PROVINCE</option>

                                    <?php foreach ($province as $key => $row) : ?>
                                    <option value="<?= $row->provCode ?>"><?= $row->provDesc ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_address"
                                class="form-control-label text-right col-sm-4">City/Municipalty:
                            </label>
                            <div class="col-sm-8">
                                <select name="citymunCode" id="" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_address" class="form-control-label text-right col-sm-4">Barangay:
                            </label>
                            <div class="col-sm-8">
                                <select name="brgyCode" id="" class="form-control"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_address" class="form-control-label text-right col-sm-4">Street/Purok:
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="address" id="" class="form-control">
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button class="btn btn-primary">Save</button>
                            <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>

                    <?= form_close(); ?>
                </div>
                <div class="col-sm-2"></div>

                <!-- </div> -->
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ImageModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Upload photo
                </h4>
            </div>
            <div class="modal-body">
                <?= form_open('resumes/upload_photo', array('id' => 'uploadPhoto')) ?>
                <div class="form-group"><input type="file" name="photo" placeholder="Choose Image" class="form-control">
                </div>
                <div class="profile-pic"><img src="" id="img" alt=""></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-sm btn-warning">Done</button>
                <button class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>