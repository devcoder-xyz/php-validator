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

class Validation
{
    /**
     * @var array<string,array>
     */
    private $validators;

    /**
     * @var array<string,string>
     */
    private $errors = [];

    /**
     * @var array
     */
    private $data = [];

    public function __construct(array $fieldValidators)
    {
        foreach ($fieldValidators as $field => $validators) {
            if (!is_array($validators)) {
                $validators = [$validators];
            }
            $this->addValidator($field, $validators);
        }
    }

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

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    /**
     * @param string $field
     * @param array<ValidatorInterface> $validators
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
