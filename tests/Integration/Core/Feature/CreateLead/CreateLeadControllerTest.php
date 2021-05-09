<?php

namespace Tests\Integration\Core\Feature\CreateLead;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LocalheroPortal\Core\Repository\UserRepository;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\User\User;
use Tests\Integration\IntegrationTestCase;

class CreateLeadControllerTest extends IntegrationTestCase
{

    private User $user;
    private $leadData;
    private $leadTestData;

    protected function setUp(): void
    {
        parent::setUp();
        //TODO: still necessary?
//        $this->artisanClears();
        $this->cleanup();
        $this->leadData = $this->getLeadData();
        $this->leadTestData = $this->getLeadTestData();
    }

    public function artisanClears()
    {
        Artisan::call('config:clear');
        Artisan::call('route:clear');
    }

    public function getTestUser()
    {
        $user = new User();
        $user->first_name = "TestUserFirstName";
        $user->last_name = "TestUserLastName";
        $user->email = "TestUserEmail@gmail.com";
        $user->email_verified_at = now();
        $user->password = Hash::make("TestUserPassword");
        $user->created_at = now();
        $user->updated_at = now();
        $user->is_active = 1;
        return $user;
    }

    public function createTestUser(int $role = 7)
    {
        $this->user = $this->getTestUser();
        $this->user->save();

        //Expert User = 7
        DB::table('role_user')->insert(
            ['role_id' => $role, 'user_id' => $this->user->id]
        );
    }

    public function getLeadData()
    {
        return [
            "company_name" => "TESTCOMPANY",
            "street" => "MÜHLENKAMP 3",
            "zip" => "49685",
            "city" => "BÜHREN",
            "contact_name" => "FABIANLUKASSEN",
            "phone1" => "+49 163 3006603",
            "email" => "FABIANLUKASSEN@TESTEN.DE",
            "category" => "Hotel",
            "website" => "https://www.test.de/",
            "status" => 1,
            "expert_status" => 0,
            "expert_id" => 1,
            "blocked" => 0,

        ];
    }

    public function getLeadTestData()
    {
        $leadData = $this->getLeadData();
        unset($leadData['blocked']);
        unset($leadData['expert_id']);
        unset($leadData['blocked']);
        return $leadData;
    }


    public function testAsExpert()
    {
        $this->createTestUser();

        $request = Request::create(route('api.createLead', ['user' => $this->user->id]), 'PUT', $this->leadData);
        $this->actingAs($this->user);
        app()->handle($request);

        $this->assertDatabaseHas('leads', $this->leadTestData);
    }

    public function testAsNonExpert()
    {
        $this->createTestUser(3);

        $users = new UserRepository();
        $expert = $users->getAllExperts()->first();

        $request = Request::create(route('api.createLead', ['user' => $expert->id]), 'PUT', $this->leadData);
        $this->actingAs($this->user);
        app()->handle($request);

        $this->assertDatabaseHas('leads', $this->leadTestData);

    }

    public function testWithAppointment()
    {
        $this->createTestUser();

        $closed_until = now();
        $appointment_end = Carbon::parse(now())->addHour();

        $appointmentAppendageLeadTestData = [
            "closed_until" => $closed_until->format("Y-m-d, H:i"),
            "appointment_end" => $appointment_end->format("Y-m-d, H:i"),
            "appointment_comment" => "LRoCiM1MBJ"
        ];
        $this->leadData = array_merge($this->leadData, $appointmentAppendageLeadTestData);

        $appointmentAppendageTestLeadData = [
            "status" => 5,
            "expert_status" => 1
        ];
        $this->leadTestData = array_merge($this->leadTestData, $appointmentAppendageTestLeadData);

        $testAppointment = [
            "body" => "LRoCiM1MBJ",
            "event_begin" => $closed_until->format("Y-m-d H:i:00"),
            "event_end" => $appointment_end->format("Y-m-d H:i:00"),
            "type" => "callcenter-appointment"
        ];

        $request = Request::create(route('api.createLead', ['user' => $this->user->id]), 'PUT', $this->leadData);
        $this->actingAs($this->user);
        $response = app()->handle($request);

        $this->assertDatabaseHas('leads', $this->leadTestData);
        $this->assertDatabaseHas('calendar_events', $testAppointment);
    }

    protected function tearDown(): void
    {
        $this->cleanup();
        parent::tearDown();
    }

    private function cleanup()
    {
        CalendarEvent::whereBody("LRoCiM1MBJ")->forceDelete();
        Lead::whereEmail($this->getLeadData()['email'])->forceDelete();

        $user = $this->user ?? User::whereEmail($this->getTestUser()->email)->first();
        if ($user) {
            Comment::whereUserId($user->id)->forceDelete();
            DB::table('role_user')->where('user_id', '=', $user->id)->delete();
            $user->forceDelete();
        }
    }


}