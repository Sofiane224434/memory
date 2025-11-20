<?php

namespace App\Models;

class CardController
{
    private string $id;
    private string $value;
    private bool $isFlipped;
    private bool $isMatched;

    public function __construct(string $id, string $value)
    {
        $this->id = $id;
        $this->value = $value;
        $this->isFlipped = false;
        $this->isMatched = false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isFlipped(): bool
    {
        return $this->isFlipped;
    }

    public function isMatched(): bool
    {
        return $this->isMatched;
    }

    public function flip(): void
    {
        $this->isFlipped = true;
    }

    public function unflip(): void
    {
        $this->isFlipped = false;
    }

    public function setMatched(): void
    {
        $this->isMatched = true;
        $this->isFlipped = true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'isFlipped' => $this->isFlipped,
            'isMatched' => $this->isMatched
        ];
    }

    public static function fromArray(array $data): self
    {
        $card = new self($data['id'], $data['value']);
        $card->isFlipped = $data['isFlipped'];
        $card->isMatched = $data['isMatched'];
        return $card;
    }
}
