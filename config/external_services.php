<?php

return [

    'blizzard' => [
        'client_id' => env('BLIZZARD_CLIENT_ID', 'default_client_id'),
        'client_secret' => env('BLIZZARD_CLIENT_SECRET', 'default_client_secret'),
        'api_url' => env('BLIZZARD_API_URL', 'https://oauth.battle.net/token'),
        'data_api_url' => env('BLIZZARD_DATA_API_URL', 'https://us.api.blizzard.com'),
    ],

];
