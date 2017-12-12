<?php
/**
 * Creates a simple process that sends a contact email filled with values passed in via
 * the start event webhook.
 */
require(__DIR__ . '/../' . 'bootstrap.php');

use ProcessMaker\PMIO\Model\ProcessAttributes;
use ProcessMaker\PMIO\Model\ProcessCreateItem;
use ProcessMaker\PMIO\Model\Process;
use ProcessMaker\PMIO\Model\EventCreateItem;
use ProcessMaker\PMIO\Model\EventAttributes;
use ProcessMaker\PMIO\Model\Event;
use ProcessMaker\PMIO\Model\TaskCreateItem;
use ProcessMaker\PMIO\Model\TaskAttributes;
use ProcessMaker\PMIO\Model\Task;
use ProcessMaker\PMIO\Model\TaskConnectorCreateItem;
use ProcessMaker\PMIO\Model\TaskConnectorAttributes;
use ProcessMaker\PMIO\Model\TaskConnector;
use ProcessMaker\PMIO\Model\FlowCreateItem;
use ProcessMaker\PMIO\Model\FlowAttributes;
use ProcessMaker\PMIO\Model\Flow;

// First, let's create our process
$response = $pmio->addProcess(new ProcessCreateItem([
    'data' => new Process([
        'attributes' => new ProcessAttributes([
            'name' => 'Scripting Example Process'
        ])
    ])
]));
// Get the underlying data object that really represents our process
$process = $response->getData();

// Now create our start task to kick off the process
$response = $pmio->addEvent($process->getId(), new EventCreateItem([
    'data' => new Event([
        'attributes' => new EventAttributes([
            'name' => 'Start',
            'type' => 'START',
            'definition' => EventAttributes::DEFINITION_MESSAGE
        ])
    ])
]));
$startEvent = $response->getData();

$response = $pmio->addEvent($process->getId(), new EventCreateItem([
    'data' => new Event([
        'attributes' => new EventAttributes([
            'name' => 'End',
            'type' => 'END',
            'definition' => EventAttributes::DEFINITION_NONE
        ])
    ])
]));
$endEvent = $response->getData();

// Let's add a script task to uppercase our subject
$response = $pmio->addTask($process->getId(), new TaskCreateItem([
    'data' => new Task([
        'attributes' => new TaskAttributes([
            'name' => 'Convert subject line to uppercase',
            'type' => 'SCRIPT-TASK',
            'script' => '
            aData.subject = string.upper(aData.subject);
            '
        ])
    ])
]));
$scriptTask = $response->getData();

// Then we add our service task
$response = $pmio->addTask($process->getId(), new TaskCreateItem([
    'data' => new Task([
        'attributes' => new TaskAttributes([
            'name' => 'Send Contact Form Email',
            'type' => 'SERVICE-TASK'
        ])
    ])
]));
$serviceTask = $response->getData();

$template = <<<EOM
Hi there!  It looks like someone submitted a contact form!  Here's their information:<br>
<br>
Name: {name}<br>
Email: {email}<br>
Subject: {subject}<br>
Message: {message}<br>

EOM;

// Then we attach our SendMailConnector to our service task
$pmio->addTaskConnector($process->getId(), $serviceTask->getId(), new TaskConnectorCreateItem([
    'data' => new TaskConnector([
        'attributes' => new TaskConnectorAttributes([
            'connector_class' => 'SendMailConnector',
            'input_parameters' => [
                'to' => getenv('TARGET_EMAIL_ADDRESS'),
                'name' => 'Introduction Form Submission',
                'subj' => 'Contact Form Submission from {name}',
                'body' => $template
            ]
        ])
    ])
]));

// Now, let's create the flow from start event to our script task
$pmio->addFlow($process->getId(), new FlowCreateItem([
    'data' => new Flow([
        'attributes' => new FlowAttributes([
            'name' => 'Start to Service Task',
            'from_object_id' => $startEvent->getId(),
            'from_object_type' => $startEvent->getType(),
            'to_object_id' => $scriptTask->getId(),
            'to_object_type' => $scriptTask->getType()
        ])
    ])
]));

// Now, let's create the flow from script task to our service task
$pmio->addFlow($process->getId(), new FlowCreateItem([
    'data' => new Flow([
        'attributes' => new FlowAttributes([
            'name' => 'Start to Service Task',
            'from_object_id' => $scriptTask->getId(),
            'from_object_type' => $scriptTask->getType(),
            'to_object_id' => $serviceTask->getId(),
            'to_object_type' => $serviceTask->getType()
        ])
    ])
]));

// And finally, let's add the flow from the service task to our end event
$pmio->addFlow($process->getId(), new FlowCreateItem([
    'data' => new Flow([
        'attributes' => new FlowAttributes([
            'name' => 'Service Task to End',
            'from_object_id' => $serviceTask->getId(),
            'from_object_type' => $serviceTask->getType(),
            'to_object_id' => $endEvent->getId(),
            'to_object_type' => $endEvent->getType()
        ])
    ])
]));

print("Process ID: " . $process->getId() . "\n");
print("Start Event ID: " . $startEvent->getId() . "\n");
