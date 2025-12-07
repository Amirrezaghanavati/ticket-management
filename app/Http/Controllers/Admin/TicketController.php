<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveTicketRequest;
use App\Http\Requests\Admin\RejectTicketRequest;
use App\Models\Ticket;
use App\Services\Admin\AdminTicketService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct(
        private readonly AdminTicketService $ticketService
    ) {}

    public function index(): View
    {
        $tickets = $this->ticketService->getTicketsWithPagination(
            Auth::user(),
            15
        );

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket = $this->ticketService->getTicketForView(Auth::user(), $ticket);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function approve(ApproveTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->ticketService->processAction(
            Auth::user(),
            $ticket,
            'approve',
            $request->validated('message')
        );

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', __('Ticket approved successfully!'));
    }

    public function reject(RejectTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->ticketService->processAction(
            Auth::user(),
            $ticket,
            'reject',
            $request->validated('message')
        );

        return redirect()
            ->route('admin.tickets.show', $ticket)
            ->with('success', __('Ticket rejected successfully!'));
    }
}
