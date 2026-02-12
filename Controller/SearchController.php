<?php
require_once 'Config/global_path.php';
$pdo = require 'Config/db.php';
require_once BASE_PATH . 'Controller\BeneficiaryController.php';
require_once BASE_PATH . 'Controller\ReasonController.php';


$search = $_GET['search'] ?? '';

if ($search === '') {
    exit;
}

$stmt = $GLOBALS["pdo"]->prepare("
    SELECT b.*
    FROM beneficiaries b
    WHERE b.full_name LIKE ?
    ORDER BY b.created_at DESC
    LIMIT 20
");

$stmt->execute(["%$search%"]);
$beneficiaries = $stmt->fetchAll(PDO::FETCH_OBJ);

if (!$beneficiaries) {
    echo '<p class="text-muted">No results found</p>';
    exit;
}

foreach ($beneficiaries as $b):
    ?>
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card card-hover shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold mb-2">
                    <?= htmlspecialchars($b->full_name) ?>
                </h5>

                <?php if (!empty($b->child_in_kottab)): ?>
                    <p class="text-muted mb-2">
                        Child name:
                        <span class="badge bg-danger">
                            <?= htmlspecialchars($b->child_in_kottab) ?>
                        </span>
                    </p>
                <?php endif; ?>

                <p class="text-muted mb-2">
                    <?= htmlspecialchars(findReasonById($b->reason_id)->getName()) ?>
                </p>

                <p class="text-muted mb-2">
                    <?= htmlspecialchars($b->created_at) ?>
                </p>

                <?php if (!empty($b->notes)): ?>
                    <span class="badge bg-success mt-2">
                        <?= htmlspecialchars($b->notes) ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="card-footer bg-white border-0">
                <a href="../Views/view_beneficiary.php?id=<?= $b->id ?>" class="btn btn-sm btn-outline-primary w-100">
                    View Details
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>