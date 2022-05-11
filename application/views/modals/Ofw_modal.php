<div class="modal fade" id="ofw_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header with-border">
                <button class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">OFW Form</h3>
            </div>
            <div class="modal-body">
                <?= form_open('Ofw/add_ofw', array('id' => 'ofw_form')); ?>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Lastname:</label>
                            <input type="text" name="lname" id="" class="form-control">
                        </div>

                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Firstname:</label>
                            <input type="text" name="fname" id="" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">MI:</label>
                            <input type="text" name="mi" id="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Barangay:</label>
                            <select name="brgyCode" class="form-control">
                                <option value="" disabled selected>SELECT BRGY</option>
                                <?php foreach ($brgy as $key => $row) : ?>
                                <option value="<?= $row->brgyCode ?>"><?= strtoupper($row->brgyDesc) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Country of Deployment:</label>
                            <input type="text" name="country" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Year From - To:</label>
                            <input type="text" name="yearFromTo" class="form-control"
                                data-inputmask='"mask":"9999-9999"' data-mask>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class=" form-control-label">Email adress:</label>
                            <input type="text" name="email" class="form-control">

                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="" class="form-control-label">Facebook account:</label>
                            <input type="text" name="fbAcc" class="form-control">
                        </div>
                    </div>
                </div>
                <h4 class="modal-title">Dependent's Personal Information</h4>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Name:</label>
                            <input type="text" name="dependentName" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Contact no. :</label>
                            <input type="text" name="contactNo" data-inputmask='"mask":"9999-9999-999"'
                                class="form-control" data-mask>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="" class="form-control-label">Facebook account:</label>
                            <input type="text" name="dependentfbAcc" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="ofwID">
                <button class="btn btn-primary btn-sm btnSaveOfw">Save</button>
                <button class="btn btn-default btn-sm btnCancelOfw">Cancel</button>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>