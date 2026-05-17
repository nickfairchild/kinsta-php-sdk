<?php

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
