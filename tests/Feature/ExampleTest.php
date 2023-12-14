<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\order;
use App\Providers\CreatedOrder;
use App\Providers\SendNotificationChef;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_orders_can_be_shipped(): void
    {
        //Event::fake();
        $response= $this->post('api/order?name=ali&address=kerbala&phone=0787',[
        "item"=> [["item_id"=> 5,"quantity"=>4,"order_id"=>1],["item_id"=>12,"quantity"=>4]]
    ]);
    //$order = order::factory()->create();
        // Perform order shipping...
        $response->assertStatus(200);
        // Event::assertListening(
        //     CreatedOrder::class,
        //     SendNotificationChef::class
        // );
        // Assert that an event was dispatched...
        //Event::assertDispatched(CreatedOrder::class);
 
        // Assert an event was dispatched twice...
        //Event::assertDispatched(CreatedOrder::class, 2);
 
        // Assert an event was not dispatched...
        // Event::assertNotDispatched(OrderFailedToShip::class);
 
        // Assert that no events were dispatched...
        //Event::assertNothingDispatched();
    }
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('api/menu');

        $response->assertStatus(200);
    }
}
