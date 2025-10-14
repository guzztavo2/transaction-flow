<?php 
namespace App\Domain\Entities;

use App\Domain\DTOs\UserData;

final class User{
    public function __construct(private UserData $data){}

    public function toArray():array{
        
        return [
            'id' => $this->data->id,
            'name' => $this->data->name,
            'email' => $this->data->email,
            'password' => $this->data->password,
            'created_at' => $this->data->created_at,
            'updated_at' => $this->data->updated_at,
        ];
    }

    public static function fromArray(array $data){
        
        return new self((new UserData(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['password'], 
            $data['created_at'], 
            $data['updated_at'])));
    }

}
