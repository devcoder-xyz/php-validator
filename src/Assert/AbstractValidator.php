<?php

namespace DevCoder\Validator\Assert;

/**
 * Class AbstractValidator
 *
 * This abstract class implements the ValidatorInterface and provides basic error handling functionality.
 */
abstract class AbstractValidator implements ValidatorInterface
{
    protected ?string $error = null;

    /**
     * Get the error message
     *
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }


    /**
     * Set the error message by replacing placeholders with values from the context array
     *
     * @param string $message The error message with placeholders
     * @param array $context An associative array containing values to replace the placeholders
     * @return void
     */
    protected function error(string $message, array $context): void
    {
        $replace = [];
        foreach ($context as $key => $value) {
            if (is_object($value)) {
                $value = method_exists($value, '__toString') ? (string)$value : get_class($value);
            } elseif (is_array($value)) {
                $value = json_encode($value);
            } else {
                $value = (string)$value;
            }
            $replace['{{ ' . $key . ' }}'] = $value;
        }

        $this->error = strtr($message, $replace);
    }
}
