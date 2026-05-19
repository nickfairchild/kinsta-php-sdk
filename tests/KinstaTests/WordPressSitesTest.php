<?php

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\SitePayload;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CloneSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CreatePlainSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\CreateSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\DeleteSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSiteRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSitesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\ResetSiteRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get all sites', function () {
    MockClient::global([
        GetSitesRequest::class => MockResponse::make([
            'company' => [
                'sites' => [
                    [
                        'id' => 'site-123',
                        'name' => 'My WordPress Site',
                        'display_name' => 'My WordPress Site',
                        'status' => 'live',
                        'environments' => [],
                    ],
                ],
            ],
        ]),
    ]);

    $sites = $this->kinsta->sites(false);

    expect($sites)->toBeArray()->toHaveCount(1);
});

it('can get a single site', function () {
    MockClient::global([
        GetSiteRequest::class => MockResponse::make([
            'site' => [
                'id' => 'site-123',
                'name' => 'my-wp-site',
                'display_name' => 'My WordPress Site',
                'status' => 'live',
                'environments' => [],
            ],
        ]),
    ]);

    $site = $this->kinsta->site('site-123');

    expect($site->id)->toBe('site-123')
        ->and($site->name)->toBe('my-wp-site')
        ->and($site->displayName)->toBe('My WordPress Site')
        ->and($site->status)->toBe('live');
});

it('can create a WordPress site', function () {
    MockClient::global([
        CreateSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'create-site-123',
        ]),
    ]);

    $payload = new SitePayload(
        displayName: 'New WordPress Site',
        region: 'us-central',
        adminEmail: 'admin@example.com',
        adminPassword: 'password123',
        adminUser: 'admin',
        siteTitle: 'My Site',
        wpLanguage: 'en_US',
        company: 'company-id',
    );

    $response = $this->kinsta->createSite($payload);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('create-site-123');
});

it('can create a plain site', function () {
    MockClient::global([
        CreatePlainSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'create-plain-123',
        ]),
    ]);

    $response = $this->kinsta->createPlainSite('Plain Site', 'us-central');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('create-plain-123');
});

it('can clone a site', function () {
    MockClient::global([
        CloneSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'clone-site-123',
        ]),
    ]);

    $response = $this->kinsta->cloneSite('Cloned Site', 'env-source-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('clone-site-123');
});

it('can delete a site', function () {
    MockClient::global([
        DeleteSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'delete-site-123',
        ]),
    ]);

    $response = $this->kinsta->deleteSite('site-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('delete-site-123');
});

it('can reset a site', function () {
    MockClient::global([
        ResetSiteRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => 'reset-site-123',
        ]),
    ]);

    $response = $this->kinsta->resetSite('site-123', 'newpassword123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->status)->toBe(200)
        ->and($response->operationId)->toBe('reset-site-123');
});
