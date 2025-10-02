<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Transaction;

class TransactionTest extends TestCase
{
    private array $pessoal_information_to_test_1 = [
        'name' => 'Fulano de Tal',
        'email' => 'fulano@exemplo.com',
        'password' => 'senhaSegura123',
        'confirm_password' => 'senhaSegura123'
    ];
    private array $pessoal_information_to_test_2 = [
        'name' => 'Fulano de Tal',
        'email' => 'fulano1@exemplo.com',
        'password' => 'senhaSegura123',
        'confirm_password' => 'senhaSegura123'
    ];

    private string $access_token;

    #[Test]
    public function test_initialization()
    {
        //test initialization
        $this->login($this->pessoal_information_to_test_1);
        // $this->deposit();
        // $this->loot();
        $this->transfer();
    }

    private function login(array $userToLogin)
    {
        $response = $this->postJson('api/auth/login', [
            'name' => $userToLogin['name'],
            'email' => $userToLogin['email'],
            'password' => $userToLogin['password'],
            'confirm_password' => $userToLogin['confirm_password'],
        ]);
        $response->assertStatus(200)->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

        $this->access_token = $response['access_token'];
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

    public function loot(){
        $args = [
            'type' => Transaction::TYPE_LOOT,
            'amount' => "250.50",
            // 'scheduled_at' => now()->addDays(1)->format('m-d-Y')
        ];

        $response = $this->post('api/transactions/', $args, ['Authorization' => $this->access_token]);
        $data = json_decode($response->getContent(), true);
    }
    
    public function transfer(){
        $args = [
            'type' => Transaction::TYPE_TRANSFER,
            'amount' => "150.50",
            'accountDestination' => '450f2338-6cc2-43a7-a72e-56265cee7696',
            // 'scheduled_at' => now()->addDays(1)->format('m-d-Y')
        ];

        $response = $this->post('api/transactions/', $args, ['Authorization' => $this->access_token]);
        $data = json_decode($response->getContent(), true);
    }

}