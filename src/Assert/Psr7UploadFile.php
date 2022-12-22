<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use Psr\Http\Message\UploadedFileInterface;
use function gettype;
use function implode;
use function in_array;
use function is_int;

class Psr7UploadFile extends AbstractValidator
{
    /**
     * @var string
     */
    private $message = 'This value should be instance of {{ class }}.';

    /**
     * @var string
     */
    private $maxSizeMessage = 'The file is too large ({{ size }} MB. Allowed maximum size is {{ limit }} MB';

    /**
     * @var string
     */
    private $mimeTypesMessage = 'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.';

    /**
     * @var int|null
     */
    private $maxSize;

    /**
     * @var array
     */
    public $mimeTypes = [];

    public function validate($value): bool
    {
        if ($value === null) {
            return true;
        }

        if (! $value instanceof UploadedFileInterface) {
            $this->error(
                $this->message, [
                    'value' => $value,
                    'class' => 'Psr\Http\Message\UploadedFileInterface',
                    'type' => gettype($value)
                ]
            );
            return false;
        }

        if (is_int($this->maxSize) && $value->getSize() > $this->maxSize) {
            $this->error(
                $this->maxSizeMessage, [
                    'value' => $value,
                    'size' => $value->getSize(),
                    'limit' => $this->maxSize,
                ]
            );
            return false;
        }

        if ($this->mimeTypes !== [] && ! in_array($value->getClientMediaType(), $this->mimeTypes)) {
            $this->error(
                $this->mimeTypesMessage, [
                    'value' => $value,
                    'type' => $value->getClientMediaType(),
                    'types' => implode(', ', $this->mimeTypes),
                ]
            );
            return false;
        }
        return true;
    }

    public function maxSize(int $maxSize): self
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    /**
     * @param array<string> $mimeTypes
     * @return $this
     */
    public function mimeTypes(array $mimeTypes): self
    {
        $this->mimeTypes = $mimeTypes;
        return $this;
    }


    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function maxSizeMessage(string $maxSizeMessage): self
    {
        $this->maxSizeMessage = $maxSizeMessage;
        return $this;
    }

    public function mimeTypesMessage(string $mimeTypesMessage): self
    {
        $this->mimeTypesMessage = $mimeTypesMessage;
        return $this;
    }
}
