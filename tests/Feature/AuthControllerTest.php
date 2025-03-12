<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_user_with_valid_datas()
    {
        $data = [
            'name' => 'Fulano de Tal',
            'email' => 'fulano@exemplo.com',
            'password' => 'senhaSegura123',
            'confirm_password' => 'senhaSegura123',
            'bank' => 'Banco Teste',
            'agency' => '001',
            'number_account' => '123456'
        ];

        $response = $this->postJson('api/auth/register', $data);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'name',
                'email',
                'bank',
                'agency',
                'number_account',
                'balance'
            ]);

        // Verifica se o usuário foi criado
        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
            'name' => $data['name']
        ]);

        // Verifica se a conta foi criada
        $user = User::first();
        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'bank' => $data['bank'],
            'agency' => $data['agency'],
            'number_account' => $data['number_account'],
            'balance' => 0.0
        ]);
    }

    /** @test */
    public function validation_width_incomplete_data()
    {
        $resposta = $this->postJson('api/auth/register', []);

        $resposta->assertStatus(422)
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

    /** @test */
    public function teste_with_not_same_password()
    {
        $data = [
            'name' => 'Fulano de Tal',
            'email' => 'fulano@exemplo.com',
            'password' => 'senhaSegura1234',
            'confirm_password' => 'senhaSegura123',
            'bank' => 'Banco Teste',
            'agency' => '001',
            'number_account' => '123456'
        ];

        $response = $this->postJson('api/auth/register', $data);

        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }
}