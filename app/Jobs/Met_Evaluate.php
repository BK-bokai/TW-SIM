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

    protected $now;
    protected $start;
    protected $end;
    protected $rootdir;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($now,$start,$end,$rootdir)
    {
        $this->now = $now;
        $this->start = $start;
        $this->end   = $end;
        $this->rootdir = $rootdir;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = date("Y-m-d-H-i-s");
        
        $command=env('pycommand').' '.$this->start.' '.$this->end.' '.$this->now.' '.$this->rootdir;
        exec($command);
    }
}
