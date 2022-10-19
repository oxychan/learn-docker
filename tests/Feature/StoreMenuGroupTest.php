<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreMenuGroupTest extends TestCase
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

     public function test_superadmin_can_load_create_page()
     {
        $response = $this->get(StoreMenuGroupTest::BASE_URL . '/create')->assertStatus(200);

        $response->assertViewHas('permissions');

        $response->assertSeeText('Create Menu Group');
        $response->assertSeeText('Name');
        $response->assertSeeText('Icon Name');
        $response->assertSeeText('Permission Name');
        $response->assertSeeText('Submit');
        $response->assertSeeText('Cancel');
     }

     public function test_all_fields_are_required()
     {
        $response = $this->post(StoreMenuGroupTest::BASE_URL, [
            'name' => '',
            'icon' => '',
            'permission_name' => '',
        ])->assertStatus(302);

        $response->assertSessionHasErrors(
            [
                "name" => "The name field is required.",
                'icon' => 'The icon field is required.',
                'permission_name' => 'The permission name field is required.',
                
            ]
        );
     }

     public function test_name_fields_is_required()
     {
        $response = $this->post(StoreMenuGroupTest::BASE_URL, [
            'name' => '',
            'icon' => 'testing',
            'permission_name' => 'testing',
        ])->assertStatus(302);

        $response->assertSessionHasErrors(
            [
                "name" => "The name field is required."
                
            ]
        );
     }

     public function test_icon_fields_is_required()
     {
        $response = $this->post(StoreMenuGroupTest::BASE_URL, [
            'name' => 'testing',
            'icon' => '',
            'permission_name' => 'testing',
        ])->assertStatus(302);

        $response->assertSessionHasErrors(
            [
                "icon" => "The icon field is required."
                
            ]
        );
     }

     public function test_permission_name_fields_is_required()
     {
        $response = $this->post(StoreMenuGroupTest::BASE_URL, [
            'name' => 'testing',
            'icon' => 'testing',
            'permission_name' => '',
        ])->assertStatus(302);

        $response->assertSessionHasErrors(
            [
                "permission_name" => "The permission name field is required."
                
            ]
        );
     }

     public function test_store_menu_group_successfully()
     {
        $response = $this->post(StoreMenuGroupTest::BASE_URL, [
            'name' => 'testing',
            'permission_name' => 'testing',
            'icon' => 'testing',
        ])->assertStatus(302);

        // redirect ke index
        $response->assertRedirect(StoreMenuGroupTest::BASE_URL);

        // cek alert success
        $response->assertSessionHas("success", "Data berhasil ditambahkan");
       
        // cek apakah sudah masuk ke database
        $this->assertDatabaseHas('menu_groups', [
            'name' => 'testing',
            'permission_name' => 'testing',
            'icon' => 'testing',
        ]);
     }
}
