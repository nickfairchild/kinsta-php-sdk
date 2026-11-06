<?php

use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\PhpAllocationData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\SearchAndReplaceData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentPhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeEnvironmentWebrootRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ChangeSitePhpAllocationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ClearSiteCacheRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\GetForceHttpsStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ListFilesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\ModifyPhpVersionRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\RestartPhpEngineRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\SearchAndReplaceRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools\SetForceHttpsStatusRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can clear site cache', function () {
    $mock = MockClient::global([
        ClearSiteCacheRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'clear-cache-123',
        ]),
    ]);

    $response = $this->kinsta->clearSiteCache('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('clear-cache-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/tools/clear-cache'
        && $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123']);
});

it('can restart PHP engine', function () {
    $mock = MockClient::global([
        RestartPhpEngineRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'restart-php-123',
        ]),
    ]);

    $response = $this->kinsta->restartPhpEngine('env-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('restart-php-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/tools/restart-php'
        && $response->getPendingRequest()->body()->all() === ['environment_id' => 'env-123']);
});

it('can modify PHP version', function () {
    $mock = MockClient::global([
        ModifyPhpVersionRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'modify-php-123',
        ]),
    ]);

    $response = $this->kinsta->modifyPhpVersion('env-123', '8.3', true);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('modify-php-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::PUT
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/tools/modify-php-version'
        && $response->getPendingRequest()->body()->all() === [
            'environment_id' => 'env-123',
            'php_version' => '8.3',
            'is_opt_out_from_automatic_php_update' => true,
        ]);
});

it('omits the PHP auto-update opt-out when not set', function () {
    $mock = MockClient::global([
        ModifyPhpVersionRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'modify-php-123',
        ]),
    ]);

    $this->kinsta->modifyPhpVersion('env-123', '8.3');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->body()->all() === [
        'environment_id' => 'env-123',
        'php_version' => '8.3',
    ]);
});

it('can change site PHP allocation', function () {
    $mock = MockClient::global([
        ChangeSitePhpAllocationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'php-alloc-site-123',
        ]),
    ]);

    $data = new PhpAllocationData(threadCount: 4, threadMemory: 256);

    $response = $this->kinsta->changeSitePhpAllocation('site-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('php-alloc-site-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/site-123/change-site-php-allocation'
        && $response->getPendingRequest()->body()->all() === ['thread_count' => 4, 'thread_memory' => 256]);
});

it('can change environment PHP allocation', function () {
    $mock = MockClient::global([
        ChangeEnvironmentPhpAllocationRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'php-alloc-env-123',
        ]),
    ]);

    $data = new PhpAllocationData(threadCount: 2, threadMemory: 512);

    $response = $this->kinsta->changeEnvironmentPhpAllocation('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('php-alloc-env-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/change-environment-php-allocation'
        && $response->getPendingRequest()->body()->all() === ['thread_count' => 2, 'thread_memory' => 512]);
});

it('can change environment webroot', function () {
    $mock = MockClient::global([
        ChangeEnvironmentWebrootRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'webroot-123',
        ]),
    ]);

    $response = $this->kinsta->changeEnvironmentWebroot('env-123', '/public', clearAllCache: false);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('webroot-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/change-webroot-subfolder'
        && $response->getPendingRequest()->body()->all() === ['web_root_subfolder' => '/public', 'clear_all_cache' => false]);
});

it('can list files', function () {
    $mock = MockClient::global([
        ListFilesRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'file_list' => [
                    ['name' => 'wp-content', 'path' => 'wp-content', 'children' => []],
                    ['name' => 'wp-config.php', 'path' => 'wp-config.php', 'children' => []],
                ],
            ],
        ]),
    ]);

    $response = $this->kinsta->listFiles('env-123');

    expect($response->environmentId)->toBe('env-123')
        ->and($response->files)->toHaveCount(2)
        ->and($response->files[0]['name'])->toBe('wp-content');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/file-list');
});

it('can get force HTTPS status', function () {
    $mock = MockClient::global([
        GetForceHttpsStatusRequest::class => MockResponse::make([
            'force_https_status' => 'ENABLED_FOR_ALL',
        ]),
    ]);

    $response = $this->kinsta->getForceHttpsStatus('env-123');

    expect($response->status)->toBe('ENABLED_FOR_ALL');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/force-https-status');
});

it('can set force HTTPS status', function () {
    $mock = MockClient::global([
        SetForceHttpsStatusRequest::class => MockResponse::make([
            'message' => 'Setting force HTTPS status in progress',
            'status' => 202,
            'operation_id' => 'force-https-123',
        ], 202),
    ]);

    $response = $this->kinsta->setForceHttpsStatus('env-123', 'ENABLED_REDIRECT_TO_PRIMARY');

    expect($response->status)->toBe(202)
        ->and($response->operationId)->toBe('force-https-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/force-https-status'
        && $response->getPendingRequest()->body()->all() === ['type' => 'ENABLED_REDIRECT_TO_PRIMARY']);
});

it('can search and replace', function () {
    $mock = MockClient::global([
        SearchAndReplaceRequest::class => MockResponse::make([
            'message' => 'Search and replace in progress',
            'status' => 202,
            'operation_id' => 'search-replace-123',
        ], 202),
    ]);

    $data = new SearchAndReplaceData(search: 'old-text', replace: 'new-text', performReplacement: true);

    $response = $this->kinsta->searchAndReplace('env-123', $data);

    expect($response->operationId)->toBe('search-replace-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/tools/search-and-replace'
        && $response->getPendingRequest()->body()->all() === [
            'environment_id' => 'env-123',
            'search' => 'old-text',
            'replace' => 'new-text',
            'perform_replacement' => true,
        ]);
});
