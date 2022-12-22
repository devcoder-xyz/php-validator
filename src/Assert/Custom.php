<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

class Custom extends AbstractValidator
{
    /**
     * @var string
     */
    private $message = '"{{ value }}" is not valid';
    /**
     * @var callable
     */
    private $callable;

    public function __construct(callable $validate)
    {
        $this->callable = $validate;
    }

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        $callable = $this->callable;
        if ($callable($value) === false) {
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
