<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use function ctype_digit;
use function is_int;
use function strval;

final class Integer extends AbstractValidator
{
    private string $invalidMessage = 'This value should be of type {{ type }}.';
    private string $minMessage = '{{ value }} should be {{ limit }} or more.';
    private string $maxMessage = '{{ value }} should be {{ limit }} or less.';
    private ?int $min = null;
    private ?int $max = null;

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (ctype_digit(strval($value)) === false) {
            $this->error($this->invalidMessage, ['value' => $value, 'type' => 'integer']);
            return false;
        }

        if (is_int($this->min) && $value < $this->min) {
            $this->error($this->minMessage, ['value' => $value, 'limit' => $this->min]);
            return false;
        }

        if (is_int($this->max) && $value > $this->max) {
            $this->error($this->maxMessage, ['value' => $value, 'limit' => $this->max]);
            return false;
        }

        return true;
    }

    public function invalidMessage(string $invalidMessage): self
    {
        $this->invalidMessage = $invalidMessage;
        return $this;
    }

    public function minMessage(string $minMessage): self
    {
        $this->minMessage = $minMessage;
        return $this;
    }

    public function maxMessage(string $maxMessage): self
    {
        $this->maxMessage = $maxMessage;
        return $this;
    }

    public function min(int $min): self
    {
        $this->min = $min;
        return $this;
    }

    public function max(int $max): self
    {
        $this->max = $max;
        return $this;
    }
}
