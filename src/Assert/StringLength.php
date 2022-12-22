<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use function is_int;
use function strlen;

class StringLength extends AbstractValidator
{
    private $invalidMessage = 'Invalid type given. String expected.';
    private $minMessage = '{{ value }} must be at least {{ limit }} characters long';
    private $maxMessage = '{{ value }} cannot be longer than {{ limit }} characters';

    /**
     * @var int|null
     */
    private $min;
    /**
     * @var int|null
     */
    private $max;

    public function validate($value): bool
    {
        if (null === $value) {
            return true;
        }

        if (! is_string($value)) {
            $this->error($this->invalidMessage, ['value' => $value]);
            return false;
        }

        if (is_int($this->min) && strlen($value) < $this->min) {
            $this->error($this->minMessage, ['value' => $value, 'limit' => $this->min]);
            return false;
        }

        if (is_int($this->max) && strlen($value) > $this->max) {
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
