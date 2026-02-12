<?php
include_once 'Model/Beneficiary.php';

$perPage = 9;
$GLOBALS["perPage"] = $perPage;
function getBeneficiariesCount()
{
    $countStmt = $GLOBALS["pdo"]->query("
    SELECT COUNT(*) AS count
    FROM beneficiaries 
");
    $row = $countStmt->fetch(PDO::FETCH_ASSOC);
    return (int) $row['count'];
}
function totalPages()
{
    $totalRows = getBeneficiariesCount();


    return $totalPages = ceil($totalRows / $GLOBALS["perPage"]);

}
function queryWithPage($page)
{
    $query = $_GET;
    $query['page'] = $page;
    return '?' . http_build_query($query);
}
function checkNameExists(string $name): ?string
{
    $sql = "SELECT COUNT(*) FROM beneficiaries WHERE full_name = :name";
    $stmt = $GLOBALS["pdo"]->prepare($sql);
    $stmt->execute(['name' => $name]);

    if ($stmt->fetchColumn() > 0) {
        return "⚠️ This name already exists.";
    }

    return null; // no warning
}
function addBeneficiaries()
{
    // 1. Sanitize inputs
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone'] ?? '');
    $reasonId = (int) $_POST['reason_id'];
    $sonInKottab = isset($_POST['son_in_kottab']) ? 1 : 0;
    $sonName = $sonInKottab ? trim($_POST['son_name']) : null;
    $personalNotes = trim($_POST['personal_notes'] ?? '');

    // 2. Basic validation
    $errors = [];

    if ($fullName === '') {
        $errors[] = "Full name is required.";
    }
    $warning = checkNameExists($fullName);
    if ($warning) {
        echo $warning;
        exit;
    }
    if ($sonInKottab && $sonName === '') {
        $errors[] = "Son name is required if son is in kottab.";
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location:" . BASE_URL . "/add_beneficiary");
        exit;
    }

    // 3. Insert into database

    $stmt = $GLOBALS["pdo"]->prepare("
INSERT INTO beneficiaries
(full_name, phone, reason_id, child_in_kottab, notes, created_at)
VALUES
(:full_name, :phone, :reason_id, :son_name, :personal_notes, NOW())
");

    $stmt->execute([
        ':full_name' => $fullName,
        ':phone' => $phone,
        ':reason_id' => $reasonId,

        ':son_name' => $sonName,
        ':personal_notes' => $personalNotes,

    ]);

    $_SESSION['success'] = "Beneficiary added successfully.";
    header("Location:" . BASE_URL . "/add_beneficiary");
    exit;

}
function updateBeneficiary()
{

    $id = (int) $_POST['id'];
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $child = trim($_POST['child_in_kottab']);
    $reasonId = (int) $_POST['reason_id'];

    $stmt = $GLOBALS["pdo"]->prepare("
UPDATE beneficiaries
SET
full_name = ?,
phone = ?,
child_in_kottab = ?,
reason_id = ?
WHERE id = ?
");

    $stmt->execute([
        $fullName,
        $phone ?: null,
        $child ?: null,
        $reasonId,
        $id
    ]);

    header("Location:" . BASE_URL . "/view_beneficiary?id=" . $id);
    exit;
}
function findBeneficiaryById($id)
{
    $stmt = $GLOBALS["pdo"]->prepare("SELECT * FROM beneficiaries WHERE id = ?");
    $stmt->execute([$id]);

    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Beneficiary');

    return $stmt->fetch();
}

function getAllBeneficiaries(): array
{
    // Use the global PDO instance
    $stmt = $GLOBALS["pdo"]->prepare("SELECT * FROM beneficiaries");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Beneficiary');
}
function getBeneficiaries($page)
{
    $page = max(1, $page);

    $offset = ($page - 1) * $GLOBALS["perPage"];

    $sql = "
SELECT b.*
FROM beneficiaries b
ORDER BY b.full_name
LIMIT ? OFFSET ?
";

    $stmt = $GLOBALS["pdo"]->prepare($sql);
    $stmt->bindValue(1, $GLOBALS["perPage"], PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Beneficiary');

}

function findChildIdByFather($fatherName)
{
    $sql = "SELECT
father.full_name AS father_name,
child.id AS child_id,
child.full_name AS child_name
FROM beneficiaries AS father
INNER JOIN beneficiaries AS child
ON father.child_in_kottab = child.full_name
WHERE father.full_name = ?";

    $stmt = $GLOBALS["pdo"]->prepare($sql);
    $stmt->execute([$fatherName]);

    // fetch() returns one row (Saad's info)
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function hasFather($name)
{
    $sql = "SELECT
father.full_name AS father_name,
child.id AS child_id,
child.full_name AS child_name
FROM beneficiaries AS father
INNER JOIN beneficiaries AS child
ON father.child_in_kottab = child.full_name
WHERE child.full_name = ?";

    $stmt = $GLOBALS["pdo"]->prepare($sql);
    $stmt->execute([$name]);

    // fetch() returns one row (Saad's info)
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


?>