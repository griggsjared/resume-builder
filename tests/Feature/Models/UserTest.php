<?php

namespace Tests\Feature\Models;

use App\Models\Enums\UserRole;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumn('users', 'id'));
        $this->assertTrue(Schema::hasColumn('users', 'first_name'));
        $this->assertTrue(Schema::hasColumn('users', 'last_name'));
    }

    /** @test */
    public function full_name_matches_first_and_last()
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals($user->full_name, $user->first_name.' '.$user->last_name);
    }

    /** @test */
    public function can_generate_role_labels()
    {
        $superAdmin = User::factory()->superAdmin()->create();
        $basic = User::factory()->basic()->create();

        $this->assertEquals($superAdmin->role_label, UserRole::SuperAdmin->label());
        $this->assertEquals($basic->role_label, UserRole::Basic->label());
    }

    /** @test */
    public function has_relationships()
    {
        $user = User::factory()
            ->has(Subject::factory(5), 'subjects')
            ->create();

        $this->assertCount(5, $user->subjects);
    }
}
