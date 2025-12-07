<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct(private readonly TicketService $ticketService) {}

    public function index(): View
    {
        $tickets = $this->ticketService->getTicketsForUser(Auth::user());

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('tickets.create');
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->ticketService->createTicketForUser(
            Auth::user(),
            $request->validated('title'),
            $request->validated('message'),
            $request->file('file_url'),
        );

        return redirect()->route('tickets.index')->with('success', __('Ticket created successfully!'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket = $this->ticketService->getTicketForUser(Auth::user(), $ticket);
        $adminResponse = $this->ticketService->getAdminResponse($ticket);

        return view('tickets.show', compact('ticket', 'adminResponse'));
    }
}
