<?php
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $action = $_POST['action'];
    switch ($action) {
        case 'update_support':

            updateSupport();
            break;
        case 'update_reason':
            updateReason();
            break;
        case 'add_reason':
            addReason();
            break;
        case 'add_support':
            addSupport();
            break;
        case 'delete_reason':
            deleteReason();
            break;
        case 'delete_support':
            deleteSupport();
            break;
        default:
            http_response_code(404);
            exit('Unknown action');
    }
}

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'en' ? 'ltr' : 'rtl' ?>">

<head>
    <meta charset="UTF-8">
    <title>Admin Settings</title>
    <!-- Bootstrap CSS -->
    <link href="node_modules\bootstrap\dist\css\bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="node_modules\bootstrap-icons\font\bootstrap-icons.min.css" rel="stylesheet">


</head>

<body>
    <?php genNav() ?>
    <div class="container mt-5">
        <h2><?= __('Settings') ?></h2>
        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="reasons-tab" data-bs-toggle="tab" href="#reasons"
                    role="tab"><?= __('Reasons') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="types-tab" data-bs-toggle="tab" href="#types"
                    role="tab"><?= __('Support Types') ?></a>
            </li>
        </ul>

        <div class="tab-content mt-4">
            <!-- ---------------- REASONS ---------------- -->
            <div class="tab-pane fade show active" id="reasons" role="tabpanel">
                <h4><?= __('Add New Reason') ?></h4>
                <form method="POST" class="mb-4" action="#">
                    <input type="text" name="reason_name" class="form-control mb-2"
                        placeholder=" <?= __('Reason Name') ?>" required>

                    <button class="btn btn-success" name="action" value="add_reason"> <?= __('Add Reason') ?></button>
                </form>

                <h4><?= __('Existing Reasons') ?></h4>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $reasons = getAllReasons();
                        foreach ($reasons as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r->getName()) ?></td>
                                <td>
                                    <!-- Edit button triggers modal -->
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editReasonModal<?= $r->getId() ?>">

                                        <?= __('Edit') ?>
                                    </button>

                                    <!-- Delete button -->
                                    <form method="post" action="#" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_reason">
                                        <input type="hidden" name="id" value="<?= $r->getId() ?>">
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">
                                            <?= __('Delete') ?>
                                        </button>
                                    </form>


                                    <!-- Edit Reason Modal -->
                                    <div class="modal fade" id="editReasonModal<?= $r->getId() ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="#">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <?= __('Edit Reason') ?>
                                                        </h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="reason_id" value="<?= $r->getId() ?>">
                                                        <input type="text" name="reason_name" class="form-control mb-2"
                                                            value="<?= htmlspecialchars($r->getName()) ?>" required>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="action" value="update_reason"
                                                            class="btn btn-success">
                                                            <?= __('Update') ?></button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            <?= __('Cancel') ?></button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>


            </div>



            <!-- ---------------- SUPPORT TYPES ---------------- -->
            <div class="tab-pane fade" id="types" role="tabpanel">
                <h4><?= __('Add New Support Type') ?></h4>
                <form method="POST" class="mb-4" action="#">
                    <input type="text" name="type_name" class="form-control mb-2"
                        placeholder="<?= __('Support Name') ?>" required>
                    <textarea name="type_description" class="form-control mb-2"
                        placeholder="<?= __('Description') ?>"></textarea>
                    <button class="btn btn-success" name="action" value="add_support">
                        <?= __('Add Type') ?> </button>
                </form>

                <h4><?= __('Existing Support Types') ?></h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th> <?= __('Name') ?></th>
                            <th><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $types = getSupports();
                        foreach ($types as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t->getName()) ?></td>

                                <td>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editSupportModal<?= $t->getId() ?>">

                                        <?= __('Edit') ?>
                                    </button>
                                    <form method="POST" action="#" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $t->getId() ?>">

                                        <button type="submit" name="action" value="delete_support"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <?= __('Delete') ?>
                                        </button>
                                    </form>
                                </td>
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editSupportModal<?= $t->getId() ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="#">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">
                                                        <?= __('Edit Reason') ?>
                                                    </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="hidden" name="type_id" value="<?= $t->getId() ?>">
                                                    <input type="text" name="type_name" class="form-control mb-2"
                                                        value="<?= htmlspecialchars($t->getName()) ?>" required>
                                                    <?php
                                                    $info = $t->getDesc() ?? "";

                                                    ?>

                                                    <textarea name="type_description" class="form-control mb-2"
                                                        placeholder="<?= __('Description') ?>"><?= $info ?></textarea>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="action" value="update_support"
                                                        class="btn btn-success">
                                                        <?= __('Update') ?></button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <?= __('Cancel') ?></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const tabParam = params.get('tab');
        if (tabParam) {
            document.querySelector(`[data-tab="${tabParam}"]`)?.click();
        }
    </script>
    <script src="Assets/js/index.js"></script>
    <script src="node_modules\bootstrap\dist\js\bootstrap.bundle.min.js"></script>
</body>
</body>

</html>