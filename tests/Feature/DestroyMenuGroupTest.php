<?php

namespace Tests\Feature;

use App\Models\MenuGroup;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DestroyMenuGroupTest extends TestCase
{
    use RefreshDatabase;

    const BASE_URL = '/menu-management/menu-group';

    /**
     * A basic feature test example.
     *
     * @return void
     */

     public function setUp(): void
     {
        parent::setUp();
        $this->actingAs(User::find(1));
     }
     
    public function test_delete_menu_groups()
    {
        // buat data baru dengan factory
        $menuGroup = MenuGroup::factory()->create();
 
        $this->delete(DestroyMenuGroupTest::BASE_URL . '/' . $menuGroup->id)->assertStatus(302);
        $this->assertDatabaseMissing('menu_groups', $menuGroup->toArray());
    }
}
