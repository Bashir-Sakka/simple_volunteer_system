<?php
class Beneficiary
{
    private ?int $id;
    private ?int $reason_id;
    private ?string $full_name;
    private ?string $phone;
    private ?string $child_in_kottab;
    private ?string $notes;
    private string $created_at;



    // --- Getters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReasonId(): ?int
    {
        return $this->reason_id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getChildInKottab(): ?string
    {
        return $this->child_in_kottab;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    // --- Setters ---

    // public function setId(?int $id): void {
    //     $this->id = $id;
    // }

    // public function setReasonId(?int $reason_id): void {
    //     $this->reason_id = $reason_id;
    // }

    // public function setFullName(?string $full_name): void {
    //     $this->full_name = $full_name;
    // }

    // public function setPhone(?string $phone): void {
    //     $this->phone = $phone;
    // }

    // public function setChildInKottab(bool $child_in_kottab): void {
    //     $this->child_in_kottab = $child_in_kottab;
    // }

    // public function setNotes(?string $notes): void {
    //     $this->notes = $notes;
    // }

    // public function setCreatedAt(string $created_at): void {
    //     $this->created_at = $created_at;
    // }
}