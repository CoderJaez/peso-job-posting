<div class="container-fluid" style="margin-top:50px">
    <div class="filter-jobs">
        <h4>Filter job vacancies</h4>
        <div class="category">
            <select name="job_category" class="form-control">
                <option value="">Job Category</option>
                <?php if ($total_jobs != null) : ?>
                <?php foreach ($total_jobs as $key => $row) : ?>
                <option value="<?= base_url("jobs/" . strtolower($row->referred_placemerit)) ?>">
                    <?= $row->referred_placemerit ?><span>
                        (<?= $row->total ?>)</span></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="category">
            <select name="job_location" class="form-control">
                <option value="">Location</option>
                <?php $total = 0;
                $placemerit = null;
                if ($total_jobs_location != null) : ?>
                <?php foreach ($total_jobs_location as $key => $row) : ?>
                <?php if ($row->citymunCode == "097322") : ?>
                <option
                    value="<?= base_url("jobs/" . ((isset($row->referred_placemerit) && $row->referred_placemerit != null) ?   strtolower($row->referred_placemerit) : 'all-jobs') . "/" . strtolower(preg_replace(array('/Pob./', '/[()]/', '/\s+/'), '', $row->brgyDesc))) . "/" . $row->brgyCode ?>">
                    <?= $row->brgyDesc  ?></a><span>
                        (<?= $row->total ?>)</span></li>
                    <?php else : ?>
                    <?php
                                            $total += $row->total;
                                            $placemerit = (isset($row->referred_placemerit)) ? strtolower($row->referred_placemerit) : 'all-jobs';
                                            ?>
                    <?php endif; ?>
                </option>
                <?php endforeach; ?>
                <?php if ($total != 0) : ?>
                <optiion value="<?= base_url('jobs/' . strtolower($placemerit)) . '/outside-pagadian/075566' ?>">
                    Outside
                    Pagadian City</a> (<?= $total ?>)
                    </option>
                    <?php endif; ?>
                    <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 job-categories">
            <h4>Job Category</h4>
            <ul class="list-group">
                <?php if ($total_jobs != null) : ?>
                <?php foreach ($total_jobs as $key => $row) : ?>
                <li class="list-group-item"><a
                        href="<?= base_url("jobs/" . strtolower($row->referred_placemerit)) ?>"><?= $row->referred_placemerit ?></a><span>
                        (<?= $row->total ?>)</span></li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <h4>Location</h4>
            <ul class="list-group">
                <?php $total = 0;
                $placemerit = null;
                if ($total_jobs_location != null) : ?>
                <?php foreach ($total_jobs_location as $key => $row) : ?>
                <?php if ($row->citymunCode == "097322") : ?>
                <li class="list-group-item"><a
                        href="<?= base_url("jobs/" . ((isset($row->referred_placemerit) && $row->referred_placemerit != null) ?   strtolower($row->referred_placemerit) : 'all-jobs') . "/" . strtolower(preg_replace(array('/Pob./', '/[()]/', '/\s+/'), '', $row->brgyDesc))) . "/" . $row->brgyCode ?> "><?= $row->brgyDesc  ?></a><span>
                        (<?= $row->total ?>)</span></li>
                <?php else : ?>
                <?php
                                        $total += $row->total;
                                        $placemerit = (isset($row->referred_placemerit)) ? strtolower($row->referred_placemerit) : 'all-jobs';
                                        ?>
                <?php endif; ?>

                <?php endforeach; ?>
                <?php if ($total != 0) : ?>
                <li class="list-group-item"><a
                        href="<?= base_url('jobs/' . strtolower($placemerit)) . '/outside-pagadian/075566' ?>">Outside
                        Pagadian City</a> (<?= $total ?>)</li>
                <?php endif; ?>
                <?php endif; ?>
            </ul>

        </div>