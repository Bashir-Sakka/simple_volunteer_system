<?php

class Support
{
    private int $id;
    private string $name;
    private ?string $description;

    public function getDesc()
    {
        return $this->description;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }
}