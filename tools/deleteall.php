<?php
/**
 * Deletes all processes in our ProcessMaker.IO Workflow engine
 */
require(__DIR__ . '/../' . 'bootstrap.php');

do {
    /** @var \ProcessMaker\PMIO\Client $pmio */
    $response = $pmio->listProcesses(1, 15);
    $processes = $response->getData();
    foreach($processes as $process) {
        print("Deleting Process " . $process->getId() . " (REFID: ".$process->getAttributes()->getRefId().")\n");
        $pmio->deleteProcess($process->getId());
    }
} while(count($processes));


