<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use function ctype_alpha;

class Alphabetic extends AbstractValidator
{
    /**
     * @var string
     */
    private $message = 'This value should be of type {{ type }}.';

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
