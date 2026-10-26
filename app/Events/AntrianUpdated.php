<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AntrianUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $idJadwal;
    public $noAntrian;

    public function __construct($idJadwal, $noAntrian)
    {
        $this->idJadwal = $idJadwal;
        $this->noAntrian = $noAntrian;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('antrian-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrian.updated';
    }
}