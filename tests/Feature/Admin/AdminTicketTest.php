<?php

namespace Admin;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use Tests\AdminTest;

class AdminTicketTest extends AdminTest
{
    public function test_index_should_return_all_closed_tickets()
    {
        $ticket1 = Ticket::factory()->create([
            "status" => TicketStatus::Closed->value
        ]);
        $ticket2 = Ticket::factory()->create([
            "status" => TicketStatus::Waiting->value
        ]);
        $res = $this->getJson(route("v1.admin.tickets.index"));
        $res->assertDontSee($ticket1->title);
        $res->assertSee($ticket2->title);
    }

    public function test_index_should_return_all_closed_tickets_sort_by_priority()
    {
        $ticket1 = Ticket::factory()
            ->for(TicketCategory::factory()->state(["priority" => 1]), "category")
            ->create([
                "status" => TicketStatus::Waiting->value
            ]);
        $ticket2 = Ticket::factory()
            ->for(TicketCategory::factory()->state(["priority" => 2]), "category")
            ->create([
                "status" => TicketStatus::Waiting->value
            ]);
        $this->withoutExceptionHandling();
        $res = $this->getJson(route("v1.admin.tickets.index"));
        $data = $res->json();
        $this->assertEquals($data[0]["title"], $ticket2->title);
        $this->assertEquals($data[1]["title"], $ticket1->title);
    }
    public function test_answer_should_answer_ticket()
    {
        $ticket = Ticket::factory()->create();
        $data = [
            "body" => "message body"
        ];
        $res = $this->postJson(route("v1.admin.tickets.answer", $ticket), $data);
        $this->assertEquals($ticket->fresh()->status, TicketStatus::Answered->value);
        $this->assertDatabaseHas("ticket_messages", $data);
    }

    public function test_answer_should_not_answer_closed_tickets()
    {
        $ticket = Ticket::factory()->create(["status" => TicketStatus::Closed->value]);
        $data = [
            "body" => "message body"
        ];
        $this->postJson(route("v1.admin.tickets.answer", $ticket), $data);
        $this->assertDatabaseMissing("ticket_messages", $data);
    }

    public function test_close_should_close_ticket()
    {
        $ticket = Ticket::factory()->create();
        $this->postJson(route("v1.admin.tickets.close", $ticket));
        $this->assertEquals($ticket->fresh()->status, TicketStatus::Closed->value);
    }

    public function test_show_should_seen_user_ticket_messages()
    {
        $ticket = Ticket::factory()
            ->create();
        $message1 = TicketMessage::factory()
            ->for($ticket)
            ->for($ticket->user)
            ->create();
        $message2 = TicketMessage::factory()
            ->for($ticket)
            ->for($this->user)
            ->create();
        $this->assertNull($message1->fresh()->seen_at);
        $this->assertNull($message2->fresh()->seen_at);
        $this->getJson(route("v1.admin.tickets.show", $ticket));
        $this->assertNotNull($message1->fresh()->seen_at);
        $this->assertNull($message2->fresh()->seen_at);
    }
}
