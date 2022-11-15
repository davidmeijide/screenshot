#Instalation - <a src="https://github.com/googleapis/google-api-php-client">Google APIs Client Library for PHP</a>
In order to upload files to Google Drive from our server, we need to install this library.
I installed it using Composer:
composer require google/apiclient:^2.12.1
Make sure to include the autoloader in your php file:
require_once '/path/to/your-project/vendor/autoload.php';
In compose.json, cleanup and only use Drive
`{
    "require": {
        "google/apiclient": "^2.12.1"
    },
    "scripts": {
        "pre-autoload-dump": "Google\\Task\\Composer::cleanup"
    },
    "extra": {
        "google/apiclient-services": [
            "Drive",
        ]
    }
}`