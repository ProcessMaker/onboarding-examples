# ProcessMaker.IO Onboarding Examples
This repository contains a collection of examples in using the 
ProcessMaker.IO workflow engine. This collection utilizes the PHP 
SDK and is referenced in the ProcessMaker.IO Blog to demonstrate 
various concepts of the workflow engine.

## Getting Started
Create a .env file to store your configuration for your ProcessMaker.IO instance 
and the access token for the user you wish to use the API with. It will also contain 
configuration for some of the examples.
An example .env file would look something like this

```$xslt
PMIO_ENDPOINT=https://fds23zz.api.processmaker.io/api/v1
PMIO_ACCESS_TOKEN=eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjkwMTBmZGI5YWMxYTZiNGI2ZGMyZjNiODZmZDg5OTJhYWRlODI0MGU5MWI3NWRkNDhjNTA4MzRlYjQzZjUzNmFjMDkxNTg4MDY5MDZiM2MxIn0.eyJhdWQiOiIxIiwianRpIjoiOTAxMGZkYjlhYzFhNmI0YjZkYzJmM2I4NmZkODk5MmFhZGU4MjQwZTkxYjc1ZGQ0OGM1MDgzNGViNDNmNTM2YWMwOTE1ODgwNjkwNmIzYzEiLCJpYXQiOjE1MDY1NDM5MTMsIm5iZiI6MTUwNjU0MzkxMywiZXhwIjoxNTM4MDc5OTEzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.PTFaPvddgKqB51trFAV2Ka_8W2npZ9jbc2uGAoYdFKfMNNvvNl07DWTDXxK90B0OwLAA08husWmfkVomWlm6W44hXuJEa8zG3SfRGwpzPQiA4NZ706YkbWqjLfY5QH41MwpJlkgKeQv6Q6nvZluyXrzQlvZVkSfSb9GvNe4h6arLLn1KQp3xQnnK-Jk8Q109uEnhmkOs4mfUKj5rd0E_DKJUTc-CmXoJZ8zsvuDv-irv7PtQlpQRmVdlOJD2mTO2NNwQpLjdl4d3D4a7zKpMcrkeLmqz_Cums4uk5069Ju2TRmAbyGfWUgL7aTg-o80xWPnwY9AY7xC__mZvW1ydlpLKX18-bIpj_5acCyqQmnA921KAoO99YV3rT19I_VKF9dglzMOPWrcLzb44qK4ONXjx8Za3zyFJZpBa_SrCkhYiOhY1GNNk5EpqqMCAT_D8nCnrR7fmLrXtFJsP9yPDYwMZxYuppCH6rcqLIeB0VlmEiNTJu_1RlYgcmG02boSNPXHmzrEd9eJKRksSWVfkE90quuDMNoEFYaL1A_yU0yn2iW0T--lh_kPOGEth2WrShoYEZ667FSrSmsRYoLe8BiqGbiSquYrtDhHYhcidOeWyZLtUDMnVNi00v3i3hrIp7KIvIM9zAQxzk3OwAxXFrKV6Ut3-cqyvpMhWo9NPyZI
# Set to true if you want to log every HTTP api call
TARGET_EMAIL_ADDRESS=myaddress@mydomain.com
PMIO_DEBUG=false
PMIO_DEBUG_FILE=debug.log
```

Then run composer to install the dependencies. This will pull down a couple utility libraries as well as the 
ProcessMaker.IO PHP SDK.

```$xslt
composer install
```


## Tools
There is a tools directory that helps you with getting information 
about your workflow engine and clearing the processes.  The files are:

* tools/deleteall.php
  * Deletes all processes from your engine
* tools/instances.php
  * Retrieves process instances and their tokens for a given process id
  
## The Examples
Each example is stored in it's own directory.  There is a script 
to create a process as well as any other relevant files such as 
example HTML forms. The examples are:

### Introduction
This example shows you the most basic way to create a new process programatically. 
It also shows how to start a process by calling a Start Event's webhook and populating 
a webhook.  The process sends an email via a SendMailConnector and the email contains 
information submitted by a contact form written in HTML.

This example introduces:
* Start and End Events
* Service Tasks
* The SendMailConnector connector
* Sequential flows

#### Creating the Introductory Process
You'll need to call the API to programatically create your process.  You can do this by:
```bash
php ./introduction/create.php
```

You should receive the newly created Process ID, as well as the Start Event ID.  These 
are used in the next step as part of the webhook url to call.

```bash
$ php ./introduction/create.php
Process ID: b78fc897-91f8-4f38-b8e3-7d60267d9b67
Start Event ID: 271bf2f1-47bb-4616-9edb-3892b5696710
```

#### Modifying the HTML Form
You should modify the form.html.  You'll see a spot where you can update the pmio_webhook variable.
The URL should contain the process ID and the start event ID you gathered when creating the process.

```html
<script>
    var pmio_webook = 'https://fds23zz.api.processmaker.io/api/v1/processes/b78fc897-91f8-4f38-b8e3-7d60267d9b67/events/271bf2f1-47bb-4616-9edb-3892b5696710/webhook';
</script>
```

#### Creating Webhook URL
Video Showcase: https://www.youtube.com/watch?v=Gv_k0MXK_cs
```bash
URL Example::   https://<<API>>.api.processmaker.io/api/v1/processes/<<Process ID>>/events/<<Start Event ID>>/webhook
URL Example::   https://dshdih7j.api.processmaker.io/api/v1/processes/7es1c79b-f89e-43f8-95ab-8ca2C0a574a6/events/e131271a-c27c-42b3-a91b-75729ec24e94/webhook
```

## More Examples Are Coming!
Keep an eye out on this repository as it is updated with new examples that introduce additional 
ProcessMaker.IO concepts!