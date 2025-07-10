## Pogodoc PHP SDK

The Pogodoc PHP SDK enables developers to seamlessly generate documents and manage templates using Pogodoc’s API.

### Installation

To install the PHP SDK, just execute the following command

```bash
$ composer require pogodoc/pogodoc-php
```

### Setup

To use the SDK you will need an API key which can be obtained from the [Pogodoc Dashboard](https://pogodoc.com)

### Example

```php

<?php

require __DIR__ . '/vendor/autoload.php';

use PogodocSdk\PogodocSdk;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function readJsonFile($filePath)
{
    try {
        $jsonString = file_get_contents($filePath);
        return json_decode($jsonString, true);
    } catch (Exception $e) {
        echo "Error reading the JSON file: " . $e->getMessage();
        return null;
    }
}

$sampleData = readJsonFile(__DIR__ . '/../../data/json_data/react.json');
$templatePath = __DIR__ . '/../../data/templates/React-Demo-App.zip';


$client = new PogodocSdk();

$templateId = $client->saveTemplate([
    'path' => $templatePath,
    'title' => "Invoice",
    'description' => 'Invoice description',
    'type' => "react",
    'categories' => ['invoice'],
    'sampleData' => $sampleData,
]);

printf("Created template id: %s \n", $templateId);

$client->updateTemplate([
    'path' => $templatePath,
    'templateId' => $templateId,
    'title' => 'Invoice Updated',
    'description' => 'Description updated',
    'type' => 'react',
    'categories' => ['invoice'],
    'sampleData' => $sampleData,
]);

print("Template updated successfully \n");

$response = $client->generateDocument([
    'templateId' => $templateId,
    'data' => $sampleData,
    'renderConfig' => [
        'type' => 'react',
        'target' => 'pdf',
    ],
    'shouldWaitForRenderCompletion' => true,
]);

printf("Generated document url:\n %s\n", $response->output->data->url);

```

### License

MIT License
