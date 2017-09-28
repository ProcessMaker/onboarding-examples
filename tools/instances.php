<?php
/**
 * Fetches the list of instances for a given process.  For each instance, it will also
 * list the tokens that are active for that instance.
 */
require(__DIR__ . '/../bootstrap.php');

if(!(count($argv) > 1)) {
    print("Usage: php ./instances.php <process-id>");
    return;
}

$processId = $argv[1];

print("Fetching instances for process " . $processId . "\n");
$response = $pmio->findInstances($processId);
$instances = $response->getData();

print("Found " . count($instances) . " Instance(s)\n");
foreach($instances as $instance) {
    print($instance->getId() . ": " . $instance->getAttributes()->getStatus() . "\n");
    // Now, let's fetch the tokens for this instance and their state
    $response = $pmio->findTokens($processId, $instance->getId());
    $tokens = $response->getData();
    if(count($tokens)) {
        foreach($tokens as $token) {
            print(" - " . $token->getId() . "\n");
        }
    } else {
        print("No active tokens for this instance.\n");
    }
    print("\n");
}

