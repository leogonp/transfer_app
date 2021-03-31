<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;


class NotificationService
{
	 private $url;

    public function __construct(string $url){
    	$this->url = $url;
    }

    public function send(): void
    {
        Http::get($this->url);
	}

}
