<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use \Tests\Fake\FakeUserRepository;
use \Tests\Fake\FakeAccountRepository;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $pessoal_information_to_test = [
        'name' => 'Fulano de Tal',
        'email' => 'fulano@exemplo.com',
        'password' => 'senhaSegura123',
        'confirm_password' => 'senhaSegura123',
        'bank' => 'Banco Teste',
        'agency' => '001',
        'number_account' => '123456'
    ];

    private array $pessoal_information_to_test_2 = [
        'name' => 'Fulano de Tal',
        'email' => 'fulano1@exemplo.com',
        'password' => 'senhaSegura123',
        'confirm_password' => 'senhaSegura123',
        'bank' => 'Banco Teste',
        'agency' => '001',
        'number_account' => '123454'
    ];

    private string $accesToken;

    private ?FakeAccountRepository $fakeAccountRepository = null;
    private ?FakeUserRepository $fakeUserRepository = null;

    private function bindFakeRepositories()
    {
        $this->fakeUserRepository = new FakeUserRepository();
        $this->app->instance(\App\Domain\Repositories\User\UserRepositoryInterface::class, $this->fakeAccountRepository);

        $this->fakeAccountRepository = new FakeAccountRepository();
        $this->app->instance(\App\Domain\Repositories\Account\AccountRepositoryInterface::class, $this->fakeAccountRepository);
    }

    #[Test]
    public function register_user_with_valid_datas(?array $user_to_created = null)
    {
        if (!$user_to_created)
            $user_to_created = $this->pessoal_information_to_test;

        $response = $this->postJson('api/auth/register', $user_to_created);

        $response->assertStatus(200)->assertJsonStructure(['name', 'email', 'bank', 'agency', 'number_account', 'balance']);

        if (is_null($this->fakeAccountRepository)) {
            $this->assertDatabaseHas('users', [
                'email' => $user_to_created['email'],
                'name' => $user_to_created['name']
            ]);

            $this->user = User::first();

            $this->assertDatabaseHas('accounts', [
                'bank' => $user_to_created['bank'],
                'agency' => $user_to_created['agency'],
                'number_account' => $user_to_created['number_account'],
                'balance' => 0.0
            ]);
        }

        return $response;
    }

    public function register_with_invalid_data()
    {
        $response = $this->postJson('api/auth/register', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'confirm_password', 'bank', 'agency', 'number_account']);
    }

    #[Test]
    public function login_user_with_valid_data(?array $user_to_login = null)
    {
        if (!$user_to_login)
            $user_to_login = $this->pessoal_information_to_test;

        $response = $this->postJson('api/auth/login', [
            'name' => $user_to_login['name'],
            'email' => $user_to_login['email'],
            'password' => $user_to_login['password'],
            'confirm_password' => $user_to_login['confirm_password'],
            'remember' => true
        ]);
        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type']);

        return $response['access_token'];
    }

    #[Test]
    public function get_user_me(?string $accessToken = null)
    {
        if (!$accessToken)
            $accessToken = $this->accesToken;

        $response = $this->withHeaders([
            'Authorization' => $accessToken,
        ])->get('api/auth/me', [], ['Authorization' => $accessToken]);
        $response->assertStatus(200)->assertJsonStructure(['name', 'email', 'created_at', 'updated_at']);
        return $response;
    }

    #[Test]
    public function change_password(?array $user_to_change_password = null)
    {
        $new_password = 'senhaSegura1234';

        if (!$user_to_change_password) {
            $user_to_change_password = $this->pessoal_information_to_test;
            $this->pessoal_information_to_test['password'] = $new_password;
            $this->pessoal_information_to_test['confirm_password'] = $new_password;
        }
        
        $response = $this->post('api/auth/change-password', [
            'password' => $user_to_change_password['password'],
            'confirm_password' => $user_to_change_password['password'],
            'new_password' => $new_password,
            'confirm_new_password' => $new_password,
        ], ['Authorization' => $this->accesToken]);

        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
        $this->accesToken = $response['access_token'];

        $this->assertDatabaseHas('users', [
            'email' => $user_to_change_password['email'],
            'name' => $user_to_change_password['name']
        ]);

        $userFromDb = User::where('email', $user_to_change_password['email'])->first();
        $this->assertTrue(Hash::check($new_password, $userFromDb->password));

        return $response;
    }

    #[Test]
    public function reset_password(?array $user_to_reset = null)
    {
        if (!$user_to_reset)
            $user_to_reset = $this->pessoal_information_to_test;

        $this->assertDatabaseHas('users', [
            'email' => $user_to_reset['email'],
            'name' => $user_to_reset['name']
        ]);

        $response = $this->post('api/auth/reset-password', ['email' => $user_to_reset['email']]);

        $response->assertStatus(200);

        $userFromDb = User::where('email', $user_to_reset['email'])->first();
        $notification = false;
        while (!$notification) {
            $notification = $userFromDb->notifications()->where('type', \App\Notifications\ResetPassword::class)->get()->last();
            sleep(2);
        }
        $token = $notification->data['token'];

        $this->change_password_with_token($token);
        return $response;
    }

    private function change_password_with_token(string $token, ?array $user_to_change_password = null)
    {
        if (!$user_to_change_password)
            $user_to_change_password = $this->pessoal_information_to_test;

        $new_password = 'senhaSegura1234';

        if ($user_to_change_password['password'] === $new_password)
            $new_password .= '5';


        $response = $this->post("api/auth/change-password/$token", [
            'password' => $user_to_change_password['password'],
            'confirm_password' => $user_to_change_password['password'],
            'new_password' => $new_password,
            'confirm_new_password' => $new_password,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => $user_to_change_password['email'],
            'name' => $user_to_change_password['name']
        ]);

        $userFromDb = User::where('email', $user_to_change_password['email'])->first();
        $this->assertTrue(Hash::check($new_password, $userFromDb->password));

        return $response;
    }
}
