<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Environment
    |--------------------------------------------------------------------------
    |
    | This option controls which environment is selected by default, note that
    | it falls back to this option when either the selected options cannot be
    | cannot be found or is not present. When even the default environment
    | cannot be found an exception will be thrown unless throwExceptionOnError
    | is set to false.
    |
    */
    "defaultEnvironment" => "development",

    /*
    |--------------------------------------------------------------------------
    | How should errors be handled
    |--------------------------------------------------------------------------
    |
    | When this variable is set to true all errors will throw an exception.
    | Otherwise it will return the variable false. Default true
    |
    */
    "throwExceptionOnError" => true,

    /*
    |--------------------------------------------------------------------------
    | Per platform and Environment settings
    |--------------------------------------------------------------------------
    |
    | Set the settings per platform and Environment, the environment stored in
    | the .env file will be used except when that value cannot be found. When
    | the environment value cannot be found in the list the value set in
    | defaultEnvironment will be used.
    |
    | Per platform:
    |   Apple:
    |      certificate: The path to the certificate relative to project root
    |      passphrase: The passphrase for the certificate (optional)
    |      environment: Either "production" or "development" determines which
    |                   environment will be used when contacting Apple
    |      topic: The application identifier
    |   Google:
    |      apikey: The GCM API key
    |
    */
    "apple" => [
        "production" => [
            "certificate" => "/path/to/certificate.pem",
            "passphrase"  => "SomePassPhrase",
            "environment" => "production",
            "topic"       => "",
        ],

        "development" => [
            "certificate" => "/path/to/certificate.pem",
            "passphrase"  => "SomePassPhrase",
            "environment" => "development",
            "topic"       => "",
        ]
    ],

    "google" => [
        "production" => [
            "apikey" => "Google G2CM API key",
        ],

        "development" => [
            "apikey" => "Google G2CM API key",
        ]
    ]
];
