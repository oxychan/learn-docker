<?php

namespace Tests\Feature;

use App\Models\MenuGroup;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMenuGroupTest extends TestCase
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

     public function test_superadmin_can_load_update_page()
     {
        $data = MenuGroup::find(1);
        $response = $this->get(UpdateMenuGroupTest::BASE_URL . '/' . $data->id . '/edit', [
            ['id' => $data->id]
        ])->assertStatus(200);

        $response->assertViewHas('menuGroup');

        $response->assertSeeText('Edit Menu Group');
        $response->assertSeeText('Name');

        $response->assertSeeText('Submit');
        $response->assertSeeText('Cancel');
     }

     public function test_name_field_is_required()
     {
        $data = MenuGroup::find(1);
        $response = $this->put(UpdateMenuGroupTest::BASE_URL . '/' . $data->id, [
            ['name' => ""]
        ])->assertStatus(302);

        $response->assertSessionHasErrors(["name" => "The name field is required."]);
     }

     public function test_update_menu_group_successfully()
     {
        $data = MenuGroup::find(1);
        $this->put(UpdateMenuGroupTest::BASE_URL . '/' . $data->id, [
            ['name' => "Updated"]
        ])->assertStatus(302);

        $this->assertDatabaseHas('menu_groups', $data->toArray());
     }
}
