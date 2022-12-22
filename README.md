# Fast, extensible and simple PHP validation classes to validate any data.
## Installation

Use [Composer](https://getcomposer.org/)

### Composer Require

```
composer require devcoder-xyz/php-validator
```

## Requirements

* PHP version 7.3
* Need package for PSR-7 HTTP Message
  (example : guzzlehttp/psr7 )

**How to use ?**

```php
<?php
class RegisterController {

    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request)
    {
       $validation = new Validation([
            'email' => [new NotNull(), new Email()],
            'password' => new NotNull(),
            'firstname' => [new NotNull(), (new StringLength())->min(3), new Alphabetic()],
            'lastname' => [(new StringLength())->min(3)],
            'gender' => new Choice(['Mme', 'Mr', null]),
            'website' => [new NotNull(), new Url()],
            'age' => [new NotNull(), (new Integer())->min(18)],
            'invoice_total' => [new NotNull(), new Numeric()],
            'active' => [new NotNull(), new Custom(function ($value) {
                return is_bool($value);
            })]
        ]);
        
        if ($validation->validate($request) === true) {
            $data = $validation->getData();
            // save in database
            // redirect in another page
        }
        
        return render('template.html.php', [
            'errors' => $validation->getErrors()
        ]);
    }
}

```

Ideal for small project.
Simple and easy!