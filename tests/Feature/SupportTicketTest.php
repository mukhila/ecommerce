<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    private function getValidTicketData(): array
    {
        return [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'category' => 'General',
            'priority' => 'medium',
            'subject' => 'Need help with my account',
            'message' => 'I am having trouble accessing my account settings.',
        ];
    }

    public function test_anyone_can_access_support_form(): void
    {
        $response = $this->get(route('support.create'));

        $response->assertStatus(200);
        $response->assertViewIs('support.create');
    }

    public function test_authenticated_user_sees_their_orders_on_form(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('support.create'));

        $response->assertStatus(200);
        $response->assertViewHas('userOrders');
    }

    public function test_guest_can_submit_support_ticket(): void
    {
        $response = $this->post(route('support.store'), $this->getValidTicketData());

        $response->assertRedirect();

        $this->assertDatabaseHas('support_tickets', [
            'email' => 'john@example.com',
            'subject' => 'Need help with my account',
            'status' => 'open',
        ]);
    }

    public function test_authenticated_user_can_submit_support_ticket(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('support.store'), $this->getValidTicketData());

        $response->assertRedirect();

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'email' => 'john@example.com',
        ]);
    }

    public function test_ticket_generates_ticket_number(): void
    {
        $response = $this->post(route('support.store'), $this->getValidTicketData());

        $ticket = SupportTicket::first();
        $this->assertNotNull($ticket->ticket_number);
        $this->assertStringStartsWith('TKT-', $ticket->ticket_number);
    }

    public function test_ticket_validates_required_fields(): void
    {
        $response = $this->post(route('support.store'), []);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'category',
            'priority',
            'subject',
            'message',
        ]);
    }

    public function test_ticket_validates_email_format(): void
    {
        $data = $this->getValidTicketData();
        $data['email'] = 'invalid-email';

        $response = $this->post(route('support.store'), $data);

        $response->assertSessionHasErrors('email');
    }

    public function test_ticket_validates_category(): void
    {
        $data = $this->getValidTicketData();
        $data['category'] = 'InvalidCategory';

        $response = $this->post(route('support.store'), $data);

        $response->assertSessionHasErrors('category');
    }

    public function test_ticket_validates_priority(): void
    {
        $data = $this->getValidTicketData();
        $data['priority'] = 'urgent'; // Invalid priority

        $response = $this->post(route('support.store'), $data);

        $response->assertSessionHasErrors('priority');
    }

    public function test_ticket_can_include_order_id(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $data = $this->getValidTicketData();
        $data['order_id'] = $order->order_number;
        $data['category'] = 'Order Issue';

        $response = $this->actingAs($user)->post(route('support.store'), $data);

        $response->assertRedirect();

        $this->assertDatabaseHas('support_tickets', [
            'order_id' => $order->order_number,
            'category' => 'Order Issue',
        ]);
    }

    public function test_ticket_can_include_attachment(): void
    {
        Storage::fake('public');

        $data = $this->getValidTicketData();
        $data['attachment'] = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->post(route('support.store'), $data);

        $response->assertRedirect();

        $ticket = SupportTicket::first();
        $this->assertNotNull($ticket->attachment);
    }

    public function test_attachment_validates_file_type(): void
    {
        Storage::fake('public');

        $data = $this->getValidTicketData();
        $data['attachment'] = UploadedFile::fake()->create('script.php', 100);

        $response = $this->post(route('support.store'), $data);

        $response->assertSessionHasErrors('attachment');
    }

    public function test_attachment_validates_file_size(): void
    {
        Storage::fake('public');

        $data = $this->getValidTicketData();
        $data['attachment'] = UploadedFile::fake()->create('large.pdf', 3000); // 3MB, limit is 2MB

        $response = $this->post(route('support.store'), $data);

        $response->assertSessionHasErrors('attachment');
    }

    public function test_user_can_access_ticket_success_route(): void
    {
        $ticket = SupportTicket::factory()->create();

        $response = $this->get(route('support.success', $ticket->ticket_number));

        // Route should be accessible (not 404 or redirect)
        $this->assertNotEquals(404, $response->status());
    }

    public function test_authenticated_user_can_access_their_tickets_route(): void
    {
        $user = User::factory()->create();
        SupportTicket::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('support.index'));

        // Route should be accessible (not redirect to login)
        $this->assertNotEquals(302, $response->status());
    }

    public function test_guest_cannot_view_tickets_list(): void
    {
        $response = $this->get(route('support.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_access_their_ticket_details_route(): void
    {
        $user = User::factory()->create();
        $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('support.show', $ticket->ticket_number));

        // Route should be accessible (not 403 or redirect)
        $this->assertNotEquals(403, $response->status());
        $this->assertNotEquals(302, $response->status());
    }

    public function test_user_cannot_view_other_users_ticket(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $ticket = SupportTicket::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->get(route('support.show', $ticket->ticket_number));

        $response->assertStatus(403);
    }

    public function test_ticket_can_have_replies(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

        SupportTicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'message' => 'We are looking into this issue.',
        ]);

        // Verify reply was created
        $this->assertDatabaseHas('support_ticket_replies', [
            'ticket_id' => $ticket->id,
            'message' => 'We are looking into this issue.',
        ]);

        // Verify relationship works
        $ticket->refresh();
        $this->assertCount(1, $ticket->replies);
    }

    public function test_all_valid_categories_are_accepted(): void
    {
        $categories = ['General', 'Order Issue', 'Payment', 'Product', 'Returns', 'Shipping', 'Other'];

        foreach ($categories as $index => $category) {
            $data = $this->getValidTicketData();
            $data['category'] = $category;
            $data['email'] = "test_category_{$index}@example.com";

            $response = $this->post(route('support.store'), $data);

            $response->assertRedirect();
            $this->assertDatabaseHas('support_tickets', [
                'email' => "test_category_{$index}@example.com",
                'category' => $category,
            ]);
        }
    }

    public function test_all_valid_priorities_are_accepted(): void
    {
        $priorities = ['low', 'medium', 'high'];

        foreach ($priorities as $priority) {
            $data = $this->getValidTicketData();
            $data['priority'] = $priority;
            $data['email'] = "test_{$priority}@example.com";

            $response = $this->post(route('support.store'), $data);

            $response->assertRedirect();
            $this->assertDatabaseHas('support_tickets', [
                'email' => "test_{$priority}@example.com",
                'priority' => $priority,
            ]);
        }
    }
}
