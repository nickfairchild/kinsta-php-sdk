<?php

use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\CheckWpAdminUserExistsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\CreateWpAdminUserRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GeneratePhpMyAdminLinkRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWordPressPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWordPressThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWpAdminLoginLinkRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\RunWpCliCommandRequest;
use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can run WP-CLI command', function () {
    $mock = MockClient::global([
        RunWpCliCommandRequest::class => MockResponse::make([
            'operation_id' => 'wp-cli-123',
            'message' => 'Running WP-CLI command in progress',
            'status' => 202,
        ], 202),
    ]);

    $response = $this->kinsta->runWpCliCommand('env-123', 'plugin list');

    expect($response->status)->toBe(202)
        ->and($response->operationId)->toBe('wp-cli-123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/run-wp-cli-command'
        && $response->getPendingRequest()->body()->all() === ['wp_command' => 'plugin list']);
});

it('can generate phpMyAdmin link', function () {
    $mock = MockClient::global([
        GeneratePhpMyAdminLinkRequest::class => MockResponse::make([
            'url' => 'https://pma.example.com/?token=abc',
            'expires_at' => 1735689600,
        ]),
    ]);

    $link = $this->kinsta->generatePhpMyAdminLink('env-123');

    expect($link->url)->toBe('https://pma.example.com/?token=abc')
        ->and($link->expiresAt)->toBe(1735689600);

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/pma-login-token');
});

it('can get WordPress plugins', function () {
    $mock = MockClient::global([
        GetWordPressPluginsRequest::class => MockResponse::make([
            'environment' => [
                'plugins' => [
                    'items' => [
                        ['name' => 'akismet', 'title' => 'Akismet', 'version' => '5.3'],
                        ['name' => 'hello-dolly', 'title' => 'Hello Dolly', 'version' => '1.7.2'],
                    ],
                    'last_updated_at' => '2025-01-01T00:00:00Z',
                ],
            ],
        ]),
    ]);

    $plugins = $this->kinsta->getWordPressPlugins('env-123');

    expect($plugins->plugins)->toBeArray()->toHaveCount(2)
        ->and($plugins->lastUpdatedAt)->toBe('2025-01-01T00:00:00Z');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/wp-plugins');
});

it('can get WordPress themes', function () {
    $mock = MockClient::global([
        GetWordPressThemesRequest::class => MockResponse::make([
            'environment' => [
                'themes' => [
                    'items' => [
                        ['name' => 'twentytwentyfour', 'title' => 'Twenty Twenty-Four', 'version' => '1.0'],
                        ['name' => 'twentytwentythree', 'title' => 'Twenty Twenty-Three', 'version' => '1.2'],
                    ],
                    'last_updated_at' => '2025-01-01T00:00:00Z',
                ],
            ],
        ]),
    ]);

    $themes = $this->kinsta->getWordPressThemes('env-123');

    expect($themes->themes)->toBeArray()->toHaveCount(2)
        ->and($themes->lastUpdatedAt)->toBe('2025-01-01T00:00:00Z');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/wp-themes');
});

it('can get WP admin login link', function () {
    $mock = MockClient::global([
        GetWpAdminLoginLinkRequest::class => MockResponse::make([
            'login_link' => 'https://example.com/wp-admin?token=abc123',
        ]),
    ]);

    $result = $this->kinsta->getWpAdminLoginLink('env-123', 'user@example.com');

    expect($result->loginUrl)->toBe('https://example.com/wp-admin?token=abc123');

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/wpa-login-url'
        && $response->getPendingRequest()->body()->all() === ['email' => 'user@example.com']);
});

it('can check if WP admin user exists', function () {
    $mock = MockClient::global([
        CheckWpAdminUserExistsRequest::class => MockResponse::make([
            'has_wp_admin_user' => true,
        ]),
    ]);

    $result = $this->kinsta->checkWpAdminUserExists('env-123', 'user@example.com');

    expect($result->exists)->toBeTrue();

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::GET
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/wpa-user-exists'
        && $response->getPendingRequest()->query()->all() === ['email' => 'user@example.com']);
});

it('can create WP admin user', function () {
    $mock = MockClient::global([
        CreateWpAdminUserRequest::class => MockResponse::make([
            'create_wp_admin_user' => true,
        ]),
    ]);

    $data = new WpAdminUserData(
        email: 'newuser@example.com',
        firstName: 'John',
        lastName: 'Doe',
    );

    $result = $this->kinsta->createWpAdminUser('env-123', $data);

    expect($result->created)->toBeTrue();

    $mock->assertSent(fn ($request, $response) => $response->getPendingRequest()->getMethod() === Method::POST
        && $response->getPendingRequest()->getUrl() === 'https://api.kinsta.com/v2/sites/environments/env-123/wpa-create-user'
        && $response->getPendingRequest()->body()->all() === [
            'email' => 'newuser@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
});
