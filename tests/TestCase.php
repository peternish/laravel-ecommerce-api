<?php

namespace Tests;

use App\Models\User;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function getRandomUser()
    {
        return User::all()->random();
    }

    public function getFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }
}
