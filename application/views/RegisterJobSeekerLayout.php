<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1 col-sm-offset-1">
            <div class="register-job-seeker">
                <?= form_open('User_account/registerJobSeekerAcc', array('id' => 'JobSeekerRegForm')) ?>
                <h3 class="title-md">Create your account</h3>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group"><input type="email" name="email" placeholder="Email address"
                                class="form-control">
                            <span class="error-msg"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group"><input type="password" name="password" placeholder="Password"
                                class="form-control">
                            <span class="error-msg"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group"><input type="text" name="lname" placeholder="Last name"
                                class="form-control">
                            <span class="error-msg"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group"><input type="text" name="fname" placeholder="First name"
                                class="form-control">
                            <span class="error-msg"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group"><input type="text" name="contactNo" placeholder="Phone number"
                                class="form-control">
                            <span class="error-msg"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group"><select name="gender" class="form-control">
                                <option value="">GENDER</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <span class="error-msg"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group"><input type="text" name="address" placeholder="Current address"
                                class="form-control">
                            <span class="error-msg"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="provCode" class="form-control">
                                <option value="" selected disabled>Select province</option>
                                <?php foreach ($province as $key => $row) : ?>
                                <option value="<?= $row->provCode ?>"><?= strtoupper($row->provDesc) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-msg"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="citymunCode" class="form-control"></select>
                            <span class="error-msg"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="brgyCode" class="form-control"></select>
                            <span class="error-msg"></span>
                        </div>
                    </div>

                </div>
                <button class="btn btn-block btn-danger btnJobSeekerRegister">Sign up</button>
            </div>
        </div>
    </div>
</div>