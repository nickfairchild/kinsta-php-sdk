<?php

use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecord;
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordDelete;
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordUpdate;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\CreateDNSRecord;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\DeleteDNSRecord;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\GetDNSRecordsForDomain;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\GetDomainsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\UpdateDNSRecord;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->kinsta = kinstaMock();
});

it('lists all domains', function () {
    MockClient::global([
        GetDomainsRequest::class => MockResponse::make([
            'company' => [
                'domains' => [
                    [
                        'id' => '123',
                        'name' => 'example.com',
                        'site_id' => '456',
                        'is_active' => true,
                    ],
                ],
            ],
        ]),
    ]);

    $responses = $this->kinsta->domains();

    foreach ($responses as $response) {
        expect($response->id)->toBeString()
            ->and($response->name)->toBeString()
            ->and($response->siteId)->toBeString()
            ->and($response->isActive)->toBeBool();
    }
    //    expect($response->message)->toBeString()
    //        ->and($response->status)->toBeInt()
    //        ->and($response->operation_id)->toBeString();
});

it('can get dns records for a domain', function () {
    MockClient::global([
        GetDNSRecordsForDomain::class => MockResponse::make([
            'domain' => [
                'dns_records' => [
                    [
                        'type' => 'A',
                        'name' => 'www',
                        'ttl' => 3600,
                        'resource_records' => [
                            [
                                'value' => '1.1.1.1',
                            ],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $responses = $this->kinsta->dnsRecordsForDomain('23947');

    foreach ($responses as $response) {
        expect($response->type)->toBeString()
            ->and($response->name)->toBeString()
            ->and($response->ttl)->toBeInt()
            ->and($response->resourceRecords)->toBeArray();
    }
});

it('can create a dns record', function () {
    MockClient::global([
        CreateDNSRecord::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => '123',
        ]),
    ]);

    $payload = new DNSRecord(
        type: 'A',
        name: 'www',
        ttl: 3600,
        resourceRecords: [
            'value' => '1.1.1.1',
        ],
    );
    $response = $this->kinsta->createDnsRecord('3487', $payload);

    expect($response->message)->toBeString()
        ->and($response->status)->toBeInt()
        ->and($response->operationId)->toBeString();
});

it('can update a dns record', function () {
    MockClient::global([
        UpdateDNSRecord::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => '123',
        ]),
    ]);

    $payload = new DNSRecordUpdate(
        type: 'A',
        name: 'www',
        ttl: 3600,
        newResourceRecords: [
            'value' => '8.8.8.8',
        ],
        removedResourceRecords: [
            'value' => '1.1.1.',
        ]
    );
    $response = $this->kinsta->updateDnsRecord('3487', $payload);

    expect($response->message)->toBeString()
        ->and($response->status)->toBeInt()
        ->and($response->operationId)->toBeString();
});

it('can delete a dns record', function () {
    MockClient::global([
        DeleteDNSRecord::class => MockResponse::make([
            'message' => 'Successfully finished request',
            'status' => 200,
            'operation_id' => '123',
        ]),
    ]);

    $payload = new DNSRecordDelete(
        type: 'A',
        name: 'www',
    );
    $response = $this->kinsta->deleteDnsRecord('3487', $payload);

    expect($response->message)->toBeString()
        ->and($response->status)->toBeInt()
        ->and($response->operationId)->toBeString();
});
