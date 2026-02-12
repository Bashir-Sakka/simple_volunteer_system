<?php
include_once 'Model/Support.php';
include_once 'Model/SupportRecord.php';
include_once 'Model/Reason.php';
include_once 'Model/Beneficiary.php';


function deleteBeneficiarySupport(
) {
    $beneficiaryId = (int) $_POST['beneficiary_id'];
    $supportId = (int) $_POST['type'];

    // 2️⃣ Delete the support record
    $stmt = $GLOBALS["pdo"]->prepare("
    DELETE FROM beneficiary_supports
    WHERE id = ? AND beneficiary_id = ?
");
    $stmt->execute([$supportId, $beneficiaryId]);

    // 3️⃣ Redirect back to beneficiary page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

}
function deleteBeneficiaryYearSupport(


) {
    $beneficiaryId = (int) $_POST['beneficiary_id'];
    $year = (int) $_POST['year'];


    // 2️⃣ Delete the support record
    $stmt = $GLOBALS["pdo"]->prepare("
    DELETE FROM beneficiary_supports
    WHERE  beneficiary_id = ?
    AND YEAR(date_given) = ?
");
    $stmt->execute([$beneficiaryId, $year]);

    // 3️⃣ Redirect back to beneficiary page
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

}


// Delete reason
function deleteReason()
{
    $stmt = $GLOBALS["pdo"]->prepare("DELETE FROM support_reason WHERE id=:id");
    $stmt->execute([':id' => $_POST['id']]);
    header("Location:" . BASE_URL . "/settings");
    exit;
}


// Delete support type
function deleteSupport()
{
    $stmt = $GLOBALS["pdo"]->prepare("DELETE FROM supports WHERE id=:id");
    $stmt->execute([':id' => $_POST['id']]);
    header("Location:" . BASE_URL . "/settings");
    exit;
}// Delete beneficiary
function deleteBeneficiary()
{
    $stmt = $GLOBALS["pdo"]->prepare("DELETE FROM beneficiaries WHERE id=:id");
    $stmt->execute([':id' => $_POST['beneficiary_id']]);
    header("Location:" . BASE_URL . "/");
    exit;
}