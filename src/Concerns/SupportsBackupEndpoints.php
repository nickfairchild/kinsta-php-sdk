<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\AddManualBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\BackupEnvironment;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\DownloadableBackupEnvironment;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\RestoreBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\AddManualBackupRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\GetBackupsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\GetDownloadableBackupsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\RemoveBackupRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Backups\RestoreBackupRequest;

/** @mixin Kinsta */
trait SupportsBackupEndpoints
{
    public function backups(string $envId): BackupEnvironment
    {
        $request = new GetBackupsRequest($envId);

        return $this->send($request)->dto();
    }

    public function downloadableBackups(string $envId): DownloadableBackupEnvironment
    {
        $request = new GetDownloadableBackupsRequest($envId);

        return $this->send($request)->dto();
    }

    public function restoreBackup(string $targetEnvId, RestoreBackupRequestData $data): OperationResponse
    {
        $request = new RestoreBackupRequest($targetEnvId, $data);

        return $this->send($request)->dto();
    }

    public function addManualBackup(string $envId, AddManualBackupRequestData $data): OperationResponse
    {
        $request = new AddManualBackupRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function removeBackup(int $backupId): OperationResponse
    {
        $request = new RemoveBackupRequest($backupId);

        return $this->send($request)->dto();
    }
}
