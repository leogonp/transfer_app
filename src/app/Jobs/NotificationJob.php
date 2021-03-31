<?php

namespace App\Jobs;
use App\Services\NotificationService;

class NotificationJob extends Job
{
    
    private $url;

    public function __construct(string $url){
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notificationService = new NotificationService($this->url);
        $notificationService->send();
    }
}
