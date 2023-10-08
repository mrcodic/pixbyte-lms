<?php

namespace App\Helpers;

use App\Enums\Http;

use App\Http\Traits\SendsResponse;
use Illuminate\Contracts\Support\Responsable;

class MessageResponse implements Responsable
{
    use SendsResponse;

    public function __construct(

        public readonly Http $code = Http::OK,
        public readonly string $message = 'Request completed successfully',
        public readonly array|object $body = [],
        public readonly ?string $errors = null,
    ) {
    }
}
