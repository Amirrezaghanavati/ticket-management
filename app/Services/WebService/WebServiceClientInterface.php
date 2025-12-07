<?php

namespace App\Services\WebService;

use App\Models\Ticket;

interface WebServiceClientInterface
{
    public function send(Ticket $ticket): WebServiceResponse;
}
