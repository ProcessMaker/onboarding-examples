<?php
/**
 * This file bootstraps all other scripts that accesses our ProcessMaker.IO API
 */
require('vendor/autoload.php');

// Bring in our namespaced classes
use ProcessMaker\PMIO\Client;
use ProcessMaker\PMIO\ApiClient;
use ProcessMaker\PMIO\Configuration;

// Read our .env file for our configuration values
$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required(['PMIO_ENDPOINT', 'PMIO_ACCESS_TOKEN']);

// Create our API configuration object and pass in it's configuration
$config = new Configuration();
$config->setHost(getenv('PMIO_ENDPOINT'));
$config->setAccessToken(getenv('PMIO_ACCESS_TOKEN'));
$config->setDebug(getenv('PMIO_DEBUG'));
$config->setDebugFile(__DIR__ . '/' . getenv('PMIO_DEBUG_FILE'));

// Initialize our API client which will be referenced by client scripts
$pmio = new Client(new ApiClient($config));

