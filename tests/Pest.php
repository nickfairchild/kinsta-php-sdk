<?php

use Nickfairchild\KinstaPhpSdk\Kinsta;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;

Config::preventStrayRequests();

uses()->in(__DIR__);

function kinstaMock(): Kinsta
{
    MockClient::destroyGlobal();

    return new Kinsta('fake-token');
}

function markTestComplete()
{
    expect(true)->toBeTrue();
}
