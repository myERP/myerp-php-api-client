# myERP API

[![Build Status](https://travis-ci.org/myERP/myerp-php-api-client.png?branch=master)](https://travis-ci.org/myERP/myerp-php-api-client)

A PHP client library for [myERP's API](http://developers.myerp.com).

## Installation Using [Composer](http://getcomposer.org/)

Assuming composer.phar is located in your project's root directory, run the following command:

```bash
php composer.phar require myerp/myerp-php-api-client:~1.0.0
```

## Getting Started

This wrapper uses [Guzzle](https://github.com/guzzle/guzzle) to communicate with the REST web service.

- 1 - Retrieve your API_KEY and your API_EMAIL from the API settings. More information [here](http://developers.myerp.com/docs/1.0/overview/security_authentication.html).

- 2 - Initiate the client by:


```php
use MyERP\MyERP;
$myERP = new MyERP('API_EMAIL', 'API_KEY');
```
- 3 - Now you're ready to make authorized API requests to your domain!

```php
// Get a specific customer/lead
$response = $myERP->accounts()->find(261367);
$headers = $response->headers;
$customer = $response->body;
echo $customer['full_name'] . ' [id=#' . $customer['id'] . ']' . "\n";

// create a customer
$jane = [
    "type" => 2, //individual
    "status" => 1, //customer
    "first_name" => "Jane",
    "last_name" => "Doe",
    "email" => "jane.doe@mail.com"
];
$jane = $myERP->accounts()->save($jane)->body;
echo $jane['full_name'] . ' created [id=#' . $jane['id'] . ', email=' . $jane['email'] . ']' . "\n";

// update some fields
$jane['email'] = 'newemail@mail.com';
$jane = $myERP->accounts()->save($jane)->body;
echo $jane['full_name'] . ' updated [id=#' . $jane['id'] . ', email=' . $jane['email'] . ']' . "\n";

// Retrieve all customers and leads using pagination
$limit = 100;
$page = 0;
do {
  $response = $myERP->accounts()->findAll(['offset' => $page * $limit, 'limit'=> $limit]);
  foreach ($response->body as $customer) {
      echo $customer['full_name'] . ' [id=#' . $customer['id'] . ']' . "\n";
  }
  $page++;
} while ($response->hasNextPage());

// delete a customer
$byeJane = $myERP->accounts()->delete(261368)->body;
echo $byeJane['full_name'] . ' deleted [id=#' . $byeJane['id'] . ', email=' . $byeJane['email'] . ']' . "\n";

// bulk creation/modification
$customers = $myERP->accounts()->bulkSave([$jane, $john, $dave])->body;
var_dump($customers);

// bulk deletion
$customers = $myERP->accounts()->delete([$jane, $john, $dave])->body;
// or $customers = $myERP->accounts()->delete([['id' => 12345], ['id' => 12346], ['id' => 12347]])->body;
var_dump($customers);


// catching errors
try {
  $response = $myERP->accounts()->find(2613670);
  //....
} catch(APIException $e) {
  echo $e->getCode() . ' ' . $e->getMessage();
}
```

## Contributing

Thanks for considering contributing to this project.

### Finding something to do

Ask, or pick an issue and comment on it announcing your desire to work on it. Ideally wait until we assign it to you to minimize work duplication.

### Reporting an issue

- Search existing issues before raising a new one.

- Include as much detail as possible.

### Pull requests

- Make it clear in the issue tracker what you are working on, so that someone else doesn't duplicate the work.

- Use a feature branch, not master.

- Rebase your feature branch onto origin/master before raising the PR.

- Keep up to date with changes in master so your PR is easy to merge.

- Be descriptive in your PR message: what is it for, why is it needed, etc.

- Make sure the tests pass

- Squash related commits as much as possible.

### Coding style

- Try to match the existing indent style.

- Don't abuse the pre-processor.

- Don't mix platform-specific stuff into the main code.


## License

The myERP API wrapper is released under the [MIT License](http://www.opensource.org/licenses/MIT).
