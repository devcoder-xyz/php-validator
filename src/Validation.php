<?php

namespace DevCoder\Validator;

use DevCoder\Validator\Assert\ValidatorInterface;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use function array_map;
use function array_merge;
use function get_class;
use function gettype;
use function is_array;
use function is_object;
use function is_string;
use function sprintf;
use function trim;

final class Validation
{
    /**
     * @var array<string,array>
     */
    private array $validators;

    /**
     * @var array<string,string>
     */
    private array $errors = [];

    private array $data = [];

    public function __construct(array $fieldValidators)
    {
        foreach ($fieldValidators as $field => $validators) {
            if (!is_array($validators)) {
                $validators = [$validators];
            }
            $this->addValidator($field, $validators);
        }
    }

    /**
     * Validate the server request data.
     *
     * @param ServerRequestInterface $request The server request to validate
     * @return bool
     */
    public function validate(ServerRequestInterface $request): bool
    {
        $data = array_map(function ($value) {
            if (is_string($value) && empty(trim($value))) {
                return null;
            }
            return $value;
        }, array_merge($request->getParsedBody(), $request->getUploadedFiles()));

        return $this->validateArray($data);
    }

    /**
     * Validate an array of data using a set of validators.
     *
     * @param array $data The array of data to be validated
     * @return bool
     */
    public function validateArray(array $data): bool
    {
        $this->data = $data;

        /**
         * @var $validators array<ValidatorInterface>
         */
        foreach ($this->validators as $field => $validators) {
            if (!isset($this->data[$field])) {
                $this->data[$field] = null;
            }

            foreach ($validators as $validator) {
                if ($validator->validate($this->data[$field]) === false) {
                    $this->addError($field, (string)$validator->getError());
                }
            }

        }
        return $this->getErrors() === [];
    }

    /**
     * @return array<string,string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Add an error for a specific field.
     *
     * @param string $field The field for which the error occurred
     * @param string $message The error message
     * @return void
     */
    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    /**
     * Add a validator for a specific field.
     *
     * @param string $field The field to validate
     * @param array<ValidatorInterface> $validators The validators to apply
     * @return void
     */
    private function addValidator(string $field, array $validators): void
    {
        foreach ($validators as $validator) {
            if (!$validator instanceof ValidatorInterface) {
                throw new InvalidArgumentException(sprintf(
                    $field . ' validator must be an instance of ValidatorInterface, "%s" given.',
                    is_object($validator) ? get_class($validator) : gettype($validator)
                ));
            }

            $this->validators[$field][] = $validator;
        }
    }
}
