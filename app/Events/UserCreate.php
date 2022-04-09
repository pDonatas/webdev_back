<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected array $request;
    protected ?User $user;

    public function __construct(array $request, ?User $user = null)
    {
        $this->request = $request;
        $this->user = $user;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user-creation');
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
