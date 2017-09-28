<?php
/**
 * Deletes all processes in our ProcessMaker.IO Workflow engine
 */
require(__DIR__ . '/../' . 'bootstrap.php');

do {
    $response = $pmio->findProcesses(1, 15);
    $processes = $response->getData();
    foreach($processes as $process) {
        print("Deleting Process " . $process->getId() . "\n");
        $pmio->deleteProcess($process->getId());
    }
} while(count($processes));


