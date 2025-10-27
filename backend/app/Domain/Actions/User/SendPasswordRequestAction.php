<?php

namespace App\Domain\Actions\User;

use App\Domain\Repositories\User\UserRepositoryInterface;
use App\Jobs\MailResetPasswordJob;

class SendPasswordRequestAction
{
    public function __construct(private UserRepositoryInterface $repo) {}

    public function __invoke(string $email, int $RECOVERY_PASSWORD_TOKEN_HOUR)
    {
        $user = $this->repo->findByEmail($email);
        MailResetPasswordJob::dispatch($user->getId(), $RECOVERY_PASSWORD_TOKEN_HOUR);
        return $this->repo->save($user);
    }
}
