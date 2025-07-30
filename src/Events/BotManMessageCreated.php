<?php

namespace Collegeman\BotManWebWidget\Events;

use App\Http\Resources\AssetState;
use App\Models\Asset;
use Collegeman\BotManWebWidget\BotManMessage;
use Collegeman\BotManWebWidget\BotManWebWidget;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BotManMessageCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public BotManMessage $message;

    /**
     * Create a new event instance.
     */
    public function __construct(BotManMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastAs(): string
    {
        return BotManWebWidget::config('echoEventName');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(BotManWebWidget::echoChannel()),
        ];
    }

}
