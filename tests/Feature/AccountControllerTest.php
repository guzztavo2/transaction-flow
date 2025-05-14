<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    // use RefreshDatabase;
    private array $pessoal_information_to_test = [
        'name' => 'Fulano de Tal',
        'email' => 'fulano@exemplo.com',
        'password' => 'senhaSegura123',
        'confirm_password' => 'senhaSegura123'
    ];

    private array $accounts = [];

    private array $account_to_create = [
        'bank' => 'Banco Teste', 'agency' => '001', 'number_account' => '123456'
    ];

    private string $access_token;

    #[Test]
    public function test_initial_account()
    {
        $this->login();
        $this->getAccounts();
        $this->getDetailAccount();
    }

    private function login()
    {
        $response = $this->postJson('api/auth/login', [
            'name' => $this->pessoal_information_to_test['name'],
            'email' => $this->pessoal_information_to_test['email'],
            'password' => $this->pessoal_information_to_test['password'],
            'confirm_password' => $this->pessoal_information_to_test['confirm_password'],
        ]);
        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

        $this->access_token = $response['access_token'];
    }

    private function getAccounts()
    {
        $response = $this->get('api/accounts/', [], ['Authorization' => $this->access_token]);
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('bank', $data[0]);
        $this->assertArrayHasKey('number_account', $data[0]);
        $this->accounts = $data;
    }

    private function getDetailAccount()
    {
        $response = $this->get('api/accounts/' . $this->accounts[0]['id'], [], ['Authorization' => $this->access_token]);
        $data = json_decode($response->getContent(), true);
        $response->assertStatus(200);
    }
}
