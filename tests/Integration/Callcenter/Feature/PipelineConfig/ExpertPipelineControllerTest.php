<?php

namespace Tests\Integration\Callcenter\Feature\PipelineConfig;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LocalheroPortal\Models\ExpertSettings;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;
use Tests\Integration\IntegrationTestCase;

class ExpertPipelineControllerTest extends IntegrationTestCase
{

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cleanup();
        $this->createTestUser();
    }

    public function getTestUser(): User
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

    public function createTestUser()
    {
        $this->user = $this->getTestUser();
        $this->user->save();

        //Expert User = 7
        DB::table('role_user')->insert(
            ['role_id' => 7, 'user_id' => $this->user->id]
        );
    }

    public function testSavePrioritizedCategories()
    {
        $requestData = [
            'expertId' => $this->user->id,
            'categories' => "Autohaus",
        ];

        $request = Request::create(route('expert.category.prioritize'), 'POST', $requestData);
        app()->handle($request);
        $this->assertDatabaseHas('expert_settings', [
            'user_id' => $this->user->id,
            'categories' => json_encode(array($requestData['categories'])),
        ]);

        $requestData['categories'] = ['Werkstatt'];
        $request = Request::create(route('expert.category.prioritize'), 'POST', $requestData);
        app()->handle($request);
        $this->assertDatabaseHas('expert_settings', [
            'user_id' => $this->user->id,
            'categories' => json_encode($requestData['categories']),
        ]);

        $request = Request::create(route('expert.category.exclude'), 'POST', $requestData);
        app()->handle($request);
        $this->assertDatabaseHas('expert_settings', [
            'user_id' => $this->user->id,
            'excluded_categories' => json_encode($requestData['categories']),
            'categories' => '[]'
        ]);
    }

    //TODO: remove duplicate code?
    public function testSaveExcludedCategories()
    {
        $requestData = [
            'expertId' => $this->user->id,
            'categories' => "Autohaus",
        ];

        $request = Request::create(route('expert.category.exclude'), 'POST', $requestData);
        app()->handle($request);
        $this->assertDatabaseHas('expert_settings', [
            'user_id' => $this->user->id,
            'excluded_categories' => json_encode(array($requestData['categories'])),
        ]);

        $requestData['categories'] = ['Werkstatt'];
        $request = Request::create(route('expert.category.exclude'), 'POST', $requestData);
        app()->handle($request);
        $this->assertDatabaseHas('expert_settings', [
            'user_id' => $this->user->id,
            'excluded_categories' => json_encode($requestData['categories']),
        ]);

        $request = Request::create(route('expert.category.prioritize'), 'POST', $requestData);
        app()->handle($request);
        $this->assertDatabaseHas('expert_settings', [
            'user_id' => $this->user->id,
            'categories' => json_encode($requestData['categories']),
            'excluded_categories' => '[]'
        ]);
    }

    protected function tearDown(): void
    {
        $this->cleanup();
        parent::tearDown();
    }

    private function cleanup()
    {
        $user = $this->user ?? User::whereEmail($this->getTestUser()->email)->first();
        if ($user) {
            ExpertSettings::where('user_id', $user->id)->forceDelete();
            DB::table('role_user')->where('user_id', '=', $user->id)->delete();
            $user->forceDelete();
        }
    }
}