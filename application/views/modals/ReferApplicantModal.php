<div class="modal fade " id="referralForm">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Refer Applicant
                </h4>

            </div>
            <div class="modal-body">

                <!-- <div class="custom-content"> -->
                <div class="row">
                    <div class="col-sm-12">
                        <?= form_open('Referrals/refer_applicant', array('id' => 'referral_form', 'class' => 'form-horizontal')); ?>
                        <div class="form-group"><label class="form-control-label col-sm-4 text-right"
                                class="form-control">Applicant Name:
                            </label>
                            <div class="col-sm-8 applicant"><span id="applicantName"
                                    class="form-control applicantName"></span>
                                <input type="hidden" name="applicantID"></div>
                        </div>
                        <div class="form-group">
                            <label for="company_address" class="form-control-label text-right col-sm-4">Address:
                            </label>
                            <div class="col-sm-8">
                                <span id="address" class="form-control"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Select placemerit:</label>
                            <div class="col-sm-8"><select name="placemerit" class="form-control">
                                    <option>SELECT PLACEMERIT</option>
                                    <?php foreach ($placemerits as $merit) : ?>
                                    <option value="<?= $merit ?>"><?= $merit ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Select company:</label>
                            <div class="col-sm-8"><select name="company" class="form-control"></select></div>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Desired position:</label>
                            <div class="col-sm-8"><select name="position" class="form-control"></select></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Date referred:</label>
                            <div class="col-sm-8"><input type="date" name="dateReferred" class="form-control"></div>
                        </div>
                        <div class="form-group training-group">
                            <label for="" class="form-control-label text-right col-sm-4">Training:</label>
                            <div class="col-sm-8">
                                <label for="" class="checkbox-inline cbTesda"><input type="checkbox" name="training"
                                        id="cbTesda" value="TESDA">TESDA</label>
                                <label for="" class="checkbox-inline cbDole"><input type="checkbox" name="training"
                                        id="cbDole" value="DOLE RO">DOLE
                                    RO</label>
                                <label for="" class="checkbox-inline cbNone"><input type="checkbox" name="training"
                                        id="cbNone" value="NONE">NONE
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Remarks:</label>
                            <div class="col-sm-8"><select type="text" name="remarks" class="form-control">
                                    <option value="" selected disabled>SELECT REMARKS</option>
                                    <?php foreach ($remarks as $key => $value) : ?>
                                    <option value="<?= $value ?>"><?= $key ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="prevPosition">
                <button class="btn btn-primary" id="btnReferApplicant">Save</button>
                <button class="btn btn-default" id="btnCancelReferral">Cancel</button>
            </div>
            <?= form_close(); ?>
        </div>

        <!-- </div> -->
    </div>
</div>
</div>
</div>


<div class="modal fade" id="searchApplicant">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Search Applicant</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" placeholder="LASTNAME/FIRSTANAME" name="applicantName" id=""
                        class="form-control">
                </div>
                <div class="student-list">
                    <ul class="list-group list-group-unbordered" id="applicantSearched"></ul>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>


<div class="modal fade " id="referApplicantForm">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Refer Applicant
                </h4>

            </div>
            <div class="modal-body">

                <!-- <div class="custom-content"> -->
                <div class="row">
                    <div class="col-sm-12">
                        <?= form_open('Referrals/refer_applicant', array('id' => 'referApplicant_Form', 'class' => 'form-horizontal')); ?>
                        <div class="form-group">
                            <label class="form-control-label col-sm-4 text-right" class="form-control">Applicant Name:
                            </label>
                            <div class="col-sm-8 applicant">
                                <span id="applicant" class="form-control"></span>
                                <input type="hidden" name="applicantID">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_address" class="form-control-label text-right col-sm-4">Address:
                            </label>
                            <div class="col-sm-8">
                                <span class="form-control address"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Placemerit:</label>
                            <div class="col-sm-8">
                                <span class="form-control placemerit"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Company /
                                Establishment:</label>
                            <div class="col-sm-8"><span class="company form-control"></span></div>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Position:</label>
                            <div class="col-sm-8"><span id="position" class="form-control"></span><input type="hidden"
                                    name="position"></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Date referred:</label>
                            <div class="col-sm-8"><input type="date" name="dateReferred" class="form-control"></div>
                        </div>
                        <div class="form-group training-group">
                            <label for="" class="form-control-label text-right col-sm-4">Training:</label>
                            <div class="col-sm-8">
                                <label for="" class="checkbox-inline cbTesda"><input type="checkbox" name="training"
                                        id="cbTesda" value="TESDA">TESDA</label>
                                <label for="" class="checkbox-inline cbDole"><input type="checkbox" name="training"
                                        id="cbDole" value="DOLE RO">DOLE
                                    RO</label>
                                <label for="" class="checkbox-inline cbNone"><input type="checkbox" name="training"
                                        id="cbNone" value="NONE">NONE
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Remarks:</label>
                            <div class="col-sm-8"><select type="text" name="remarks" class="form-control">
                                    <option value="" selected disabled>SELECT REMARKS</option>
                                    <?php foreach ($remarks as $key => $value) : ?>
                                    <option value="<?= $value ?>"><?= $key ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="btnReferApplicantApplied">Save</button>
                <button class="btn btn-default" id="btnCancelReferral">Cancel</button>
            </div>
            <?= form_close(); ?>
        </div>

        <!-- </div> -->
    </div>
</div>
</div>
</div>