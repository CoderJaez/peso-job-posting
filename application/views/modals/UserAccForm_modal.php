<div class="modal fade" id="UserAccountForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header with-border">
                <button class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">User Account information</h4>
            </div>

            <div class="modal-body">
                <?= form_open('User_account/addnew_account', array('id' => 'account_form', 'class' => 'form-horizontal')) ?>
                <div class="row">
                    <div class="col-sm-9 col-sm-offset-1">
                        <div class="form-group"><label for="fullname"
                                class="form-control-label col-sm-4 text-right">Full Name:
                            </label>
                            <div class="col-sm-8"><input type="text" name="fullname" class="form-control"></div>
                        </div>

                        <div class="form-group">
                            <label for="uname" class="form-control-label text-right col-sm-4">Position:
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="position" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="uname" class="form-control-label text-right col-sm-4">Username:
                            </label>
                            <div class="col-sm-8">
                                <input type="text" name="uname" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pass" class="form-control-label text-right col-sm-4">Password:
                            </label>
                            <div class="col-sm-8">
                                <input type="password" name="pass" class="form-control">
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <th rowspan="2"></th>
                                <th rowspan="2">Modules</th>
                                <th colspan="3">Access right's</th>
                                <tr>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="access_rights">
                                <?php foreach ($modules as $key => $row) : ?>
                                <tr>
                                    <td><input type="checkbox" name="input_modules"
                                            data-module_id="<?= $row->moduleID ?>"></td>
                                    <td><?= strtoupper($row->modules) ?></td>
                                    <td><input type="checkbox" name="add"></td>
                                    <td><input type="checkbox" name="edit"></td>
                                    <td><input type="checkbox" name="delete"></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>
                        <?= form_close(); ?>
                        <div class="modal-footer">
                            <div class="btn-group pull-right">
                                <button class="btn btn-sm btn-primary btnSaveAccount">Save</button>
                                <button class="btn btn-sm btn-default btnCancelAccForm">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>