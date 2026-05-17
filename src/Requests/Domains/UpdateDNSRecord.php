<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Domains;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordUpdate;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDNSRecord extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected string $domainId,
        protected DNSRecordUpdate $dnsRecord
    ) {}

    public function resolveEndpoint(): string
    {
        return '/domains/'.$this->domainId.'/dns-records';
    }

    protected function defaultBody(): array
    {
        return [
            'type' => $this->dnsRecord->type,
            'name' => $this->dnsRecord->name,
            'ttl' => $this->dnsRecord->ttl,
            'new_resource_records' => $this->dnsRecord->newResourceRecords,
            'removed_resource_records' => $this->dnsRecord->removedResourceRecords,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
