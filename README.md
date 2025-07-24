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

$client = new PogodocSdk("YOUR_POGODOC_API_TOKEN");

$response = $client->generateDocument([
    'templateId' => $templateId,
    'data' => ["name" => "John Doe"];,
    'renderConfig' => [
        'type' => 'ejs',
        'target' => 'pdf',
        'formatOpts' => [
                'fromPage' => 1,
            ],
    ],
]);

printf("Generated document url:\n %s\n", $response->output->data->url);

```

### License

MIT License
