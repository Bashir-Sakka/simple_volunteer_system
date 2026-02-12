<?php
include_once 'Model/Support.php';
include_once 'Model/SupportRecord.php';


function addSupportToBeneficiary()
{




    $stmt = $GLOBALS["pdo"]->prepare("
    INSERT INTO beneficiary_supports
    (beneficiary_id, support_id, reason_id,date_given, amount)
    VALUES (?, ?, ?, ?,?)
");

    $stmt->execute([
        $_POST["b_id"],
        $_POST["support_id"],
        $_POST["r_id"],
        $_POST["date"],
        $_POST["amount"]
    ]);

    header("Location:" . $_SERVER['REQUEST_URI']);
    exit;
}


function getSupports()
{
    $stmt = $GLOBALS["pdo"]->query("SELECT * FROM supports");
    return $stmt->fetchAll(PDO::FETCH_CLASS, "Support");
}



function getBeneficiarySupports($id)
{
    // 1. Get everything in ONE query using JOIN
    $sql = "SELECT bs.*, s.name AS support_name 
            FROM beneficiary_supports bs
            JOIN supports s ON bs.support_id = s.id
            WHERE bs.beneficiary_id = ?
            ORDER BY bs.date_given DESC";

    $stmt = $GLOBALS["pdo"]->prepare($sql);
    $stmt->execute([$id]);

    $supports = [];

    // 2. Fetch directly into your Class
    while ($row = $stmt->fetchObject("SupportRecord")) {

        // Use the year for grouping
        $year = date('Y', strtotime($row->getDateGiven()));

        // 3. Store the formatted data
        $supports[$year][] = [
            'id' => $row->getId(),
            'type' => $row->support_name, // Populated by the JOIN
            'date' => date('d M Y', strtotime($row->getDateGiven())),
            'amount' => $row->getAmount()
        ];
    }

    return $supports;
}

function findSupportById($id)
{
    $stmt = $GLOBALS["pdo"]->query("SELECT * FROM supports where id=$id");
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Support");
    $row = $stmt->fetch();
    return $row ? $row : null;
}
function findRecordByID($id)
{
    $stmt = $GLOBALS["pdo"]->query("SELECT * FROM beneficiary_supports where beneficiary_id=$id");
    $stmt->setFetchMode(PDO::FETCH_CLASS, "SupportRecord");
    return $stmt->fetch();
}

function getGivenSupports()
{
    $stmt = $GLOBALS["pdo"]->query("SELECT * FROM beneficiary_supports");
    return $stmt->fetchAll(PDO::FETCH_CLASS, "SupportRecord");
}

// Add new support type
function addSupport()
{
    $name = $_POST['type_name'] ?? '';
    $description = $_POST['type_description'] ?? '';
    if ($name) {
        $stmt = $GLOBALS["pdo"]->prepare("INSERT INTO supports (name, description) VALUES (:name, :description)");
        $stmt->execute([':name' => $name, ':description' => $description]);
        header("Location:" . BASE_URL . "/settings");
        exit;
    }
}

// Update support type
function updateSupport()
{
    $stmt = $GLOBALS["pdo"]->prepare("UPDATE supports SET name=:name, description=:description WHERE id=:id");
    $stmt->execute([
        ':id' => $_POST['type_id'],
        ':name' => $_POST['type_name'],
        ':description' => $_POST['type_description']
    ]);
    header("Location:" . BASE_URL . "/settings");
    exit;

}