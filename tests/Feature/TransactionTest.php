<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    private array $pessoal_information_to_test = [
        'name' => 'Fulano de Tal',
        'email' => 'fulano@exemplo.com',
        'password' => 'senhaSegura123',
        'confirm_password' => 'senhaSegura123'
    ];

    private string $access_token;

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

    #[Test]
    public function test_initialization()
    {
        //test initialization
        $this->login();
        $this->deposit();
    }

    public function deposit(){
        $args = [
            'type' => Transaction::TYPE_DEPOSIT,
            'amount' => "150.50",
            // 'scheduled_at' => now()->addDays(1)->format('m-d-Y')
        ];

        $response = $this->post('api/transactions/', $args, ['Authorization' => $this->access_token]);
        $data = json_decode($response->getContent(), true);
    }
}