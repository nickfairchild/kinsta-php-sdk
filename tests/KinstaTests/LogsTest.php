<?php

use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\GetLogsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\GetRedirectRulesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs\UpdateRedirectRulesRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get logs', function () {
    MockClient::global([
        GetLogsRequest::class => MockResponse::make([
            'logs' => [
                ['timestamp' => '2024-01-01 12:00:00', 'message' => 'Log entry 1'],
                ['timestamp' => '2024-01-01 12:01:00', 'message' => 'Log entry 2'],
            ],
        ]),
    ]);

    $query = new LogsQuery(logType: 'access');
    $logs = $this->kinsta->getLogs('env-123', $query);

    expect($logs->logs)->toBeArray()->toHaveCount(2);
});

it('can get redirect rules', function () {
    MockClient::global([
        GetRedirectRulesRequest::class => MockResponse::make([
            'redirect_rules' => [
                ['source' => '/old-path', 'target' => '/new-path', 'type' => 301],
            ],
        ]),
    ]);

    $rules = $this->kinsta->getRedirectRules('env-123');

    expect($rules->rules)->toBeArray()->toHaveCount(1);
});

it('can update redirect rules', function () {
    MockClient::global([
        UpdateRedirectRulesRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'update-rules-123',
        ]),
    ]);

    $rules = [
        ['source' => '/old', 'target' => '/new', 'type' => 301],
    ];

    $response = $this->kinsta->updateRedirectRules('env-123', $rules);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('update-rules-123');
});
