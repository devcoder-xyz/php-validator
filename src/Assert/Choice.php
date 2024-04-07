<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use function in_array;

final class Choice extends AbstractValidator
{
    private string $message = '{{ value }} is not a valid email address.';
    private array $choices;

    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }

    public function validate($value): bool
    {
        if (in_array($value, $this->choices) === false) {
            $this->error($this->message, ['value' => $value]);
            return false;
        }

        return true;
    }

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }
}
