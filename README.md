# Laravel Push
This package provides a Push service for both iOS and Android. It implements the iOS APS 2.0 (HTTP2) API, which requires a version of cURL with HTTP2 enabled. Contrary to other packages, this package can give feedback on failed push tokens.

# Installation
To install the package, run the composer command:

```composer require pharmit/push```

Open `app.php` from your config directory and add to the providers array: `'PharmIT\Push\PushServiceProvider',`

And add to the alias array: `'Push' => 'PharmIT\Push\PushServiceFacade',`

Then publish the configuration by executing: ```php artisan vendor:publish```

## Server settings
Since the new Apple APS API uses HTTP2 and support for HTTP2 is not good, make sure you have a cURL compiled with HTTP2 support. This has been fully implemented in cURL version 7.43.0 and up. However cURL needs to be specifically compiled with this feature. When the used cURL does not have support for HTTP2, it will throw an (odd) SSL error when trying to send a push message.

# Configuration
The package contains a configuration file called, "push.php". Listed below are the configuration settings.

#### throwExceptionOnError
When this variable is set to true all errors will throw an exception. Otherwise it will return the variable false. Defaults to true.

#### defaultEnvironment
This option controls which environment is selected by default, note that it falls back to this option when either the selected options cannot be cannot be found or is not present. When even the default environment cannot be found an exception will be thrown unless ```throwExceptionOnError``` is set to false.

#### apple
This array contains all configuration options for APS.

###### certificate
The path to the certificate relative to project root

###### environment
Either "production" or "development" determines which environment will be used when contacting Apple

###### passphrase
The passphrase for the certificate (optional)

###### topic
The application identifier, e.g. "nl.PharmIT.MedApp"

#### google
This array contains all configuration options for Google's GCM, which is currently just a single option.

###### apikey
The GCM API key

# Usage
The following is a minimal code example for the Google API:
```
// Get an instance
$google = Push::getPushService('google')
            ->setMessageText('Hi, how are you?')
            ->addRecipient('<PUSH_ID>')
            ->addRecipients(['<PUSH_ID>','<PUSH_ID>']);

// Get all push ID's to which the push message has been sent
$success = $google->send();

// Get all push ID's to which the push message could <u>not</u> be sent.
$failed = $google->getFailedRecipients();
```

For APS the exact same pattern can be used, but the parameter given to the `getPushService` call, has to be 'apple' instead of 'google'.
