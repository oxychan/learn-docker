<?php

namespace Tests\Feature;

use App\Models\MenuGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MenuGroupTest extends TestCase
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

    public function test_superadmin_can_access_group_list()
    {
        // pergi ke laman /menu-management/menu-group
        $response = $this->get(MenuGroupTest::BASE_URL)->assertStatus(200);

        // memastikan pada view terdapat variabel menuGrups
        $response->assertViewHas('menuGroups');

        // memastikan terdapat header table seperti berikut
        $response->assertSeeText('#');
        $response->assertSeeText('Name');
        $response->assertSeeText('Permission');
        $response->assertSeeText('Action');

        // memastikan terdapat data yang sesuai
        $response->assertSeeText('1');
        $response->assertSeeText('Dashboard');
        $response->assertSeeText('dashboard');
        $response->assertSeeText('Edit');
        $response->assertSeeText('Delete');
    }

    public function test_superadmin_can_access_paging_menu_list()
    {
        // add more \data
        MenuGroup::factory()->count(3)->create();

        // pergi ke laman /menu-management/menu-group
        $response = $this->get(MenuGroupTest::BASE_URL)->assertStatus(200);

        // memastikan pada view terdapat variabel menuGrups
        $response->assertViewHas('menuGroups');

        // terdpat pagination
        $response->assertSeeTextInOrder(["1", "2"]);

        //b uka page 2
        $response = $this->get(MenuGroupTest::BASE_URL . '?page=2');

        // karena dibatasi pagination 10, maka yang ada di page 2 adalah 11
        $response->assertSee("11");
    }

    public function test_superadmin_can_search_and_result_shown_in_list()
    {
        $response = $this->get(MenuGroupTest::BASE_URL, [
            "name" => "Role Management"
        ]);

        $response->assertStatus(200);
        $response->assertSeeText("Role Management");
        $response->assertSeeText("role.permission.management");
        $response->assertSeeText('Edit');
        $response->assertSeeText('Delete');
    }

    
}
