<?php

namespace App\Jobs;

use App\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;

class sendEmail implements ShouldQueue
{
    use Queueable;

    private int $userId, $RECOVERY_PASSWORD_TOKEN_HOUR;
    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $RECOVERY_PASSWORD_TOKEN_HOUR = null)
    {
        $this->userId = $userId;
        $this->RECOVERY_PASSWORD_TOKEN_HOUR = $RECOVERY_PASSWORD_TOKEN_HOUR;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user) return;

        $user->notify(new ResetPassword($this->RECOVERY_PASSWORD_TOKEN_HOUR));
    }
}
