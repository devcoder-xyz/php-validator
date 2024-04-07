<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use function ctype_alpha;

final class Alphabetic extends AbstractValidator
{
    private string $message = 'This value should be of type {{ type }}.';

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (ctype_alpha($value) === false) {
            $this->error($this->message, ['value' => $value, 'type' => 'alphabetic']);
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
