<?php

use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesUpdateData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\GetLogsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\GetRedirectRulesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\UpdateRedirectRulesRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get logs', function () {
    $mock = MockClient::global([
        GetLogsRequest::class => MockResponse::make([
            'environment' => [
                'container_info' => [
                    'logs' => "line 1\nline 2",
                ],
            ],
        ]),
    ]);

    $query = new LogsQuery(fileName: 'access', lines: 500, from: '2025-01-01T00:00:00Z', search: 'wp-login');
    $logs = $this->kinsta->getLogs('env-123', $query);

    expect($logs->logs)->toBe("line 1\nline 2");

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/logs'
        && $response->getPendingRequest()->query()->all() === [
            'file_name' => 'access',
            'lines' => 500,
            'from' => '2025-01-01T00:00:00Z',
            'search' => 'wp-login',
        ]);
});

it('sends default log query params', function () {
    $mock = MockClient::global([
        GetLogsRequest::class => MockResponse::make([
            'environment' => ['container_info' => ['logs' => '']],
        ]),
    ]);

    $this->kinsta->getLogs('env-123', new LogsQuery);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->query()->all() === [
        'file_name' => 'error',
        'lines' => 1000,
    ]);
});

it('can get redirect rules', function () {
    $mock = MockClient::global([
        GetRedirectRulesRequest::class => MockResponse::make([
            'environment' => [
                'id' => 'env-123',
                'container_info' => [
                    'redirect_rules' => [
                        'rules' => [
                            ['from' => '^/old-path$', 'to' => '/new-path', 'type' => 'permanent', 'domain' => null],
                        ],
                        'redirect_rules_count' => 1,
                    ],
                ],
            ],
        ]),
    ]);

    $rules = $this->kinsta->getRedirectRules('env-123', new RedirectRulesQuery(limit: 10, regexSearch: false));

    expect($rules->rules)->toBeArray()->toHaveCount(1)
        ->and($rules->count)->toBe(1);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/redirect-rules'
        && $response->getPendingRequest()->query()->all() === ['limit' => 10, 'regex_search' => 'false']);
});

it('can update redirect rules', function () {
    $mock = MockClient::global([
        UpdateRedirectRulesRequest::class => MockResponse::make([
            'result' => null,
        ]),
    ]);

    $newRule = ['domain' => null, 'from' => '^/old$', 'to' => '/new', 'type' => 'permanent'];

    expect($this->kinsta->updateRedirectRules('env-123', new RedirectRulesUpdateData(actionType: 'NEW', newValue: $newRule)))->toBeTrue();

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/redirect-rules'
        && $response->getPendingRequest()->body()->all() === [
            'action_type' => 'NEW',
            'new_value' => $newRule,
        ]);
});
