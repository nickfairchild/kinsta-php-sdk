<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpAccountData;
use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpAccounts;
use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpConfig;
use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpPassword;
use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpStatus;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\AddAdditionalSftpAccountRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ChangeSftpPasswordExpirationRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GenerateSftpPasswordRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetAdditionalSftpAccountsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSftpConfigRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSftpIpAllowlistRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSftpPasswordRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\GetSshStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\RemoveAdditionalSftpAccountRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\SetSftpIpAllowlistRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleAdditionalSftpAccountsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleSftpAccessRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sftp\ToggleSftpPasswordAccessRequest;

/** @mixin Kinsta */
trait SupportsSftpEndpoints
{
    public function toggleSftpAccess(string $envId, bool $enabled): bool
    {
        $request = new ToggleSftpAccessRequest($envId, $enabled);

        return $this->send($request)->dto();
    }

    public function getSshStatus(string $envId): SftpStatus
    {
        $request = new GetSshStatusRequest($envId);

        return $this->send($request)->dto();
    }

    public function toggleSftpPasswordAccess(string $envId, bool $enabled): bool
    {
        $request = new ToggleSftpPasswordAccessRequest($envId, $enabled);

        return $this->send($request)->dto();
    }

    public function generateSftpPassword(string $envId): bool
    {
        $request = new GenerateSftpPasswordRequest($envId);

        return $this->send($request)->dto();
    }

    public function getSftpIpAllowlist(string $envId): array
    {
        $request = new GetSftpIpAllowlistRequest($envId);

        return $this->send($request)->dto();
    }

    public function setSftpIpAllowlist(string $envId, array $ips): bool
    {
        $request = new SetSftpIpAllowlistRequest($envId, $ips);

        return $this->send($request)->dto();
    }

    public function getSftpConfig(string $siteId, string $envId): SftpConfig
    {
        $request = new GetSftpConfigRequest($siteId, $envId);

        return $this->send($request)->dto();
    }

    public function changeSftpPasswordExpiration(string $envId, string $interval): bool
    {
        $request = new ChangeSftpPasswordExpirationRequest($envId, $interval);

        return $this->send($request)->dto();
    }

    public function getSftpPassword(string $envId): SftpPassword
    {
        $request = new GetSftpPasswordRequest($envId);

        return $this->send($request)->dto();
    }

    public function addAdditionalSftpAccount(string $envId, SftpAccountData $data): OperationResponse
    {
        $request = new AddAdditionalSftpAccountRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function getAdditionalSftpAccounts(string $envId): SftpAccounts
    {
        $request = new GetAdditionalSftpAccountsRequest($envId);

        return $this->send($request)->dto();
    }

    public function toggleAdditionalSftpAccounts(string $envId, bool $enabled): OperationResponse
    {
        $request = new ToggleAdditionalSftpAccountsRequest($envId, $enabled);

        return $this->send($request)->dto();
    }

    public function removeAdditionalSftpAccount(string $accountId): OperationResponse
    {
        $request = new RemoveAdditionalSftpAccountRequest($accountId);

        return $this->send($request)->dto();
    }
}
