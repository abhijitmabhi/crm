<?php
//
//namespace Tests\Unit;
//
//use Illuminate\Http\Request;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use LocalheroPortal\Core\Http\Controllers\Auth\RegisterController;
//
//use Tests\TestCase;
//
//class UserTest extends TestCase
//{
//    use RefreshDatabase;
//
//    public function testStore()
//    {
//        $faker = \Faker\Factory::create();
//        $email = $faker->email;
//        $pw = $faker->password(10);
//        $request = Request::create('/register', 'POST', [
//            'name' => $faker->name(),
//            'email' => $email,
//            'password' => $pw,
//            'password_confirmation' => $pw,
//        ]);
//        $controller = new RegisterController;
//        $response = $controller->register($request);
//        $this->assertEquals(302, $response->getStatusCode());
//        $user = \LocalheroPortal\Models\User\User::where('email', $email)->first();
//        $this->assertNotNull($user);
//    }
//}
