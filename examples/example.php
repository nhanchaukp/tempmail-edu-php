<?php
require __DIR__ . '/../vendor/autoload.php';

use Nhanchaukp\TempmailEdu\Config;
use Nhanchaukp\TempmailEdu\Http\GuzzleClient;
use Nhanchaukp\TempmailEdu\TempmailEdu;

$config = new Config(baseUri: 'https://tempmail.id.vn/api', timeout: 10);
$http = new GuzzleClient($config);
$client = new TempmailEdu($http);

try {
    // Login example
    $login = $client->login('you@example.com', 'yourpassword');
    print_r($login);

    // Get domains
    $domains = $client->getDomains();
    print_r($domains);

    // Create email example
    $create = $client->createEmail([
        'user' => 'guest123',
        'domain' => 'tempmail.id.vn',
        'generate_guest_link' => true,
        'guest_link_expiration_days' => 7
    ]);
    print_r($create);

} catch (\Nhanchaukp\TempmailEdu\Exceptions\ApiException $e) {
    echo "API error: " . $e->getMessage() . PHP_EOL;
}