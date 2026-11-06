<?php

use Nickfairchild\KinstaPhpSdk\Enums\ImageOptimizationType;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearCdnCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\ClearEdgeCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetEdgeCacheStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Caching\SetImageOptimizationRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can clear edge cache', function () {
    $mock = MockClient::global([
        ClearEdgeCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-edge-123',
        ]),
    ]);

    $response = $this->kinsta->clearEdgeCache('env-123', clearSubdirectories: true, url: 'https://example.com/blog');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clear-edge-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/edge-caching/clear'
        && $response->getPendingRequest()->body()->all() === [
            'environment_id' => 'env-123',
            'clear_subdirectories' => true,
            'url' => 'https://example.com/blog',
        ]);
});

it('clears all edge cache when no URL is given', function () {
    $mock = MockClient::global([
        ClearEdgeCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-edge-123',
        ]),
    ]);

    $this->kinsta->clearEdgeCache('env-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123']);
});

it('can set edge cache status', function () {
    $mock = MockClient::global([
        SetEdgeCacheStatusRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'set-edge-status-123',
        ]),
    ]);

    $response = $this->kinsta->setEdgeCacheStatus('env-123', true);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('set-edge-status-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::PUT
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/edge-caching/status'
        && $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123', 'enabled' => true]);
});

it('can clear CDN cache', function () {
    $mock = MockClient::global([
        ClearCdnCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-cdn-123',
        ]),
    ]);

    $response = $this->kinsta->clearCdnCache('env-123', 'cdn-cache-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clear-cdn-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/cdn/clear-cache'
        && $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123', 'cdn_cache_id' => 'cdn-cache-123']);
});

it('can set image optimization', function () {
    $mock = MockClient::global([
        SetImageOptimizationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'set-img-opt-123',
        ]),
    ]);

    $response = $this->kinsta->setImageOptimization('env-123', ImageOptimizationType::LOSSY);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('set-img-opt-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::PUT
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/cdn/image-optimization'
        && $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123', 'image_optimization_type' => 'lossy']);
});

it('sends image optimization off as the string "off"', function () {
    $mock = MockClient::global([
        SetImageOptimizationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'set-img-opt-123',
        ]),
    ]);

    $this->kinsta->setImageOptimization('env-123', ImageOptimizationType::OFF);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123', 'image_optimization_type' => 'off']);
});
