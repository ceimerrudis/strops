<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Enums\UserTypes;
use App\Enums\VehicleUsageTypes;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Error;
use App\Models\Vehicle;
use App\Models\Reservation;
use App\Models\VehicleUse;
use App\Models\ObjectModel;

class AutomaticFeatureTest extends TestCase
{
    use DatabaseMigrations;

    //Pārbauda vai publiskā lapa ir pieejama
    public function test_publicPageUp()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    //pieteikšanās lapas tests
    public function test_loginPageUp()
    {
        $response = $this->get('/pieteikties');
        $response->assertStatus(200);
    }

    //sākum lapas tests
    public function test_homePage()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $this->actingAs($user);
        $response = $this->get('/sakums');
        $response->assertStatus(200);
    }

    //pieteikšanās loģikas tests
    public function test_loginLogic()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $response = $this->post('/pieteikties', [
            'username' => $user->username,
            'password' => "parole",
        ]);

        $this->assertAuthenticatedAs($user);
    }

    //piekļuves nolieguma tests
    public function test_accessDenial()
    {
        $response = $this->get('/sakums');
        $response->assertRedirect('/pieteikties');
    }

    //administratora piekļuves nolieguma tests
    public function test_adminAccessDenial()
    {
        $user = User::factory()->create(['type' => UserTypes::USER->value]);
        $this->actingAs($user);
        $response = $this->get('/apskatitVisus?table=1');
        $response->assertStatus(403);
    }

    //administratora piekļuves tests
    public function test_adminAccess()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $this->actingAs($user);
        $response = $this->get('/apskatitVisus?table=1');
        $response->assertStatus(200);
    }

    //lietotāja izveides tests
    public function test_userCreation()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $this->actingAs($user);
        $response = $this->post('/izveidot', [
            'table' => 1,
            'username' => 'rudisc',
            'name' => 'Rūdis',
            'lname' => 'Ceimers',
            'password' => 'parole',
            'type' => UserTypes::ADMIN->value,
        ]);

        $response->assertRedirect('/apskatitVisus?table=1');
        $this->assertDatabaseHas('users', ['username' => 'rudisc']);
    }
    
    //lietojuma kļūdas izveides tests
    public function test_usageErrorCreation()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $vehicle = Vehicle::factory()->create(['usage_type' => VehicleUsageTypes::MOTOR_HOURS->value]);
        $object = ObjectModel::factory()->create([]);
        $this->actingAs($user);
        $this->post('/saktLietojumu', [
            'vehicle' => $vehicle->id,
            'usage' => $vehicle->usage + 1,
            'object' => $object->id,
            'comment' => null,
            'endCurrentUsage' => 'no',
        ]);

        $id =  null;
        if(VehicleUse::exists())
            $id = VehicleUse::first()->id;
        
        $this->assertDatabaseHas('errors', ['vehicle_use' => $id]);
    }

    //rezervācijas kļūdas izveides tests
    public function test_reservationErrorCreation()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $user2 = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $vehicle = Vehicle::factory()->create(['usage_type' => VehicleUsageTypes::MOTOR_HOURS->value]);
        $from = Carbon::now();
        $until = $from->copy()->addMinutes(60);
        $reservation = Reservation::factory()->create(['vehicle' => $vehicle->id, 'user' => $user2->id, 'from' => $from, 'until' => $until]);
        $object = ObjectModel::factory()->create([]);
        
        $this->actingAs($user);
        $this->post('/saktLietojumu', [
            'vehicle' => $vehicle->id,
            'usage' => $vehicle->usage + 1,
            'object' => $object->id,
            'comment' => null,
            'endCurrentUsage' => 'no',
        ]);

        $id = VehicleUse::first()->id;
        $this->assertDatabaseHas('errors', ['vehicle_use' => $id, 'reservation' => $reservation->id]);
    }

    //rezervācijas izveides tests
    public function test_reservationCreation()
    {
        $user = User::factory()->create([]);
        $vehicle = Vehicle::factory()->create([]);
        $this->actingAs($user);
        $response = $this->post('/izveidotRezervaciju', [
            'vehicle' => $vehicle->id,
            'from' => Carbon::parse('2027-05-20 09:00:00'),
            'until' => Carbon::parse('2027-05-22 13:00:00'),
        ]);
        $this->assertDatabaseHas('reservations', ['vehicle' => $vehicle->id]);
    }

    //lietojuma sākšanas tests
    public function test_usageStart()
    {
        $user = User::factory()->create([]);
        $vehicle = Vehicle::factory()->create([]);
        $object = ObjectModel::factory()->create([]);

        $this->actingAs($user);
        $this->post('/saktLietojumu', [
            'vehicle' => $vehicle->id,
            'usage' => null,
            'object' => $object->id,
            'comment' => null,
            'endCurrentUsage' => 'no',
        ]);

        $this->assertDatabaseHas('vehicle_uses', ['vehicle' => $vehicle->id]);
    }

    //lietojuma beigšanas tests
    public function test_usageEnd()
    {
        $user = User::factory()->create([]);
        $vehicle = Vehicle::factory()->create(['usage_type' => VehicleUsageTypes::DAYS->value]);
        $object = ObjectModel::factory()->create([]);
        $use = VehicleUse::factory()->create(['object' => $object->id, 'vehicle' => $vehicle->id, 'usage_before' => $vehicle->usage, 'until' => null]);

        $this->actingAs($user);
        $this->post('/beigtLietojumu', [
            'vehicle_use' => $use->id,
        ]);

        $vuse = VehicleUse::first();
        $this->assertNotEquals($vuse->until, null);
    }

    //lietojuma sākšanas ar rezervāciju tests
    public function test_usageStartWithReservation()
    {
        $user = User::factory()->create(['type' => UserTypes::ADMIN->value]);
        $vehicle = Vehicle::factory()->create(['usage_type' => VehicleUsageTypes::MOTOR_HOURS->value]);
        $until = Carbon::now()->addMinutes(60);
        $object = ObjectModel::factory()->create([]);

        $this->actingAs($user);
        $response = $this->post('/saktLietojumu', [
            'vehicle' => $vehicle->id,
            'usage' => $vehicle->usage + 1,
            'object' => $object->id,
            'comment' => null,
            'until' =>  $until,
            'endCurrentUsage' => 'no',
        ]);
        
        $this->assertDatabaseHas('vehicle_uses', ['vehicle' => $vehicle->id]);
        $this->assertDatabaseHas('reservations', ['vehicle' => $vehicle->id]);
    }
}
