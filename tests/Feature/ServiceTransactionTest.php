<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Sparepart;
use App\Models\ServiceTransaction;

class ServiceTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $employee = Employee::factory()->create();
        
        $this->user = User::factory()->create([
            'employees_id' => $employee->employees_id,
        ]);
    }

    public function test_can_create_service_transaction_with_existing_customer_and_vehicle()
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->customer_id]);
        $sparepart = Sparepart::factory()->create(['selling_price' => 100000]);

        $response = $this->actingAs($this->user)->postJson('/api/transactions', [
            'customer_id' => $customer->customer_id,
            'vehicle_id' => $vehicle->vehicles_id,
            'km_masuk' => '10000',
            'items' => [
                [
                    'sparepart_id' => $sparepart->sparepart_id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('service_transactions', [
            'vehicle_id' => $vehicle->vehicles_id,
            'km_masuk' => '10000',
        ]);

        $transaction = ServiceTransaction::where('vehicle_id', $vehicle->vehicles_id)->first();
        
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->transaction_id,
            'spare_part_id' => $sparepart->sparepart_id,
            'qty' => 2,
            'price' => 100000,
            'subtotal' => 200000,
        ]);
    }

    public function test_can_update_service_transaction()
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['customer_id' => $customer->customer_id]);
        
        $transaction = ServiceTransaction::create([
            'transaction_id' => 'TRX-' . time(),
            'vehicle_id' => $vehicle->vehicles_id,
            'transaction_date' => now(),
            'km_masuk' => '5000',
            'status' => 'Pending',
            'total_amount' => 0,
            'created_by' => $this->user->employees_id,
        ]);

        $sparepart = Sparepart::factory()->create(['selling_price' => 50000]);

        $response = $this->actingAs($this->user)->putJson("/api/transactions/{$transaction->transaction_id}", [
            'customer_id' => $customer->customer_id,
            'vehicle_id' => $vehicle->vehicles_id,
            'km_masuk' => '6000',
            'items' => [
                [
                    'sparepart_id' => $sparepart->sparepart_id,
                    'quantity' => 1,
                ]
            ]
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('service_transactions', [
            'transaction_id' => $transaction->transaction_id,
            'km_masuk' => '6000',
        ]);
        
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->transaction_id,
            'spare_part_id' => $sparepart->sparepart_id,
            'qty' => 1,
            'price' => 50000,
        ]);
    }
}
