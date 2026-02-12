<?php


$b_id = $_GET["id"] ?? null;

if (!$b_id) {
    die("Beneficiary ID is required.");
}

$ben = findBeneficiaryById($b_id);
$r_id = $ben->getReasonId();

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'update_beneficiary':
            updateBeneficiary();
            break;
        case 'add_support':
            addSupportToBeneficiary();
            break;
        case 'delete_beneficiary_support':
            deleteBeneficiarySupport();
            break;
        case 'delete_yearly_support':
            deleteBeneficiaryYearSupport();
            break;
        case 'delete_beneficiary':
            deleteBeneficiary();
            break;
        default:
            http_response_code(404);
            exit('Unknown action');
    }
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'ar' ? 'rtl' : 'ltr' ?>">

<head>
    <meta charset="UTF-8">
    <title>Beneficiary Details - <?= htmlspecialchars($ben->getFullName()) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            transition: .2s ease-in-out;
        }

        .support-year {
            background-color: #e9ecef;
            padding: 10px 15px;
            margin-top: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php genNav(); ?>

    <div class="container mt-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h4 class="fw-bold mb-1"><?= htmlspecialchars($ben->getFullName()) ?></h4>
                        <p class="text-primary mb-2">
                            <i class="bi bi-info-circle me-1"></i>
                            <?= findReasonById($ben->getReasonId())->getName() ?>
                        </p>
                    </div>
                    <div class="text-end">
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $ben->getPhone()) ?>" target="_blank"
                            class="btn btn-sm btn-outline-success">
                            <span class="fw-bold me-1"><?= htmlspecialchars($ben->getPhone()) ?></span>
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <?php if ($ben->getChildInKottab()): ?>
                            <p class="mb-1 text-muted small"><?= __('child name') ?></p>
                            <?php $exists = findChildIdByFather($ben->getFullName()); ?>
                            <?php if (!$exists): ?>
                                <span class="badge bg-secondary" data-bs-toggle="tooltip" title="<?= __("No record") ?>">
                                    <?= htmlspecialchars($ben->getChildInKottab()) ?>
                                </span>
                            <?php else: ?>
                                <a href="view_beneficiary.php?id=<?= $exists["child_id"] ?>"
                                    class="badge bg-danger text-decoration-none">
                                    <?= htmlspecialchars($ben->getChildInKottab()) ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php

                        $exists = hasFather($ben->getFullName());

                        if ($exists): ?>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="custom-tooltip" data-bs-title="<?= __("No record") ?>">

                                <?= htmlspecialchars($exists["father_name"]) ?>
                            </button>
                        <?php endif; ?>
                    </div>



                    <div class="col-md-6 d-md-flex justify-content-md-end align-items-center mt-3 mt-md-0 gap-2">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#editSupportModal"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i> <?= __('Edit') ?>
                        </button>
                        <form method="POST" class=" me-2" action="#" style="display:inline;">

                            <input type="hidden" name="action" value="delete_beneficiary">



                            <input type="hidden" name="beneficiary_id" value="<?= $_GET["id"] ?>">

                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure?')">

                                <i class="bi bi-trash3"></i><?= __('Delete') ?>

                            </button>

                        </form>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#addSupportModal"
                            class="btn btn-sm btn-success">
                            <i class="bi bi-plus-circle"></i> <?= __('Add Support') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $supports = getBeneficiarySupports($ben->getId());
        if (!empty($supports)):
            foreach ($supports as $year => $yearSupports): ?>
                <div class="support-year d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-calendar3 me-2 ms-2"></i><?= $year ?></span>
                    <form method="post" onsubmit="return confirm('Delete all records for <?= $year ?>?')">
                        <input type="hidden" name="action" value="delete_yearly_support">
                        <input type="hidden" name="year" value="<?= htmlspecialchars($year) ?>">
                        <input type="hidden" name="beneficiary_id" value="<?= $b_id ?>">
                        <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="bi bi-trash3"></i></button>
                    </form>
                </div>

                <div class="row g-3 mt-1">
                    <?php foreach ($yearSupports as $s): ?>
                        <div class="col-md-4">
                            <div class="card card-hover shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold text-dark"><?= htmlspecialchars($s['type']) ?></h6>
                                    <div class="small text-muted">
                                        <div class="mb-1"><i class="bi bi-clock me-1"></i> <?= $s['date'] ?></div>
                                        <div><i class="bi bi-cash-stack me-1"></i> <?= $s['amount'] ?></div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent border-0 d-flex justify-content-end gap-2">
                                    <form method="post" onsubmit="return confirm('Delete this support entry?')">
                                        <input type="hidden" name="action" value="delete_beneficiary_support">
                                        <input type="hidden" name="type" value="<?= $s["id"] ?>">
                                        <input type="hidden" name="beneficiary_id" value="<?= $b_id ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach;
        else: ?>
            <div class="alert alert-info mt-4">No support data found for this beneficiary.</div>
        <?php endif; ?>
    </div>
    <div class="modal fade" id="addSupportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="#">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Add Support') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><?= __('Type') ?></label>
                        <select name="support_id" class="form-select" required>
                            <?php foreach (getSupports() as $t): ?>
                                <option value="<?= htmlspecialchars($t->getId()) ?>">
                                    <?= htmlspecialchars($t->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('Date') ?></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><?= __('Amount') ?></label>
                        <div class="input-group">
                            <input type="text" name="amount" class="form-control" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="b_id" value="<?= $b_id ?>">
                <input type="hidden" name="r_id" value="<?= $r_id ?>">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" name="action" value="add_support"
                        class="btn btn-success"><?= __('Add Support') ?></button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editSupportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="#">
                <div class="modal-header">
                    <h5 class="modal-title"><?= __('Edit Beneficiary') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $ben->getId() ?>">

                    <div class="mb-3">
                        <label class="form-label"><?= __('Full Name') ?></label>
                        <input type="text" name="full_name" class="form-control"
                            value="<?= htmlspecialchars($ben->getFullName()) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?= __('Phone') ?></label>
                        <input type="text" name="phone" class="form-control"
                            value="<?= htmlspecialchars($ben->getPhone()) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?= __('Child in Kottab') ?></label>
                        <input type="text" name="child_in_kottab" class="form-control"
                            value="<?= htmlspecialchars($ben->getChildInKottab()) ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?= __('Reason') ?></label>
                        <select name="reason_id" class="form-select" required>
                            <?php foreach (getAllReasons() as $r): ?>
                                <option value="<?= $r->getId() ?>" <?= $r->getId() == $ben->getReasonId() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($r->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer gap-2"> <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"><?= __('Cancel') ?></button>
                    <button type="submit" name="action" value="update_beneficiary"
                        class="btn btn-primary"><?= __('Save changes') ?></button>
                </div>
            </form>
        </div>
    </div>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
</body>

</html>