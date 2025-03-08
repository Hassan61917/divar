<?php

namespace App\ModelServices\Support;

use App\Enums\TicketStatus;
use App\Events\TicketWasCreated;
use App\Exceptions\ModelException;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketService
{
    public function make(User $user, array $data): Ticket
    {
        $ticket = $user->tickets()->create($data);
        TicketWasCreated::dispatch($ticket);
        return $ticket;
    }

    public function addMessage(User $user, Ticket $ticket, string $body): TicketMessage
    {
        if ($user->is($ticket->user)) {
            $ticket->update(["status" => TicketStatus::Waiting->value]);
        }
        if (!$this->canSendMessage($ticket)) {
            throw new ModelException("message can't be sent");
        }
        return $this->sendMessage($user, $ticket, $body);
    }

    public function answer(User $user, Ticket $ticket, string $body): TicketMessage
    {
        $message = $this->addMessage($user, $ticket, $body);
        $ticket->update(["status" => TicketStatus::Answered->value]);
        return $message;
    }

    public function getTicketsFor(User $user, array $relations = []): HasMany
    {
        return $user->tickets()->with($relations);
    }

    public function getTickets(array $relations = []): Builder
    {
        return Ticket::withRelations()->with($relations);
    }

    public function getUnClosedTickets(array $relations = []): Builder
    {
        return Ticket::query()
            ->unClosed()
            ->SortByPriority()
            ->with($relations);
    }

    public function getClosedTickets(array $relations = []): Builder
    {
        return Ticket::with($relations)
            ->whereNotNull("close_at")
            ->with($relations)
            ->latest();
    }

    public function seenMessage(User $user, Ticket $ticket): void
    {
        $ticket->messages()->unSeen($user->id)->update(["seen_at" => now()]);
    }

    public function close(Ticket $ticket, ?int $rate = null): void
    {
        $ticket->update([
            "status" => TicketStatus::Closed->value,
            "rate" => $rate
        ]);
    }

    public function autoClose(Ticket $ticket): void
    {
        $time = now()->addDays($ticket->category->auto_close);
        $ticket->update([
            "close_at" => $time
        ]);
    }

    private function canSendMessage(Ticket $ticket): bool
    {
        return $ticket->status != TicketStatus::Closed->value;
    }

    private function sendMessage(User $user, Ticket $ticket, string $body): TicketMessage
    {
        return $ticket->messages()->create([
            "user_id" => $user->id,
            "body" => $body
        ]);
    }
}
