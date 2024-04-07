<?php

declare(strict_types=1);

namespace DevCoder\Validator\Assert;

use Psr\Http\Message\UploadedFileInterface;
use function gettype;
use function implode;
use function in_array;
use function is_int;

final class Psr7UploadFile extends AbstractValidator
{
    private string $message = 'This value should be instance of {{ class }}.';
    private string $maxSizeMessage = 'The file is too large ({{ size }} MB. Allowed maximum size is {{ limit }} MB';
    private string $mimeTypesMessage = 'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.';
    private ?int $maxSize = null;
    public array $mimeTypes = [];

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
