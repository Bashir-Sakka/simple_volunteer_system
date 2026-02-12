<?php

if (isset($_POST["submit_btn"])) {
    addBeneficiaries();

}

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'en' ? 'ltr' : 'rtl' ?>">

<head>
    <meta charset="UTF-8">
    <title><?= __("Add Beneficiary") ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="node_modules\bootstrap\dist\css\bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="node_modules\bootstrap-icons\font\bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php genNav() ?>

    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-person-plus-fill"></i>
                            <?= __("Add New Beneficiary") ?>
                        </h4>
                    </div>

                    <div class="card-body">
                        <form method="POST">

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><?= __("Full Name") ?></label>
                                <input type="text" name="full_name" class="form-control"
                                    placeholder="<?= __("Enter full name") ?>" required>
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold"><?= __("Phone Number") ?></label>
                                <input type="text" name="phone" class="form-control"
                                    placeholder="<?= __("Optional") ?>">
                            </div>

                            <!-- Reason for help -->
                            <div class="mb-3">
                                <p class="fw-semibold mb-2"><?= __("Reason For Help") ?></p>
                                <div class="btn-group gap-4 d-flex flex-wrap" role="group">
                                    <?php
                                    $reasons = getAllReasons();
                                    foreach ($reasons as $r): ?>
                                        <input type="radio" class="btn-check" name="reason_id"
                                            value="<?= htmlspecialchars($r->getId()) ?>" id="reason<?= $r->getId() ?>"
                                            required>
                                        <label class="btn btn-outline-primary" for="reason<?= $r->getId() ?>">
                                            <?= htmlspecialchars(__($r->getName())) ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Switch for Kottab -->
                            <div class="form-check form-switch mb-3">
                                <label class="fw-semibold mb-2"><?= __("Son in Kottab") ?></label>
                                <input class="form-check-input" type="checkbox" name="son_in_kottab" value="1"
                                    id="check_kottab" switch>
                            </div>

                            <!-- Son name -->
                            <div class="mb-3" id="son_name">
                                <label class="form-label fw-semibold"><?= __("Son Name") ?></label>
                                <input type="text" name="son_name" class="form-control"
                                    placeholder="<?= __("Enter full name") ?>">
                            </div>

                            <!-- Notes on person -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold"><?= __("Notes On Person") ?></label>
                                <textarea name="personal_notes" class="form-control" rows="4"
                                    placeholder="<?= __("Additional information (optional)") ?>"></textarea>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="<?= BASE_URL ?>/" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> <?= __("Cancel") ?>
                                </a>
                                <button type="submit" name="submit_btn" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i> <?= __("Save Beneficiary") ?>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="Assets/Js/index.js"></script>
    <script src="node_modules\bootstrap\dist\js\bootstrap.bundle.min.js"></script>
</body>

</html>