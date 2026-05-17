<?php

use Nickfairchild\KinstaPhpSdk\Requests\Utilities\GetOperationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Utilities\ValidateAPIKeyRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can validate an api key', function () {
    MockClient::global([
        ValidateAPIKeyRequest::class => MockResponse::make(['name' => 'test', 'status' => 'active']),
    ]);

    $response = $this->kinsta->validateApiKey();

    expect($response['name'])->toBeString();
    expect($response['status'])->toBe('active');
});

it('can get an operation by its id', function () {
    MockClient::global([
        GetOperationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'data' => 'test',
        ]),
    ]);

    $response = $this->kinsta->getOperation('123');

    expect($response->message)->toBeString()->toBe('Successfully finished request')
        ->and($response->status)->toBeInt()->toBe(200)
        ->and($response->data)->toBe('test');
});
