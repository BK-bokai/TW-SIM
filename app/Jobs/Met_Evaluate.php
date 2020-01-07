<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Met_Evaluate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $start;
    protected $end;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($start,$end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $command="py -3 D:\bokai\python\python-code\Evaluate_tool\Meteorology.py" .' '. $this->start.' '. $this->end ;
        exec($command);
    }
}
