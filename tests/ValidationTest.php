<?php

namespace Test\DevCoder\Validator;

use DevCoder\Validator\Assert\Alphabetic;
use DevCoder\Validator\Assert\Choice;
use DevCoder\Validator\Assert\Custom;
use DevCoder\Validator\Assert\Email;
use DevCoder\Validator\Assert\Integer;
use DevCoder\Validator\Assert\NotNull;
use DevCoder\Validator\Assert\Numeric;
use DevCoder\Validator\Assert\StringLength;
use DevCoder\Validator\Assert\Url;
use DevCoder\Validator\Validation;
use PHPUnit\Framework\TestCase;
use Test\DevCoder\Validator\Helper\Request;

class ValidationTest extends TestCase
{
    public function testOk()
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

        $this->assertTrue($validation->validate(Request::create([
            'email' => 'dev@devcoder.xyz',
            'password' => 'Mypassword',
            'firstname' => 'devcoder',
            'lastname' => null,
            'gender' => 'Mr',
            'website' => 'https://www.devcoder.xyz',
            'age' => 20,
            'invoice_total' => '2000.25',
            'active' => true,
        ])));
    }

    public function testError()
    {
        $validation = new Validation([
            'email' => [new NotNull(), new Email()],
            'password' => new NotNull(),
            'firstname' => [new NotNull(), (new StringLength())->min(3), new Alphabetic()],
            'lastname' => [(new StringLength())->min(3)],
            'website' => [new NotNull(), new Url()],
            'invoice_total' => [new NotNull(), new Numeric()],
            'active' => [new NotNull(), new Custom(function ($value) {
                return is_bool($value);
            })]
        ]);

        $this->assertFalse($validation->validate(Request::create([
            'email' => 'dev@devcoder',
            'password' => null,
            'firstname' => '12',
            'lastname' => '12',
            'website' => 'www.devcoder',
            'invoice_total' => 'test2000.25',
            'active' => 'yes',
        ])));


        $this->assertSame(7, count($validation->getErrors()));
        $errors = $validation->getErrors();
        $this->assertSame(2, count($errors['firstname']));

        $this->assertSame($errors['email'][0], 'dev@devcoder is not a valid email address.');
        $this->assertSame($errors['active'][0], '"yes" is not valid');
    }
}