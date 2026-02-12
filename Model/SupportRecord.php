<?php

class SupportRecord
{
    private int $id;
    private int $beneficiary_id;
    private int $support_id;
    private int $reason_id;
    private float $amount;
    private string $date_given;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBeneficiaryId(): int
    {
        return $this->beneficiary_id;
    }

    public function getSupportId(): int
    {
        return $this->support_id;
    }

    public function getReasonId(): int
    {
        return $this->reason_id;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDateGiven(): string
    {
        return $this->date_given;
    }
}