<?php
require(__DIR__."/vendor/autoload.php");
$openapi = \OpenApi\scan(__DIR__."/app/");
//header('Content-Type: application/x-yaml');
file_put_contents(__DIR__."/public/swagger/swagger.json",$openapi->toYaml());
