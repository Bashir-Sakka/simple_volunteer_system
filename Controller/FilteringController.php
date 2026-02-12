<?php
include_once 'Model/Beneficiary.php';

function getFilteredBeneficiaries(array $filters)
{
    global $pdo;
    $params = [];
    $sql = "SELECT DISTINCT b.* FROM beneficiaries b";

    // Optional join only if filtering by support type or date
    $joinSupport = !empty($filters['support_id']) || !empty($filters['date']) || empty($filters);
    if ($joinSupport) {
        $sql .= " LEFT JOIN beneficiary_supports bs ON bs.beneficiary_id = b.id";
    }

    // Conditions array
    $conditions = [];

    // Reason filter
    if (!empty($filters['reason_id'])) {
        $conditions[] = "b.reason_id = :reason_id";
        $params[':reason_id'] = $filters['reason_id'];
    }

    // Support type filter
    if (!empty($filters['support_id'])) {
        $conditions[] = "bs.support_id = :support_id";
        $params[':support_id'] = $filters['support_id'];
    }

    // Date filter
    if (!empty($filters['date'])) {
        $conditions[] = "DATE(bs.date_given) = :date";
        $params[':date'] = $filters['date'];
    } elseif ($joinSupport) {
        // default: current year only if support join exists
        $conditions[] = "YEAR(bs.date_given) = :year";
        $params[':year'] = date('Y');
    }

    // Child filter
    if (!empty($filters['has_child'])) {
        $conditions[] = "b.child_in_kottab IS NOT NULL";
    }

    // Build WHERE clause
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }


    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_CLASS, Beneficiary::class);
}