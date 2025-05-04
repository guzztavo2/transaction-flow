<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;

    private static AuthControllerTest $authControllerTest;

    public static function setUpBeforeClass(): void
    {
        self::$authControllerTest = new AuthControllerTest();
    }

    private function register_login()
    {
        self::setUpBeforeClass();
        $registerResponse = $this->authControllerTest->register_user_with_valid_datas(
            $this->authControllerTest->pessoal_information_to_test
        );
        $this->accesToken = $this->authControllerTest->login_user_with_valid_data(
            $this->authControllerTest->pessoal_information_to_test
        );
    }

    #[Test]
    public function test_initial_account()
    {
        $this->register_login();
    }
}
