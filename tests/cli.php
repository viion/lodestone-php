<?php

// composer auto loader
require __DIR__.'/../vendor/autoload.php';

// parse characters
// view Lodestone/Modules/Routes for more urls.
// todo: write a simple route wrapper.
$url = sprintf(\Lodestone\Modules\Routes::LODESTONE_CHARACTERS_URL, 730968);
$parser = new Lodestone\Parser\Character();
$data = $parser->url($url)->parse();

// Array of character data
print_r($data);