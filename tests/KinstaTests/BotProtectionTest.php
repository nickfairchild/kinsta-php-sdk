<?php

use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\ExceptionRuleData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\AddExceptionRuleRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetAiCrawlersBlockingRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetBotProtectionLevelRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetExceptionRulesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetWpAutomationsStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\RemoveExceptionRuleRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\SetAiCrawlersBlockingRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\SetBotProtectionLevelRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\SetWpAutomationsStatusRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

function botProtectionOperation(): MockResponse
{
    return MockResponse::make([
        'operation_id' => 'op-1',
        'message' => 'In progress',
        'status' => 202,
    ], 202);
}

it('can get the bot protection level', function () {
    MockClient::global([
        GetBotProtectionLevelRequest::class => MockResponse::make([
            'bot_protection' => ['id' => 'env-1', 'level' => 'block-automations'],
        ]),
    ]);

    $response = $this->kinsta->getBotProtectionLevel('env-1');

    MockClient::global()->assertSent(function (GetBotProtectionLevelRequest $request) {
        return $request->getMethod() === Method::GET
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/level';
    });

    expect($response->id)->toBe('env-1')
        ->and($response->level)->toBe('block-automations');
});

it('returns null when bot protection is not available', function () {
    MockClient::global([
        GetBotProtectionLevelRequest::class => MockResponse::make(['bot_protection' => null]),
    ]);

    expect($this->kinsta->getBotProtectionLevel('env-1'))->toBeNull();
});

it('can set the bot protection level', function () {
    MockClient::global([SetBotProtectionLevelRequest::class => botProtectionOperation()]);

    $response = $this->kinsta->setBotProtectionLevel('env-1', 'challenge-bots');

    MockClient::global()->assertSent(function (SetBotProtectionLevelRequest $request) {
        return $request->getMethod() === Method::PUT
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/level'
            && $request->body()->all() === ['level' => 'challenge-bots'];
    });

    expect($response->operationId)->toBe('op-1')
        ->and($response->status)->toBe(202);
});

it('can get the AI crawlers blocking status', function () {
    MockClient::global([
        GetAiCrawlersBlockingRequest::class => MockResponse::make([
            'bot_protection' => ['id' => 'env-1', 'ai_crawlers_blocking' => 'enabled'],
        ]),
    ]);

    $response = $this->kinsta->getAiCrawlersBlocking('env-1');

    MockClient::global()->assertSent(function (GetAiCrawlersBlockingRequest $request) {
        return $request->getMethod() === Method::GET
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/ai-crawlers';
    });

    expect($response->aiCrawlersBlocking)->toBe('enabled');
});

it('can set the AI crawlers blocking status', function () {
    MockClient::global([SetAiCrawlersBlockingRequest::class => botProtectionOperation()]);

    $response = $this->kinsta->setAiCrawlersBlocking('env-1', 'disabled');

    MockClient::global()->assertSent(function (SetAiCrawlersBlockingRequest $request) {
        return $request->getMethod() === Method::PUT
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/ai-crawlers'
            && $request->body()->all() === ['status' => 'disabled'];
    });

    expect($response->operationId)->toBe('op-1');
});

it('can get the WP automations status', function () {
    MockClient::global([
        GetWpAutomationsStatusRequest::class => MockResponse::make([
            'bot_protection' => ['id' => 'env-1', 'wp_automations' => 'disabled'],
        ]),
    ]);

    $response = $this->kinsta->getWpAutomationsStatus('env-1');

    MockClient::global()->assertSent(function (GetWpAutomationsStatusRequest $request) {
        return $request->getMethod() === Method::GET
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/wp-automations';
    });

    expect($response->wpAutomations)->toBe('disabled');
});

it('can set the WP automations status', function () {
    MockClient::global([SetWpAutomationsStatusRequest::class => botProtectionOperation()]);

    $response = $this->kinsta->setWpAutomationsStatus('env-1', 'enabled');

    MockClient::global()->assertSent(function (SetWpAutomationsStatusRequest $request) {
        return $request->getMethod() === Method::PUT
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/wp-automations'
            && $request->body()->all() === ['status' => 'enabled'];
    });

    expect($response->operationId)->toBe('op-1');
});

it('can get the bot protection exception rules', function () {
    MockClient::global([
        GetExceptionRulesRequest::class => MockResponse::make([
            'bot_protection' => [
                'id' => 'env-1',
                'exception_rules' => [
                    ['id' => 'ip:skip:192.0.2.1', 'type' => 'ip', 'action' => 'skip', 'value' => '192.0.2.1'],
                ],
                'has_custom_waf_rules' => false,
            ],
        ]),
    ]);

    $response = $this->kinsta->getBotProtectionExceptionRules('env-1');

    MockClient::global()->assertSent(function (GetExceptionRulesRequest $request) {
        return $request->getMethod() === Method::GET
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/exception-rules';
    });

    expect($response->hasCustomWafRules)->toBeFalse()
        ->and($response->exceptionRules)->toHaveCount(1)
        ->and($response->exceptionRules[0]->id)->toBe('ip:skip:192.0.2.1')
        ->and($response->exceptionRules[0]->action)->toBe('skip');
});

it('can add a bot protection exception rule', function () {
    MockClient::global([AddExceptionRuleRequest::class => botProtectionOperation()]);

    $response = $this->kinsta->addBotProtectionExceptionRule(
        'env-1',
        new ExceptionRuleData(type: 'country', action: 'block', value: 'XX'),
    );

    MockClient::global()->assertSent(function (AddExceptionRuleRequest $request) {
        return $request->getMethod() === Method::POST
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/exception-rules'
            && $request->body()->all() === ['type' => 'country', 'action' => 'block', 'value' => 'XX'];
    });

    expect($response->operationId)->toBe('op-1');
});

it('can remove a bot protection exception rule', function () {
    MockClient::global([RemoveExceptionRuleRequest::class => botProtectionOperation()]);

    $response = $this->kinsta->removeBotProtectionExceptionRule('env-1', 'ip:skip:192.0.2.1');

    MockClient::global()->assertSent(function (RemoveExceptionRuleRequest $request) {
        return $request->getMethod() === Method::DELETE
            && $request->resolveEndpoint() === '/sites/environments/env-1/bot-protection/exception-rules'
            && $request->body()->all() === ['exception_rule_id' => 'ip:skip:192.0.2.1'];
    });

    expect($response->operationId)->toBe('op-1');
});
