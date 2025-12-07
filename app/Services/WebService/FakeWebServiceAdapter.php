<?php

namespace App\Services\WebService;

use App\Models\Ticket;

class FakeWebServiceAdapter implements WebServiceClientInterface
{
    public function send(Ticket $ticket): WebServiceResponse
    {
        $success = (bool) random_int(0, 1);
        if ($success) {
            return new WebServiceResponse(true, 200, 'OK');
        }

        return new WebServiceResponse(false, 500, 'Failed');
    }
}
