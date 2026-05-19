<?php

use Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools\GetDeniedIpsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\AdditionalTools\UpdateDeniedIpsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get denied IPs', function () {
    MockClient::global([
        GetDeniedIpsRequest::class => MockResponse::make([
            'denied_ips' => ['192.168.1.100', '10.0.0.50'],
        ]),
    ]);

    $result = $this->kinsta->getDeniedIps('env-123');

    expect($result->ips)->toBeArray()->toHaveCount(2);
});

it('can update denied IPs', function () {
    MockClient::global([
        UpdateDeniedIpsRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'update-ips-123',
        ]),
    ]);

    $response = $this->kinsta->updateDeniedIps('env-123', ['192.168.1.200']);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('update-ips-123');
});
