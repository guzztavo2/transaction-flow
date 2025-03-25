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

    private User $user;
    private string $accesToken;

    /**
     * @test
     */
    public function validation_with_incomplete_data()
    {
        $resposta = $this->postJson('api/auth/register', []);

        $resposta
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'email',
                'password',
                'confirm_password',
                'bank',
                'agency',
                'number_account'
            ]);
    }

    public function register_user_with_valid_datas()
    {
        $response = $this->postJson('api/auth/register', $this->pessoal_information_to_test);

        $response->assertStatus(200)->assertJsonStructure(['name', 'email', 'bank', 'agency', 'number_account', 'balance']);

        $this->assertDatabaseHas('users', [
            'email' => $this->pessoal_information_to_test['email'],
            'name' => $this->pessoal_information_to_test['name']
        ]);

        // Verifica se a conta foi criada
        $this->user = User::first();

        $this->assertDatabaseHas('accounts', ['user_id' => $this->user->id,
            'bank' => $this->pessoal_information_to_test['bank'], 'agency' => $this->pessoal_information_to_test['agency'],
            'number_account' => $this->pessoal_information_to_test['number_account'], 'balance' => 0.0]);
        return $response;
    }

    public function login_user_with_valid_data()
    {
        $response = $this->postJson('api/auth/login', [
            'name' => $this->pessoal_information_to_test['name'],
            'email' => $this->pessoal_information_to_test['email'],
            'password' => $this->pessoal_information_to_test['password'],
            'confirm_password' => $this->pessoal_information_to_test['confirm_password'],
        ]);
        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

        $this->accesToken = $response['access_token'];
        return $response;
    }

    public function get_user_me()
    {
        $response = $this->get('api/auth/me', [], ['Authorization' => $this->accesToken]);
        $response->assertStatus(200)->assertJsonStructure(['id', 'name', 'email', 'created_at', 'updated_at']);
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

    /**
     * @test
     */
    public function test_auth_controller()
    {
        $registerResponse = $this->register_user_with_valid_datas();
        // $loginResponse = $this->login_user_with_valid_data();
        // $getMeResponse = $this->get_user_me();
        // $changePasswordResponse = $this->change_password();
        $resetPasswordResponse = $this->reset_password();
    }
}
