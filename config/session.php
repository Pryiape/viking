<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Session Driver
    |--------------------------------------------------------------------------
    |
    | Here you may specify the session driver that should be used by your
    | application. The default option is "file", but you may use any of
    | the other available drivers: "cookie", "database", "apc", "memcached",
    | "redis", "dynamodb", "array" or "null".
    |
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that you wish the session
    | to be allowed to remain idle before it expires. If you want to
    | expire the session on browser close, set this value to null.
    |
    */

    'lifetime' => 120,

    /*
    |--------------------------------------------------------------------------
    | Expire On Close
    |--------------------------------------------------------------------------
    |
    | Whether the session should expire on browser close.
    |
    */

    'expire_on_close' => false,

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    |
    | This option determines whether the session should be encrypted.
    |
    */

    'encrypt' => false,

    /*
    |--------------------------------------------------------------------------
    | Session Files Location
    |--------------------------------------------------------------------------
    |
    | When using the "file" session driver, you may specify the location
    | of the session files. The default is the storage/framework/sessions
    | directory.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the
    | database connection that should be used to manage your sessions.
    |
    */

    'connection' => null,

    /*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the
    | table that should be used to manage your sessions.
    |
    */

    'table' => 'sessions',

    /*
    |--------------------------------------------------------------------------
    | Session Store
    |--------------------------------------------------------------------------
    |
    | You may specify a custom session store name if you wish to use
    | multiple session stores in the same application.
    |
    */

    'store' => null,

    /*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | Some applications may need to sweep their sessions from time to time.
    | Here you may specify the probability that the session will be swept
    | on each request.
    |
    */

    'lottery' => [2, 100],


    /*
    |--------------------------------------------------------------------------
    | Cookie Name
    |--------------------------------------------------------------------------
    |
    | The name of the cookie used to identify the session instance.
    |
    */

    'cookie' => env('SESSION_COOKIE', 'laravel_session'),

    /*
    |--------------------------------------------------------------------------
    | Cookie Path
    |--------------------------------------------------------------------------
    | 
    | The path for which the cookie is valid.
    |
    */

    'path' => '/',

    /*
    |--------------------------------------------------------------------------
    | Cookie Domain
    |--------------------------------------------------------------------------
    |
    | The domain for which the cookie is valid.
    |
    */

    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Cookie Secure
    |--------------------------------------------------------------------------
    |
    | Indicates if the cookie should only be transmitted over HTTPS.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIE', false),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookie
    |--------------------------------------------------------------------------
    |
    | This option determines the "SameSite" setting of the cookie.
    |
    */

    'same_site' => 'lax',

];
