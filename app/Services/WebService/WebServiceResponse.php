<?php

namespace App\Services\WebService;

class WebServiceResponse
{
    public function __construct(
        public bool $success,
        public int $statusCode,
        public string $message,
    ) {}
}
