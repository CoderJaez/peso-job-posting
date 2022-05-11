<div class="col-sm-5 wrap">
    <?php if ($job_data != null) : ?>
    <h1><?= strtoupper($job_data->position); ?></h1>
    <h5><?= ucwords($job_data->company) ?></h5>
    <span><?= ucwords(strtolower("$job_data->address, $job_data->brgyDesc, $job_data->citymunDesc, $job_data->provDesc")) ?></span><br>
    <span>Total vacancies: <?= $job_data->vacancy - $job_data->hired ?> </span><br>
    <span>Date posted: <?= date('F d, Y', strtotime($job_data->dateSolicited)) ?></span><br>

    <h4 class="modal-header"><strong>Job Description</strong></h4>
    <p>
        <?= $job_data->job_description ?>
    </p>

    <h4 class="modal-header"><strong>Qualifications</strong></h4>
    <p>
        <?= $job_data->requirements ?>
    </p>
    <?php endif; ?>
</div>
<div class="col-sm-4">
    <h4>How to apply</h4>
    <p>Visit us at PESO Office and bring your resume: </p>
    <p>City Hall Complex, Gatas District, Pagadian City</p>

    <p><strong>OR</strong></p>
    <?php if (!$this->session->userdata('logged_in')) : ?>
    <div class="registration-form">
        <h4> Fillup the registration form to Apply</h4>
        <?= form_open('Jobs/quick_apply', array('id' => 'quickApplyForm')) ?>
        <div class="form-group">
            <label for="">Fullname</label>
            <input type="text" name="name" class="form-control" placeholder="Fullname">
        </div>
        <div class="form-group">
            <label for="">Gender</label>
            <select name="gender" class="form-control">
                <option value="" selected disabled>SELECT GENDER</option>
                <option value="Male">MALE</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
            <label for="">Mobile number</label>
            <input type="number" name="contactNo" class="form-control" placeholder="Mobile number">
        </div>
        <div class="form-group">
            <label for="">Present address</label>
            <input type="text" name="address" class="form-control"
                placeholder="House no., Subdivision,Street name, Purok/Zone">
        </div>
        <div class="form-group">
            <label for="">Current province</label>
            <select class="form-control" name="provCode">
                <option value="" selected disabled>Select province</option>
                <?php foreach ($province as $key => $row) : ?>
                <option value="<?= $row->provCode ?>"><?= strtoupper($row->provDesc) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="">Current city</label>
            <select class="form-control" name="citymunCode"></select>
        </div>
        <div class="form-group">
            <label for="">Current brgy</label>
            <select class="form-control" name="brgyCode"></select>
        </div>
        <div class="form-group">
            <label for="">Upload resume</label>
            <input type="file" name="resume" class="form-control" data-toggle="tooltip" title="Upload photo">
            <small>(Only files with .doc, .docx or .pdf less than 900kb are allowed)</small>
        </div>
        <input type="hidden" name="id">
        <div class="form-group"><button class="btn btn-sm btn-warning">Submit</button></div>
        <?= form_close() ?>
    </div>
    <?php else : ?>
    <?= ($has_applied) ? ' <button class="btn btn-warning btnApplyNow disabled" disabled>Applied</button>' : ' <button class="btn btn-warning btnApplyNow">Apply Now</button>' ?>

    <?php endif; ?>
</div>
</div>
</div>


<footer class="footer">
    <div class="footer-bg">
        <strong>Copyright &copy; <?php echo date('Y'); ?></strong> All rights
        reserved.
    </div>
</footer>