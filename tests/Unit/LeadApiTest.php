<?php
//
//namespace Tests\Unit;
//
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use LocalheroPortal\Callcenter\Http\Controllers\Api\LeadController;
//use LocalheroPortal\Models\Lead;
//use LocalheroPortal\Models\User\User;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//
//class LeadApiTest extends TestCase
//{
//    use RefreshDatabase;
//
//    const ELEMENT_NR = 1;
//
//    private function leadUserData() : array
//    {
//        $user = factory(User::class)->create();
//        $lead = factory(Lead::class, self::ELEMENT_NR)->create([
//            'expert_id' => $user->id
//        ])->first();
//        return [$lead, $user];
//    }
//
//    public function testIndex()
//    {
//        $this->leadUserData();
//        $request = Request::create('/api/leads', 'GET');
//        $controller = new LeadController;
//        $response = $controller->index($request);
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals(self::ELEMENT_NR, count(json_decode($response->getContent())));
//    }
//
//    public function testShow()
//    {
//        [$lead, $user] = $this->leadUserData();
//        $controller = new LeadController;
//        $response = $controller->show($lead);
//        $this->assertEquals($lead->toJson(), $response->getContent());
//    }
//
//    public function testDestroy()
//    {
//        [$lead, $user] = $this->leadUserData();
//        $this->assertNull($lead->deleted_at);
//        $controller = new LeadController;
//        $response = $controller->destroy($lead);
//        $this->assertEquals('Lead deleted', json_decode($response->getContent())->message);
//        $this->assertNotNull($lead->deleted_at);
//    }
//
//    public function testUpdate()
//    {
//        [$lead, $user] = $this->leadUserData();
//        $lead_array = $lead->toArray();
//        $this->assertDatabaseHas('leads', $lead_array);
//        $lead_array['company_name'] .= '123test';
//        Auth::login($user);
//        $request = Request::create("/api/experts/$user->id", 'POST', $lead_array);
//        $controller = new LeadController;
//        $controller->update($lead, $request);
//        $this->assertDatabaseHas('leads', $lead_array);
//    }
//}
