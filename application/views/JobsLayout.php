<div class="col-sm-9 ">
    <h4>Job Hirings in Pagadian City <?= (is_numeric($this->uri->segment(2))) ? true : false ?> </h4>
    <div class="row job-header">
        <div class="col-md-5 col-sm-6">
            <h6>Position</h6>
        </div>
        <div class="col-md-3 col-sm-6">
            <h5>Location</h5>
        </div>
        <div class="col-md-2 col-sm-6">
            <h5>Company/Establishment</h5>
        </div>
        <div class="col-md-2 col-sm-6">
            <h5>Date</h5>
        </div>
    </div>
    <?php if ($job_vacancy != null) : ?>
    <?php foreach ($job_vacancy as $key => $row) : ?>
    <div class="well well-sm job-browse-card">
        <div class="row">
            <div class="col-md-5 col-sm-6">

                <h5><a class="position"
                        href="<?= strtolower(base_url("jobs/view/$row->id/" . str_replace(' ', '-', $row->position) . "/" . str_replace(' ', '-', $row->name))) ?>"
                        data-id="<?= $row->id ?>"><?= ucwords($row->position) ?></a>
                </h5>
                <?= word_limiter($row->requirements, 10, '...') ?>
            </div>
            <div class="col-md-3 col-sm-6">
                <?= ucwords(strtolower("$row->brgyDesc, $row->citymunDesc")) ?>
                <br> <small><?= ucwords("$row->address") ?></small>
            </div>
            <div class="col-md-2 col-sm-6"><?= strtoupper($row->name) ?></div>
            <div class="col-md-2 col-sm-6"><?= date("F d, Y", strtotime($row->dateSolicited)) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <nav>
        <?= $pagination_links ?>
    </nav>
</div>
</div>
</div>

<footer class="footer">
    <div class="footer-bg">
        <strong>Copyright &copy; <?php echo date('Y'); ?></strong> All rights
        reserved.
    </div>
</footer>