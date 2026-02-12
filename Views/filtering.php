<?php

if (isset($_GET["filter-out"])) {
    $filters = [
        'reason_id' => $_GET['reason_id'] ?? null,
        'support_id' => $_GET['support_id'] ?? null,
        'search' => $_GET['search'] ?? null,
        'has_child' => $_GET['has_child'] ?? null,
        'year' => !empty($_GET['date']) ? $_GET['date'] : null,
    ];

    $beneficiaries = getFilteredBeneficiaries($filters);

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="node_modules\bootstrap\dist\css\bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="node_modules\bootstrap-icons\font\bootstrap-icons.min.css" rel="stylesheet">

    <title>Document</title>
</head>

<body>
    <?php genNav() ?>
    <div class="container-fluid mt-4">
        <div class="row">

            <!-- LEFT: Filters -->
            <div class="col-lg-3 col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header fw-bold">
                    </div>
                    <div class="text-center">
                        <?= __('Filters') ?>
                    </div>
                    <div class="card-body">
                        <form method="GET">

                            <!-- Reason -->
                            <div class="mb-3">
                                <label class="form-label"><?= __('Reason') ?></label>
                                <select name="reason_id" class="form-select">
                                    <option value=""><?= __('All') ?></option>
                                    <?php $reasons = getAllReasons();
                                    foreach ($reasons as $r): ?>
                                        <option value="<?= $r->getId() ?>"><?= $r->getName() ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <!-- Support Type -->
                            <div class="mb-3">
                                <label class="form-label"><?= __('Support Type') ?></label>
                                <select name="support_id" class="form-select">
                                    <option value=""><?= __('All') ?></option>
                                    <?php $supports = getSupports();
                                    foreach ($supports as $s): ?>
                                        <option value="<?= $s->getId() ?>"><?= $s->getName() ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <!-- child  -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="has_child" value="1"
                                    <?= !empty($_GET['has_child']) ? 'checked' : '' ?>>
                                <label class="form-check-label">
                                    <?= __('Only parents with child in kottab') ?>
                                </label>
                            </div>

                            <!-- Date range -->


                            <div class="mb-3">
                                <label class="form-label"> <?= __('Date') ?></label>
                                <input type="number" name="date" class="form-control">
                            </div>

                            <button class="btn btn-primary w-100" name="filter-out">
                                <?= __('Apply Filters') ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Data -->
            <div class="col-lg-9 col-md-8">



                <!-- Results -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>

                                <th>#</th>
                                <th><?= __('Full Name') ?></th>
                                <th><?= __('Phone') ?></th>
                                <th><?= __('Reason') ?></th>
                                <th><?= __('Child In Kottab') ?></th>
                                <th class="text-end"><?= __('Support Type') ?></th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if (empty($beneficiaries)): ?>
                                <tr>
                                    <td colspan="12" class="text-center text-muted py-4">
                                        <?= __('No beneficiaries found') ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($beneficiaries as $index => $b): ?>
                                    <?php if ($b): ?>
                                        <tr style="cursor:pointer"
                                            onclick="window.location='view_beneficiary.php?id=<?= $b->getId() ?>'">

                                            <!-- Index -->
                                            <td><?= $index + 1 ?></td>

                                            <!-- Full Name -->
                                            <td class="fw-semibold">
                                                <?= htmlspecialchars($b->getFullName()) ?>
                                            </td>

                                            <!-- Phone -->
                                            <?php if ($b->getPhone()): ?>
                                                <td class="text-muted">
                                                    <?= htmlspecialchars($b->getPhone()) ?>
                                                </td>
                                            <?php else: ?>
                                                <td class="text-muted">
                                                    N/A
                                                </td>
                                            <?php endif ?>


                                            <!-- Reason -->
                                            <td>
                                                <?php $reason = findReasonById($b->getReasonId()); ?>
                                                <?php if ($reason): ?>
                                                    <span class="badge bg-info">
                                                        <?= htmlspecialchars($reason->getName()) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"> <?= __('N/A') ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <!-- Child In Kottab -->
                                            <?php if ($b->getChildInKottab()): ?>
                                                <td class="text-muted">
                                                    <?= $b->getChildInKottab() ?>
                                                </td>
                                            <?php else: ?>
                                                <td class="text-muted">
                                                    <?= __('N/A') ?>
                                                </td>
                                            <?php endif ?>

                                            <!-- Actions -->
                                            <td class="text-end">
                                                <?php $supports = getBeneficiarySupports($b->getId());
                                                ?>

                                                <?php if ($supports != false): ?>
                                                    <?php foreach ($supports as $year => $support): ?>
                                                        <?php foreach ($support as $s): ?>
                                                            <span class="badge bg-success">
                                                                <?= $s["type"] ?>
                                                            </span>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?= __('not given any') ?></span>
                                                <?php endif; ?>

                                            </td>

                                        </tr>
                                    <?php endif ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
    <script src="../Assets/js/index.js"></script>
</body>

</html>