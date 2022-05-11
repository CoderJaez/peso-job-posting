<div class="modal fade " id="companyForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Company Information
                </h4>

            </div>
            <div class="modal-body">
                <div class="remove-loader-gear" id="loading_screen"></div>
                <?= form_open('Settings/add_company', array('id' => 'company_form', 'class' => 'form-horizontal')); ?>
                <div class="form-group"><label for="company_name" class="form-control-label col-sm-3">Referred
                        placemerit:
                    </label>
                    <div class="col-sm-9"><select name="placemerit" id="" class="form-control">
                            <option selected disabled></option>
                            <?php foreach ($placemerit as $value) : ?>
                            <option value="<?= $value ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label for="company_name" class="form-control-label col-sm-3">Company name:
                    </label>
                    <div class="col-sm-9"><input type="text" name="company_name" id="" class="form-control"></div>
                </div>
                <div class="form-group">
                    <label for="" class="form-control-label col-sm-3">Select province</label>
                    <div class="col-sm-9">
                        <select name="provCode" class="form-control">
                            <option value="">SELECT PROVINCE</option>
                            <?php if ($province != null) : ?>
                            <?php foreach ($province as $key => $row) : ?>
                            <option value="<?= $row->provCode ?>"><?= $row->provDesc ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="form-control-label col-sm-3">Select City/Municipality</label>
                    <div class="col-sm-9">
                        <select name="citymunCode" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="form-control-label col-sm-3">Select barangay</label>
                    <div class="col-sm-9">
                        <select name="brgyCode" class="form-control"></select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="company_address" class="form-control-label col-sm-3">Present address:
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="company_address" class="form-control">
                    </div>
                </div>
                <div class="form-group"><label for="manager_name" class="form-control-label col-sm-3">Manager name:
                    </label>
                    <div class="col-sm-9"><input type="text" name="manager_name" id="" class="form-control"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="btnAddCompany">Save</button>
                <button class="btn btn-default" id="btnCancelCompany">Cancel</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>