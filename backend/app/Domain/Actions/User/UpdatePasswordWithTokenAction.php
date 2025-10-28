<?php

namespace App\Domain\Actions\User;

use App\Domain\Entities\User;
use App\Exceptions\UnauthorizedException;
use Carbon\Carbon;
use App\Domain\Actions\User\UpdatePasswordAction;
use Illuminate\Notifications\DatabaseNotification;

class UpdatePasswordWithTokenAction
{
    public function __construct(private UpdatePasswordAction $updatePassword) {}

    public function execute(string $token, string $old_password, string $new_password, int $recovery_max_hours = 2): ?User
    {
        $hasToken = false;
        DatabaseNotification::where('type', \App\Notifications\ResetPassword::class)->get()
            ->map(function ($notification) use ($token, &$hasToken) {
                if ($notification->data['token'] === $token) {
                    $notification->delete();
                    $hasToken = $notification;
                }
            });

        if (!$hasToken)
            throw new UnauthorizedException('Invalid token');

        if (Carbon::create($hasToken->created_at)->diffInHours(Carbon::now()) > $recovery_max_hours)
            throw new UnauthorizedException('The token has expired.');

        return $this->updatePassword->execute($hasToken->data['user_id'], $new_password, $old_password);
    }
}
