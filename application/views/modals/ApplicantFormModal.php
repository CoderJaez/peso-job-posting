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
                <!-- loader -->
                <div class="remove-loader-gear" id="loadingScreen">
                </div>
                <!-- loader -->
                <!-- <div class="custom-content"> -->
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-9">
                        <?= form_open('Applicant/add_applicant', array('id' => 'applicant_form', 'class' => 'form-horizontal')); ?>
                        <div class="form-group"><label for="name"
                                class="form-control-label col-sm-4 text-right">Applicant's
                                Name:
                            </label>
                            <div class="col-sm-8"><input type="text" name="name" id="" class="form-control"></div>
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
                            <button class="btn btn-primary" id="btnAddApplicant">Save</button>
                            <button class="btn btn-default" id="btnCancelApplicant">Cancel</button>
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