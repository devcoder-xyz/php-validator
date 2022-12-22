<?php

namespace Test\DevCoder\Validator\Helper;

final class Request
{

    public static function create(array $postData): \Psr\Http\Message\ServerRequestInterface
    {
        $request = new class implements \Psr\Http\Message\ServerRequestInterface {

            private $postData = [];

            public function getProtocolVersion()
            {
                // TODO: Implement getProtocolVersion() method.
            }

            public function withProtocolVersion($version)
            {
                // TODO: Implement withProtocolVersion() method.
            }

            public function getHeaders()
            {
                // TODO: Implement getHeaders() method.
            }

            public function hasHeader($name)
            {
                // TODO: Implement hasHeader() method.
            }

            public function getHeader($name)
            {
                // TODO: Implement getHeader() method.
            }

            public function getHeaderLine($name)
            {
                // TODO: Implement getHeaderLine() method.
            }

            public function withHeader($name, $value)
            {
                // TODO: Implement withHeader() method.
            }

            public function withAddedHeader($name, $value)
            {
                // TODO: Implement withAddedHeader() method.
            }

            public function withoutHeader($name)
            {
                // TODO: Implement withoutHeader() method.
            }

            public function getBody()
            {
                // TODO: Implement getBody() method.
            }

            public function withBody(\Psr\Http\Message\StreamInterface $body)
            {
                // TODO: Implement withBody() method.
            }

            public function getRequestTarget()
            {
                // TODO: Implement getRequestTarget() method.
            }

            public function withRequestTarget($requestTarget)
            {
                // TODO: Implement withRequestTarget() method.
            }

            public function getMethod()
            {
                // TODO: Implement getMethod() method.
            }

            public function withMethod($method)
            {
                // TODO: Implement withMethod() method.
            }

            public function getUri()
            {
                // TODO: Implement getUri() method.
            }

            public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false)
            {
                // TODO: Implement withUri() method.
            }

            public function getServerParams()
            {
                // TODO: Implement getServerParams() method.
            }

            public function getCookieParams()
            {
                // TODO: Implement getCookieParams() method.
            }

            public function withCookieParams(array $cookies)
            {
                // TODO: Implement withCookieParams() method.
            }

            public function getQueryParams()
            {
                // TODO: Implement getQueryParams() method.
            }

            public function withQueryParams(array $query)
            {
                // TODO: Implement withQueryParams() method.
            }

            public function getUploadedFiles()
            {
                return [];
            }

            public function withUploadedFiles(array $uploadedFiles)
            {
                // TODO: Implement withUploadedFiles() method.
            }

            public function getParsedBody()
            {
                return $this->postData;
            }

            public function withParsedBody($data)
            {
                $this->postData = $data;
            }

            public function getAttributes()
            {
                // TODO: Implement getAttributes() method.
            }

            public function getAttribute($name, $default = null)
            {
                // TODO: Implement getAttribute() method.
            }

            public function withAttribute($name, $value)
            {
                // TODO: Implement withAttribute() method.
            }

            public function withoutAttribute($name)
            {
                // TODO: Implement withoutAttribute() method.
            }
        };
        $request->withParsedBody($postData);
        return $request;
    }
}
