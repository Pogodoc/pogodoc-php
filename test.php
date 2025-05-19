

<?php

require_once 'vendor/autoload.php';
require_once 'index.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sampleData = readJsonFile(__DIR__ . '/../../data/json_data/react.json');
$templatePath = __DIR__ . '/../../data/templates/React-Demo-App.zip';


$client = new PogodocApiClient(
    $_ENV['POGODOC_API_TOKEN'] ?? '',
    [
        'baseUrl' =>  $_ENV['LAMBDA_BASE_URL'] ?? '',
    ],
   
);

function main($client, $templatePath, $sampleData)
{
    $templateId = $client->saveTemplate([
        'path' => $templatePath,
        'title' => "Invoice",
        'description' => 'Invoice template',
        'type' => "html",
        'categories' => ['invoice'],
        'sampleData' => $sampleData,
    ]);

    if (!$templateId) {
        throw new Exception("Template ID is required");
    }

    echo $templateId . "\n";

    $documentOutput = $client->generateDocument([
        'templateId' => $templateId,
        'data' => $sampleData,
        'renderConfig' => [
            'type' => 'html',
            'target' => 'pdf',
            'formatOpts' => [
                'fromPage' => 1,
                'toPage' => 1,
            ],
        ],
        'shouldWaitForRenderCompletion' => true,
    ]);

   print_r($documentOutput);

    $updateTemplateId = $client->updateTemplate([
        'path' => $templatePath,
        'templateId' => '9c4ace0a-8d6f-4371-b455-38508f1f8ac2',
        'title' => 'Invoice',
        'description' => 'Invoice template',
        'type' => 'html',
        'categories' => ['invoice'],
        'sampleData' => $sampleData,
    ]);

   echo $updateTemplateId . "\n";
}

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

main($client, $templatePath, $sampleData);
