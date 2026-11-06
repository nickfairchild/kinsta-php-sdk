<?php

use Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools\GetDeniedIpsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools\UpdateDeniedIpsRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get denied IPs', function () {
    $mock = MockClient::global([
        GetDeniedIpsRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'ip_list' => ['192.168.1.100', '10.0.0.50'],
            ],
        ]),
    ]);

    $result = $this->kinsta->getDeniedIps('env-123');

    expect($result->ips)->toBe(['192.168.1.100', '10.0.0.50']);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/tools/denied-ips'
        && $response->getPendingRequest()->query()->all() === ['environment_id' => 'env-123']);
});

it('can update denied IPs', function () {
    $mock = MockClient::global([
        UpdateDeniedIpsRequest::class => MockResponse::make([
            'result' => null,
        ]),
    ]);

    expect($this->kinsta->updateDeniedIps('env-123', ['192.168.1.200']))->toBeTrue();

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::PUT
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/tools/denied-ips'
        && $response->getPendingRequest()->query()->all() === []
        && $response->getPendingRequest()->body()->all() === [
            'environment_id' => 'env-123',
            'ip_list' => ['192.168.1.200'],
        ]);
});
