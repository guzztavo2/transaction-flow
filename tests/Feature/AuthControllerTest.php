<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    // use RefreshDatabase;

    private array $pessoal_information_to_test = [
        'name' => 'Fulano de Tal', 'email' => 'fulano@exemplo.com',
        'password' => 'senhaSegura123', 'confirm_password' => 'senhaSegura123',
        'bank' => 'Banco Teste', 'agency' => '001', 'number_account' => '123456'
    ];
    
    private array $pessoal_information_to_test_2 = [
        'name' => 'Fulano de Tal', 'email' => 'fulano1@exemplo.com',
        'password' => 'senhaSegura123', 'confirm_password' => 'senhaSegura123',
        'bank' => 'Banco Teste', 'agency' => '001', 'number_account' => '123454'
    ];

   

    private User $user;
    private string $accesToken;

    #[Test]
    public function test_all_routes_auth_controller()
    {
        $registerResponse = $this->register_user_with_valid_datas($this->pessoal_information_to_test);
        $this->register_user_with_valid_datas($this->pessoal_information_to_test_2);
        // $this->accesToken = $this->login_user_with_valid_data($this->pessoal_information_to_test);
        // $getMeResponse = $this->get_user_me($this->accesToken);
        // $changePasswordResponse = $this->change_password();
        // $resetPasswordResponse = $this->reset_password();
    }

    public function register_user_with_valid_datas(array $user_to_created)
    {
        $response = $this->postJson('api/auth/register', $user_to_created);

        $response->assertStatus(200)->assertJsonStructure(['name', 'email', 'bank', 'agency', 'number_account', 'balance']);

        $this->assertDatabaseHas('users', [
            'email' => $user_to_created['email'],
            'name' => $user_to_created['name']
        ]);

        $this->user = User::first();

        $this->assertDatabaseHas('accounts', ['bank' => $user_to_created['bank'], 'agency' => $user_to_created['agency'],
            'number_account' => $user_to_created['number_account'], 'balance' => 0.0]);
        return $response;
    }

    public function register_with_invalid_data()
    {
        $response = $this->postJson('api/auth/register', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'confirm_password', 'bank', 'agency', 'number_account']);
    }

    public function login_user_with_valid_data(array $user_to_login)
    {
        $response = $this->postJson('api/auth/login', [
            'name' => $user_to_login['name'],
            'email' => $user_to_login['email'],
            'password' => $user_to_login['password'],
            'confirm_password' => $user_to_login['confirm_password'],
        ]);
        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

        return $response['access_token'];
    }

    public function get_user_me($accesToken)
    {
        $response = $this->get('api/auth/me', [], ['Authorization' => $accesToken]);
        $response->assertStatus(200)->assertJsonStructure(['name', 'email', 'created_at', 'updated_at']);
        return $response;
    }

    public function change_password()
    {
        $new_password = 'senhaSegura1234';

        $response = $this->post('api/auth/change-password', [
            'password' => $this->pessoal_information_to_test['password'],
            'confirm_password' => $this->pessoal_information_to_test['password'],
            'new_password' => $new_password,
            'confirm_new_password' => $new_password,
        ], ['Authorization' => $this->accesToken]);

        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
        $this->accesToken = $response['access_token'];

        $this->assertDatabaseHas('users', [
            'email' => $this->pessoal_information_to_test['email'],
            'name' => $this->pessoal_information_to_test['name']
        ]);

        $userFromDb = User::where('email', $this->pessoal_information_to_test['email'])->first();
        $this->assertTrue(Hash::check($new_password, $userFromDb->password));

        return $response;
    }

    public function change_password_with_token(string $token)
    {
        $new_password = 'senhaSegura1234';

        $response = $this->post("api/auth/change-password/$token" . '?' . http_build_query(['email' => urlencode($this->pessoal_information_to_test['email'])]), [
            'password' => $this->pessoal_information_to_test['password'],
            'confirm_password' => $this->pessoal_information_to_test['password'],
            'new_password' => $new_password,
            'confirm_new_password' => $new_password,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => $this->pessoal_information_to_test['email'],
            'name' => $this->pessoal_information_to_test['name']
        ]);

        $userFromDb = User::where('email', $this->pessoal_information_to_test['email'])->first();
        $this->assertTrue(Hash::check($new_password, $userFromDb->password));

        return $response;
    }

    public function reset_password()
    {
        $this->assertDatabaseHas('users', [
            'email' => $this->pessoal_information_to_test['email'],
            'name' => $this->pessoal_information_to_test['name']
        ]);

        $response = $this->post('api/auth/reset-password', ['email' => $this->pessoal_information_to_test['email']]);

        $response->assertStatus(200);

        $userFromDb = User::where('email', $this->pessoal_information_to_test['email'])->first();

        $notification = $userFromDb->notifications()->get()->last();
        $token = $notification->data['token'];

        $this->change_password_with_token($token);
        return $response;
    }
}
