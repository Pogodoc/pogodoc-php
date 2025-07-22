## Pogodoc PHP SDK

The Pogodoc PHP SDK enables developers to seamlessly generate documents and manage templates using Pogodoc’s API.

### Installation

To install the PHP SDK, just execute the following command

```bash
$ composer require pogodoc/pogodoc-php
```

### Setup

To use the SDK you will need an API key which can be obtained from the [Pogodoc Dashboard](https://app.pogodoc.com)

### Example

```php

<?php

require __DIR__ . '/vendor/autoload.php';

use PogodocSdk\PogodocSdk;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new PogodocSdk();

$sampleData = [
    "name" => "John Doe",
    "email" => "john.doe@example.com",
    "phone" => "1234567890",
    "address" => "123 Main St, Anytown, USA",
    "city" => "Anytown",
];

$response = $client->generateDocument([
    'templateId' => $templateId,
    'data' => $sampleData,
    'renderConfig' => [
        'type' => 'ejs',
        'target' => 'pdf',
    ],
    'shouldWaitForRenderCompletion' => true,
]);

printf("Generated document url:\n %s\n", $response->output->data->url);

```

### License

MIT License
