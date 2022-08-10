<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header"><a class="navbar-brand" href="<?= base_url('jobs') ?>">Akini Job Site</a>

                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navcol-1"><span
                        class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span
                        class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navcol-1">
                <?php if ($this->session->userdata('logged_in') && $this->session->userdata('position') == 'applicant') : ?>
                <ul class="nav navbar-nav pull-right">
                    <li><a href="<?= base_url('resumes/profile') ?>">Welcome <?= $this->session->userdata('name') ?></a>
                    </li>
                    <button class="btn btn-danger navbar-btn btnJobSeekerLogout">Logout</button>
                </ul>
                <?php else : ?>
                <ul class="nav navbar-nav pull-right">
                    <li><a href="<?= base_url('login') ?>">Login</a></li>
                    <button class="btn btn-danger navbar-btn btnJobSeeker">Sign-up as JobSeeker</button>
                </ul>
                <?php endif; ?>

            </div>
        </div>
    </nav>