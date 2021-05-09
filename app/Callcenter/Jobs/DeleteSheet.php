<?php

namespace LocalheroPortal\Callcenter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class DeleteSheet implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var string
     */
    protected $sheetName;

    public function __construct(string $name)
    {
        $this->sheetName = $name;
    }

    public function handle()
    {
        Storage::disk('public')->delete('sheets/' . $this->sheetName);
    }
}