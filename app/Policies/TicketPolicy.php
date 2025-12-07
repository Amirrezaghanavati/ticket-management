<?php

namespace App\Policies;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Authenticatable $user, Ticket $ticket): bool
    {
        $userModel = $this->getUserModel($user);

        // Admin can view all tickets
        if ($userModel->isAdmin()) {
            return true;
        }
        
        // Regular users can only view their own tickets
        return $ticket->getAttribute('user_id') === $user->getAuthIdentifier();
    }

    /**
     * Determine whether the user can process the ticket (approve/reject).
     */
    public function process(Authenticatable $user, Ticket $ticket): bool
    {
        $userModel = $this->getUserModel($user);

        return match ($userModel->role) {
            UserRole::ADMIN1 => $ticket->status === TicketStatus::SUBMITTED,
            UserRole::ADMIN2 => $ticket->status === TicketStatus::APPROVED_BY_ADMIN1,
            default => false,
        };
    }

    protected function getUserModel(Authenticatable $user): User
    {
        if ($user instanceof User) {
            return $user;
        }

        return User::findOrFail($user->getAuthIdentifier());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Authenticatable $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Authenticatable $user, Ticket $ticket): bool
    {
        return $ticket->getAttribute('user_id') === $user->getAuthIdentifier();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, Ticket $ticket): bool
    {
        return $ticket->getAttribute('user_id') === $user->getAuthIdentifier();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Authenticatable $user, Ticket $ticket): bool
    {
        return $ticket->getAttribute('user_id') === $user->getAuthIdentifier();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Authenticatable $user, Ticket $ticket): bool
    {
        return $ticket->getAttribute('user_id') === $user->getAuthIdentifier();
    }
}
