<?php

use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\AddSiteDomainData;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\AddSiteDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\ChangePrimaryDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\DeleteSiteDomainRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\GetSiteDomainsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteDomains\GetVerificationRecordsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('can get site domains', function () {
    MockClient::global([
        GetSiteDomainsRequest::class => MockResponse::make([
            'site_domains' => [
                ['id' => 'domain-123', 'name' => 'example.com', 'is_primary' => true],
                ['id' => 'domain-456', 'name' => 'www.example.com', 'is_primary' => false],
            ],
        ]),
    ]);

    $response = $this->kinsta->getSiteDomains('env-123');

    expect($response->domains)->toBeArray()->toHaveCount(2);
});

it('can add a site domain', function () {
    MockClient::global([
        AddSiteDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'add-domain-123',
        ]),
    ]);

    $data = new AddSiteDomainData(domain: 'newexample.com');

    $response = $this->kinsta->addSiteDomain('env-123', $data);

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('add-domain-123');
});

it('can delete a site domain', function () {
    MockClient::global([
        DeleteSiteDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'delete-domain-123',
        ]),
    ]);

    $response = $this->kinsta->deleteSiteDomain('env-123', 'domain-123');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('delete-domain-123');
});

it('can get verification records', function () {
    MockClient::global([
        GetVerificationRecordsRequest::class => MockResponse::make([
            'verification_records' => [
                ['type' => 'TXT', 'name' => '_verification', 'value' => 'verify123'],
            ],
        ]),
    ]);

    $response = $this->kinsta->getVerificationRecords('domain-123');

    expect($response->records)->toBeArray()->toHaveCount(1);
});

it('can change primary domain', function () {
    MockClient::global([
        ChangePrimaryDomainRequest::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 202,
            'operation_id' => 'change-primary-123',
        ]),
    ]);

    $response = $this->kinsta->changePrimaryDomain('env-123', 'domain-456');

    expect($response->message)->toBe('Successfully finished request')
        ->and($response->operationId)->toBe('change-primary-123');
});
