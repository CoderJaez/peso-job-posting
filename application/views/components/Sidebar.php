<body class="hold-transition skin-red sidebar-mini">

    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>LT</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>PESO</b> </span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">



                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <img src="<?php echo ($this->session->userdata('image_dir') != null) ? base_url($this->session->userdata('image_dir')) : base_url('assets/uploads/user.png'); ?>"
                                    class="user-image" alt="User Image">
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs"><?= strtoupper($this->session->userdata('name')) ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="<?php echo ($this->session->userdata('image_dir') != null) ? base_url($this->session->userdata('image_dir')) : base_url('assets/uploads/user.png'); ?>"
                                        class="img-circle" alt="User Image">

                                    <p>
                                        <?= strtoupper($this->session->userdata('name')) ?>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 col-xs-offset-4 text-center">
                                            <a href="<?= base_url('logout') ?>">Log out</a>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </li>
                                <!-- Menu Footer-->

                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->

                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="<?= ($this->session->userdata('image_dir') != null) ? base_url($this->session->userdata('image_dir')) : base_url('assets/uploads/user.png'); ?>"
                            class="user-image img-circle" alt="<?= strtoupper($this->session->userdata('name')) ?>">
                    </div>
                    <div class="pull-left info">
                        <p style="font-size: 12px !important"><?= strtoupper($this->session->userdata('name')) ?></p>
                    </div><br>
                </div>
                <!-- search form -->

                <!-- /.search form -->
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MAIN NAVIGATION</li>

                    <?php if ($this->session->userdata('position') != 'applicant') : ?>

                    <?php if (isset($dashboard) && $dashboard) : ?>
                    <li <?php echo (isset($dashboardClicked) && $dashboardClicked == true) ? 'class="active"' : ''; ?>>
                        <a href="<?php echo base_url('dashboard') ?>">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (isset($job_solicitation) && $job_solicitation == true) : ?>
                    <li
                        <?php echo (isset($job_solicitationClicked) && $job_solicitationClicked == true) ? 'class="active"' : ''; ?>>
                        <a href="<?php echo base_url('job_solicitation') ?>">
                            <i class="fa fa-file"></i> <span>Job Solicitation</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($ApplicantsModule) && $ApplicantsModule == true) : ?>
                    <li
                        <?php echo (isset($ApplicantsClicked) && $ApplicantsClicked == true) ? 'class="treeview active"' : 'class="treeview"' ?>>
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span>Applicants</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (isset($applicant) && $applicant == true) : ?>
                            <li
                                <?php echo (isset($applicantClicked) && $applicantClicked == true) ? 'class="active"' : ''; ?>>
                                <a href="<?= base_url('applicant') ?>">Applicants list</a></li>
                            <li
                                <?php echo (isset($applicantAppliedClicked) && $applicantAppliedClicked == true) ? 'class="active"' : ''; ?>>
                                <a href="<?= base_url('applicant/applied') ?>">Applicants applied</a></li>
                            <?php endif; ?>
                            <?php if (isset($placement) && $placement == true) : ?>
                            <li
                                <?php echo (isset($placementClicked) && $placementClicked == true) ? 'class="active"' : ''; ?>>
                                <a href="<?= base_url('placement') ?>">Placement</a></li>
                            <?php endif; ?>
                            <?php if (isset($referral) && $referral == true) : ?>
                            <li
                                <?php echo (isset($referralClicked) && $referralClicked == true) ? 'class="active"' : ''; ?>>
                                <a href="<?= base_url('referrals') ?>">
                                    <span>Referrals</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if (isset($ofw) && $ofw == true) : ?>
                    <li <?php echo (isset($ofwClicked) && $ofwClicked == true) ? 'class="active"' : ''; ?>>
                        <a href="<?= base_url('ofw') ?>">
                            <i class="fa fa-globe"></i>
                            <span>OFW</span>
                        </a>
                    </li>
                    <?php endif; ?>


                    <?php if (isset($report) && $report == true) : ?>
                    <li
                        <?php echo (isset($ReportsClicked) && $ReportsClicked == true) ? 'class="treeview active"' : 'class="treeview"' ?>>
                        <a href="#">
                            <i class="fa fa-file"></i> <span>Reports</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li <?= (isset($referral_report) && $referral_report == true) ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('reports/referral') ?>">Referral report</a>
                            </li>
                            <li
                                <?= (isset($job_solicitation_report) && $job_solicitation_report == true) ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('reports/job_solicitation') ?>">Job Solicitation</a>
                            </li>
                            <li <?= (isset($placement_report) && $placement_report == true) ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('reports/placement') ?>">Placement report</a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <?php if (isset($settings) && $settings == true) : ?>
                    <li
                        <?php echo (isset($SettingsClicked) && $SettingsClicked == true) ? 'class="treeview active"' : 'class="treeview"' ?>>
                        <a href="#">
                            <i class="fa fa-gear"></i> <span>Settings</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li <?= (isset($company) && $company == true) ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('settings/company') ?>">Company / Establishment</a>
                            </li>
                            <li <?= (isset($position) && $position == true) ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('settings/job_position') ?>">Job position</a>
                            </li>
                            <li <?= (isset($user_account) && $user_account == true) ? 'class="active"' : '' ?>>
                                <a href="<?= base_url('User_account') ?>">User Acount</a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php else : ?>
                    <li <?= (isset($profile) && $profile) ? 'class="active"' : '' ?>><a
                            href="<?= base_url('resumes/profile') ?>"><i class="fa fa-user-o"></i> My Profile</a></li>
                    <li <?= (isset($job_application) && $job_application == true) ? 'class ="active"' : '' ?>><a
                            href="<?= base_url('jobs/job-application') ?>"><i class="fa fa-file-archive-o"></i> My Job
                            Applications
                        </a></li>
                    <li><a href="<?= base_url() ?>"><i class="fa fa-file"></i> Search jobs</a></li>
                    <li <?= (isset($account_settings) && $account_settings) ? 'class="active"' : '' ?>><a
                            href="<?= base_url('applicant/account') ?>"><i class="fa fa-gear"></i> Account
                            Settings</a>
                    </li>

                    <li><a href="<?= base_url('logout') ?>"><i class="fa fa-power-off"></i> Logout</a>
                    </li>
                    <?php endif; ?>

                    <!-- CHECKS IF USER TYPE IS NOT EITHER APPLICANT OR EMPLOYER -->
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>