<?php

namespace App\Domain\Actions\User;

use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Exceptions\UnauthorizedException;
use Carbon\Carbon;
use App\Domain\Actions\User\UpdatePasswordAction;

class UpdatePasswordWithTokenAction
{
    public function __construct(private UpdatePasswordAction $updatePassword) {}

    public function execute(string $email, string $token, string $old_password, string $new_password, int $recovery_max_hours = 2): ?User
    {
        $user = User::where('email', $email)->first();
        if (!$user)
            throw new UserNotFound('User not found');

        $hasToken = false;
        $user->notifications->where('type', \App\Notifications\ResetPassword::class)
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

        return $this->updatePassword->execute($user->getId(), $old_password, $new_password);
    }
}
