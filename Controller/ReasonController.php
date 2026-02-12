<?php

include_once 'Model/Reason.php';
// Add new reason
function addReason()
{
    $name = $_POST['reason_name'] ?? '';

    if ($name) {
        $stmt = $GLOBALS["pdo"]->prepare("INSERT INTO support_reason (name) VALUES (:name)");
        $stmt->execute([':name' => $name]);
        header("Location:" . BASE_URL . "/settings");
        exit;
    }
}

// Update reason
function updateReason()
{
    $stmt = $GLOBALS["pdo"]->prepare("UPDATE support_reason SET name=:name WHERE id=:id");
    $stmt->execute([
        ':id' => $_POST['reason_id'],
        ':name' => $_POST['reason_name'],
    ]);
    header("Location:" . BASE_URL . "/settings");
    exit;
}


function findReasonById($id)
{
    $stmt = $GLOBALS["pdo"]->prepare("SELECT *  FROM support_reason WHERE id = ?");
    $stmt->execute([$id]);

    $stmt->setFetchMode(PDO::FETCH_CLASS, "Reason");

    return $stmt->fetch();
}


function getAllReasons()
{
    return $reasons = $GLOBALS["pdo"]->query("SELECT * FROM support_reason ")->fetchAll(PDO::FETCH_CLASS, "Reason");


}