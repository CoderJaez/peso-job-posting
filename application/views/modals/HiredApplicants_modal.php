<div class="modal fade " id="hired_applicantForm">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Refer Applicant
                </h4>

            </div>
            <div class="modal-body">
                <!-- loader -->
                <!-- <div class="custom-content"> -->
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <?= form_open('Hired_applicants/hire_newApplicant', array('id' => 'hiring_form', 'class' => 'form-horizontal')); ?>
                        <div class="form-group">
                            <label class="form-control-label col-sm-4 text-right" class="form-control">Hiring status:
                            </label>
                            <div class="col-sm-8">
                                <label for="" class="checkbox-inline hiring_status"><input type="radio"
                                        name="hiring_status" id="cbReffered" value="referred">REFFERED</label>

                                <label for="" class="checkbox-inline hiring_status"><input type="radio"
                                        name="hiring_status" value="walk-in">WALK-IN
                                </label>
                            </div>
                        </div>
                        <div class="form-group"><label class="form-control-label col-sm-4 text-right"
                                class="form-control">Applicant Name:
                            </label>
                            <div class="col-sm-8 applicant"><span id="applicantName" class="form-control "> CLICKED HERE
                                    TO SEARCH</span>
                                <input type="hidden" name="applicantID"></div>
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
                            <label for="" class="form-control-label text-right col-sm-4">Job Vacancy:</label>
                            <div class="col-sm-8"><select name="position" class="form-control">
                                    <option disabled>SELECT POSITION</option>
                                </select></div>
                        </div>
                        <div class="form-group">
                            <label for="" class="form-control-label text-right col-sm-4">Date hired:</label>
                            <div class="col-sm-8"><input type="date" name="dateHired" class="form-control"></div>
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
                <div class="modal-footer">
                    <input type="hidden" name="placementID">
                    <input type="hidden" name="prevPosition">
                    <button class="btn btn-primary" id="btnHiredApplicant">Save</button>
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