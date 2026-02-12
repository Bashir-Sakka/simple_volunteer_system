<?php



?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $lang === 'en' ? 'ltr' : 'rtl' ?>">

<?php $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

$beneficiaries = getBeneficiaries($_GET["page"] ?? 1);
?>

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="node_modules\bootstrap\dist\css\bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="node_modules\bootstrap-icons\font\bootstrap-icons.min.css" rel="stylesheet">


    <style>
        body {
            background-color: #f5f7fa;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            transition: .2s ease-in-out;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php genNav() ?>

    <div class="container-fluid mt-4">

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="<?php BASE_URL ?>filtering" class="text-decoration-none">
                    <div class="card bg-info card-hover  shadow-sm">
                        <div class="card-body  text-white">
                            <h6 class=" "><?= __('Total Beneficiaries') ?></h6>
                            <h3 class="fw-bold "><?= getBeneficiariesCount() ?></h3>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted"><?= __('Support Types') ?> </h6>
                        <h3 class="fw-bold"><?= count(getSupports()) ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted"><?= __('Supports Given') ?></h6>
                        <h3 class="fw-bold"><?= count(getGivenSupports()); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0"><?= __('Beneficiaries') ?></h4>
            <a href="<?php BASE_URL ?> add_beneficiary" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> <?= __('Add Beneficiary') ?>
            </a>
        </div>

        <!-- Search -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form class="row ">
                    <div class="col-md-12">
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="<?= __('Search by name') ?>...">
                    </div>

                </form>
            </div>
        </div>

        <!-- Live search results -->
        <div class="row" id="searchResults"></div>

        <div id="defaultResults">
            <!-- Beneficiaries Cards -->
            <div class="row g-4">
                <?php

                foreach ($beneficiaries as $b): ?>
                    <!-- Card -->
                    <div class="col-lg-4 col-md-6">
                        <div class="card card-hover shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2"><?= $b->getFullName() ?></h5>
                                <?php if ($b->getChildInKottab() != null): ?>
                                    <h5 class="text-muted mb-2"><?= __('child name') ?>: <span
                                            class="badge bg-danger"><?= $b->getChildInKottab() ?></span>
                                    </h5>
                                <?php endif ?>
                                <p class="text-muted mb-2"><?= $b->getReasonId() ? htmlspecialchars(findReasonById($b->getReasonId())->getName()) : '-'
                                    ?></p>
                                <p class="text-muted mb-2"><?= htmlspecialchars($b->getCreatedAt()) ?></p>
                                <?php if ($b->getNotes() != null): ?>
                                    <p class="badge bg-success  mt-2">
                                        <?= $b->getNotes() ?>
                                    </p>
                                <?php endif ?>

                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="<?= BASE_URL ?>/view_beneficiary?id=<?= $b->getId() ?>"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    <?= __('View Details') ?>
                                </a>
                            </div>
                        </div>
                    </div>

                <?php endforeach ?>

            </div>

            <!-- Pagination -->
            <nav class="mt-4">
                <ul class="pagination d-flex align-items-center justify-content-between">

                    <?php
                    $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
                    ?>
                    <!-- Next -->
                    <li class="page-item <?= $page >= totalPages() ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= queryWithPage($page + 1) ?>">Next</a>
                    </li>

                    <!-- Previous -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= queryWithPage($page - 1) ?>">Previous</a>
                    </li>



                </ul>

            </nav>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="Assets/js/index.js"></script>
    <script src="node_modules\bootstrap\dist\js\bootstrap.bundle.min.js"></script>
</body>
</body>

</html>