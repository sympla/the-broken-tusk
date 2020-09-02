# The Broken Tusk

PHP Wrapper to dispatch campaigns in Track.co

## Instaling 

````
composer require sympla/the-broken-tusk
````

## Before Use
First you need to login into your Track.co account and enable v2 API and generate a Access token and create a campaign to send the e-mail

### Dispatch campaign
In your project add the class initialize with the token and call method sendDispatchRequest with campaign ID, Username and Email.

```php

use Tracksale\Request\DispatchCampaign;

$sendCampaign = new DispatchCampaign('TRACKSALE_TOKEN');

$response = $sendCampaign->sendDispatchRequest($campaign_id, $user_name, $email);
        


