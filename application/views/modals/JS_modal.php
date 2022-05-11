<div class="modal fade" id="JobSolicitation">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header with-border">
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="modal-title">Job Solicitation</h4>
            </div>
            <div class="modal-body">
                <?= form_open('JobSolicitation/add_newJS', array('id' => 'JS_form')) ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group"><label for="" class="form-control-label">Company / Establishment :
                            </label>
                            <select name="js_company" class="form-control"></select>
                        </div>
                        <div class="form-group">
                            <label for="">Position:</label>
                            <select name="js_position" class="form-control"></select><br>
                            <button class="btn btn-xs btn-warning pull-right btnAddPosition">Add new</button>
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="">Vacancy:</label>
                            <input type="number" name="vacancy" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Referrals:</label>
                            <input type="number" name="referral" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Date solicited:</label>
                            <input type="date" name="dateSolicited" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">Job description:</label><br>
                            <textarea class="form-control" name="job_desc" id="job_desc" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Qualification:</label><br>
                            <textarea class="form-control" name="job_req" id="job_req" rows="5"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="jsID">
                <button class="btn btn-sm btn-primary" id="btnAddJS">Save</button>
                <button class="btn btn-sm btn-default" id="btnCanceJS">Cancel</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>


<div class="modal fade" id="Company_Position">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header with-border">
                <button class="close" data-dismiss='modal'>&times;</button>
                <h4 class="modal-title">Add new Establishment/Company and position</h4>
            </div>
            <div class="modal-body">
                <?= form_open('JobSolicitation/addCompany_Position', array('id' => 'CompanyPosition_form')); ?>
                <div class="form-group">
                    <label for="" class="">Placemerit:</label>
                    <select name="placemerit" id="" class="form-control">
                        <option value="" disabled>SELECT PLACEMERIT</option>
                        <option value="LOCAL">LOCAL</option>
                        <option value="GOVERNMENT">GOVERNMENT</option>
                        <option value="OVERSEAS">OVERSEAS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Company:</label>
                    <div class="dropdown pull-right">
                        <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Company list
                            <span class="caret"></span> </button>
                        <ul class="dropdown-menu company_list">

                        </ul>
                    </div>
                    <input type="text" name="company" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Address:</label>
                    <input type="text" name="address" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Manager's name:</label>
                    <input type="text" name="manager" class="form-control">
                </div>

                <div class="form-group position">
                    <label for="">Position:</label>
                    <button class="btn btn-xs btn-primary pull-right btnAddMorePosition"><i class="fa fa-plus"></i></button>
                    <br>
                    <input type="text" name="position[]" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="companyID">
                <button class="btn btn-sm btn-primary">Save</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="view_js">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title company"></h4>
            </div>
            <div class="modal-body">
                <h4><span class="vacancy">2</span> &nbsp; <span class="position">Position</span></h4>
                <h4>Job description:</h4>
                <p class="job_description"></p>
                <h4>Qualifications:</h4>
                <p class="requirements"></p>
            </div>
            <div class="moda-footer"></div>
        </div>
    </div>
</div>