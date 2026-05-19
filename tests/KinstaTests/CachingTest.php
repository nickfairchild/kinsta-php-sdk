<?php

use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearCdnCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearEdgeCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetEdgeCacheStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetImageOptimizationRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can clear edge cache', function () {
    MockClient::global([
        ClearEdgeCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-edge-123',
        ]),
    ]);

    $response = $this->kinsta->clearEdgeCache('site-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clear-edge-123');
});

it('can set edge cache status', function () {
    MockClient::global([
        SetEdgeCacheStatusRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'set-edge-status-123',
        ]),
    ]);

    $response = $this->kinsta->setEdgeCacheStatus('site-123', true);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('set-edge-status-123');
});

it('can clear CDN cache', function () {
    MockClient::global([
        ClearCdnCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-cdn-123',
        ]),
    ]);

    $response = $this->kinsta->clearCdnCache('site-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clear-cdn-123');
});

it('can set image optimization', function () {
    MockClient::global([
        SetImageOptimizationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'set-img-opt-123',
        ]),
    ]);

    $response = $this->kinsta->setImageOptimization('site-123', true);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('set-img-opt-123');
});
