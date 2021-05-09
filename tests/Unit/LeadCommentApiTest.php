<?php
//
//namespace Tests\Unit;
//
//use Tests\TestCase;
//use Illuminate\Foundation\Testing\WithFaker;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use LocalheroPortal\Models\User\User;
//use LocalheroPortal\Models\Lead;
//use LocalheroPortal\Callcenter\Http\Controllers\Api\LeadCommentController;
//use LocalheroPortal\Models\Comment;
//use Illuminate\Support\Facades\Request;
//
//class LeadCommentApiTest extends TestCase
//{
//    use RefreshDatabase, WithFaker;
//
//    private function leadUserData() : array
//    {
//        $user = factory(User::class)->create();
//        $lead = factory(Lead::class)->create(['expert_id' => $user->id]);
//        return [$lead, $user];
//    }
//
//    public function testIndex()
//    {
//        $commentNr = 10;
//        [$lead, $user] = $this->leadUserData();
//        factory(Comment::class, 10)->create([
//            'user_id' => $user->id,
//            'lead_id' => $lead->id
//        ]);
//        $controller = new LeadCommentController;
//        $response = $controller->index($lead);
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals($commentNr, count(json_decode($response->getContent())));
//    }
//
//    // public function testShow()
//    // {
//    //     $user = factory(User::class)->create();
//    //     $lead = factory(Lead::class)->create(['expert_id' => $user->id]);
//    //     $comment = factory(Comment::class)->create([
//    //         'user_id' => $user->id,
//    //         'lead_id' => $lead->id
//    //     ]);
//    //     $controller = new LeadCommentController;
//    //     $response = $controller->show($lead);
//    // }
//
//    // public function testDestroy()
//    // {
//    //     $user = factory(User::class)->create();
//    //     $lead = factory(Lead::class)->create(['expert_id' => $user->id]);
//    //     $comment = factory(Comment::class)->create([
//    //         'user_id' => $user->id,
//    //         'lead_id' => $lead->id
//    //     ]);
//    //     $this->assertNull($comment->deleted_at);
//    //     $controller = new LeadCommentController;
//    //     $response = $controller->delete($lead);
//    // }
//
//    public function testStore()
//    {
//        [$lead, $user] = $this->leadUserData();
//        $text= $this->faker->text();
//        $request = Request::create('/api/experts/' . $user->id . '/comments', 'POST', [
//            'user_id' => $user->id,
//            'lead_id' => $lead->id,
//            'body' => $text,
//            'reason' => $this->faker->text(30)
//        ]);
//        $controller = new LeadCommentController;
//        $response = $controller->store($lead, $request);
//        $this->assertEquals(201, $response->getStatusCode());
//        $this->assertDatabaseHas('comments', ['body' => $text]);
//    }
//}
