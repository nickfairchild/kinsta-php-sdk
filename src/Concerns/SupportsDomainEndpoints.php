<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecord;
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordDelete;
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordUpdate;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\CreateDNSRecord;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\DeleteDNSRecord;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\GetDNSRecordsForDomain;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\GetDomainsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Domains\UpdateDNSRecord;

/** mixin Kinsta */
trait SupportsDomainEndpoints
{
    public function domains(): array
    {
        $request = new GetDomainsRequest($this->companyId);

        return $this->send($request)->dto();
    }

    public function dnsRecordsForDomain(string $domainId): array
    {
        $request = new GetDNSRecordsForDomain($domainId);

        return $this->send($request)->dto();
    }

    public function createDnsRecord(string $domainId, DNSRecord $dnsRecord): OperationResponse
    {
        $request = new CreateDNSRecord($domainId, $dnsRecord);

        return $this->send($request)->dto();
    }

    public function updateDnsRecord(string $domainId, DNSRecordUpdate $dnsRecord): OperationResponse
    {
        $request = new UpdateDNSRecord($domainId, $dnsRecord);

        return $this->send($request)->dto();
    }

    public function deleteDnsRecord(string $domainId, DNSRecordDelete $dnsRecord): OperationResponse
    {
        $request = new DeleteDNSRecord($domainId, $dnsRecord);

        return $this->send($request)->dto();
    }
}
