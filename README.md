# Kinsta PHP SDK

![Tests](https://github.com/nickfairchild/kinsta-php-sdk/workflows/run-tests/badge.svg)

A PHP SDK for the [Kinsta API](https://api-docs.kinsta.com/pages/introduction), built with [Saloon](https://docs.saloon.dev/) v4.

It covers every operation in **Kinsta API v1.122.0**: 116 documented operations, plus one undocumented one (see [Known limitations](#known-limitations)). Requests and responses are mapped to typed, read-only DTOs.

```php
use Nickfairchild\KinstaPhpSdk\Kinsta;

$kinsta = new Kinsta('your-api-key', 'your-company-id');

foreach ($kinsta->sites() as $site) {
    echo "{$site->displayName} ({$site->id})\n";
}
```

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Authentication](#authentication)
- [Core concepts](#core-concepts)
- [Endpoint reference](#endpoint-reference)
  - [WordPress sites](#wordpress-sites)
  - [Site environments](#site-environments)
  - [Site domains](#site-domains)
  - [Site tools](#site-tools)
  - [WordPress tools](#wordpress-tools)
  - [Themes & plugins](#themes--plugins)
  - [Edge caching & CDN](#edge-caching--cdn)
  - [Bot protection](#bot-protection)
  - [Backups](#backups)
  - [SFTP / SSH](#sftp--ssh)
  - [Additional SFTP users](#additional-sftp-users)
  - [Logs & redirect rules](#logs--redirect-rules)
  - [Analytics](#analytics)
  - [Company](#company)
  - [Domains & DNS](#domains--dns)
  - [Additional tools](#additional-tools)
  - [Operations & utilities](#operations--utilities)
- [Enums](#enums)
- [Error handling](#error-handling)
- [Testing your code](#testing-your-code)
- [Known limitations](#known-limitations)
- [Development](#development)

## Requirements

- PHP 8.3 or newer
- [saloonphp/saloon](https://github.com/saloonphp/saloon) ^4.0, installed automatically

## Installation

```bash
composer require nickfairchild/kinsta-php-sdk
```

## Authentication

You need an API key and your company ID:

- **API key**: in [MyKinsta](https://my.kinsta.com/), go to your name > **Company settings** > **API Keys**.
- **Company ID**: in MyKinsta, go to your name > **Company settings** > **Billing details**.

```php
use Nickfairchild\KinstaPhpSdk\Kinsta;

$kinsta = new Kinsta(
    apiKey: getenv('KINSTA_API_KEY'),
    companyId: getenv('KINSTA_COMPANY_ID'),
);
```

The SDK sends the key as a bearer token to `https://api.kinsta.com/v2`. Endpoints that need a company ID (sites, analytics, company users and so on) take it from the client, so you never pass it per call.

> [!CAUTION]
> Don't hard-code your API key or commit it to version control. Load it from an environment variable or a secrets manager.

## Core concepts

### Site IDs vs environment IDs

Most endpoints act on an **environment** (live, staging and so on), not a site. Get environment IDs from a site:

```php
$environments = $kinsta->getSiteEnvironments($siteId)->environments;

foreach ($environments as $environment) {
    echo "{$environment->displayName}: {$environment->id}\n";
}

$envId = $environments[0]->id;
```

In the reference below, `$siteId` is a site ID and `$envId` is an environment ID.

### Asynchronous operations

Many write operations (creating sites, backups, clearing cache and so on) are processed asynchronously. They return an `OperationResponse`:

| Property             | Type      | Notes                                        |
|----------------------|-----------|----------------------------------------------|
| `message`            | `string`  |                                              |
| `status`             | `int`     | Usually `202`                                |
| `operationId`        | `?string` | Not every response includes one              |
| `supportReferenceId` | `?int`    |                                              |
| `warning`            | `?string` | Returned by some create operations           |

Check progress with `getOperation()`:

```php
$response = $kinsta->addManualBackup($envId, new AddManualBackupRequestData(tag: 'before-deploy'));

if ($response->operationId !== null) {
    $operation = $kinsta->getOperation($response->operationId);

    echo $operation->status;  // e.g. 200 when finished, 202 while in progress
    echo $operation->message;
}
```

### Endpoints that return `bool`

Some endpoints respond with only `{"result": null}`. For those the SDK returns `true` on success. Failures still throw a [`KinstaException`](#error-handling).

### Input DTOs and enums

Write operations take small read-only DTOs (for example `SitePayload` or `PushEnvironmentData`). Optional DTO properties are only sent when set. Fixed-value parameters use PHP enums where available; see [Enums](#enums).

### Nullable enum responses

Where a response field is parsed into an enum (bot-protection level, force-HTTPS status), any value not in the documented set becomes `null` instead of throwing. That way a new value added by Kinsta won't break your integration.

---

## Endpoint reference

Each section lists the SDK method, the HTTP request it makes, what it returns, and the matching page in the [Kinsta API docs](https://api-docs.kinsta.com/pages/introduction).

### WordPress sites

[Kinsta docs: WordPress Sites](https://api-docs.kinsta.com/api-reference/wordpress-sites/get-list-of-company-sites)

| Method | HTTP | Returns |
|---|---|---|
| `sites(?bool $includeEnvironments = null)` | `GET /sites` | `Site[]` |
| `site(string $siteId)` | `GET /sites/{site_id}` | `Site` |
| `createSite(SitePayload $payload)` | `POST /sites` | `OperationResponse` |
| `createPlainSite(string $displayName, string $region)` | `POST /sites/plain` | `OperationResponse` |
| `cloneSite(string $displayName, string $siteId)` | `POST /sites/clone` | `OperationResponse` |
| `deleteSite(string $siteId)` | `DELETE /sites/{site_id}` | `OperationResponse` |
| `resetSite(string $siteId, string $adminPassword)` | `POST /sites/{site_id}/reset-site` | `OperationResponse` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites\SitePayload;

// List sites, including their environments
$sites = $kinsta->sites(includeEnvironments: true);

// One site
$site = $kinsta->site($siteId);
echo $site->displayName, ' - ', $site->status;

// Create a WordPress site
$response = $kinsta->createSite(new SitePayload(
    displayName: 'My Site',
    region: 'us-central1',
    adminEmail: 'admin@example.com',
    adminPassword: 'a-strong-password',
    adminUser: 'admin',
    siteTitle: 'My Site',
    wpLanguage: 'en_US',
    company: $companyId,
    isMultisite: false,
    isSubdomainMultisite: false,
));

// Create an empty site (no WordPress install)
$kinsta->createPlainSite(displayName: 'Blank Site', region: 'us-central1');

// Clone an existing site
$kinsta->cloneSite(displayName: 'My Site Copy', siteId: $siteId);

// Reset a site to a fresh WordPress install
$kinsta->resetSite($siteId, adminPassword: 'a-strong-password');

// Delete a site
$kinsta->deleteSite($siteId);
```

The `company` argument is required by `SitePayload`, but the request always sends the company ID the client was created with.

`SitePayload` also accepts `woocommerce`, `wordpressSeo` (both deprecated by Kinsta) and `installMode` (`new` by default; documented values `new`, `plain`, `clone`).

### Site environments

[Kinsta docs: WordPress Site Environments](https://api-docs.kinsta.com/api-reference/wordpress-site-environments/get-site-environments)

| Method | HTTP | Returns |
|---|---|---|
| `getSiteEnvironments(string $siteId)` | `GET /sites/{site_id}/environments` | `EnvironmentsCollection` |
| `getEnvironment(string $envId)` | `GET /sites/environments/{env_id}` ⚠️ undocumented | `Environment` |
| `createEnvironment(string $siteId, CreateEnvironmentData $data)` | `POST /sites/{site_id}/environments` | `OperationResponse` |
| `createPlainEnvironment(string $siteId, CreatePlainEnvironmentData $data)` | `POST /sites/{site_id}/environments/plain` | `OperationResponse` |
| `cloneEnvironment(string $siteId, CloneEnvironmentData $data)` | `POST /sites/{site_id}/environments/clone` | `OperationResponse` |
| `pushEnvironment(string $siteId, PushEnvironmentData $data)` | `PUT /sites/{site_id}/environments` | `OperationResponse` |
| `deleteEnvironment(string $envId)` | `DELETE /sites/environments/{env_id}` | `OperationResponse` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CloneEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreateEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\CreatePlainEnvironmentData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteEnvironments\PushEnvironmentData;

// New staging environment with a fresh WordPress install
$kinsta->createEnvironment($siteId, new CreateEnvironmentData(
    displayName: 'Staging',
    siteTitle: 'My Site (staging)',
    isPremium: false,
    adminEmail: 'admin@example.com',
    adminPassword: 'a-strong-password',
    adminUser: 'admin',
    wpLanguage: 'en_US',
));

// Empty environment
$kinsta->createPlainEnvironment($siteId, new CreatePlainEnvironmentData(displayName: 'Empty'));

// Clone live into a new environment (Kinsta limits display names to 12 characters)
$kinsta->cloneEnvironment($siteId, new CloneEnvironmentData(
    displayName: 'Hotfix',
    sourceEnvId: $liveEnvId,
));

// Push staging to live
$kinsta->pushEnvironment($siteId, new PushEnvironmentData(
    sourceEnvId: $stagingEnvId,
    targetEnvId: $liveEnvId,
    pushDb: true,
    pushFiles: true,
    runSearchAndReplace: true,
    pushFilesOption: 'SPECIFIC_FILES',   // or 'ALL_FILES'
    fileList: ['wp-content/themes', 'wp-content/plugins'],
));

$kinsta->deleteEnvironment($stagingEnvId);
```

### Site domains

[Kinsta docs: WordPress Site Domains](https://api-docs.kinsta.com/api-reference/wordpress-site-domains/get-list-of-site-domains-for-the-environment)

| Method | HTTP | Returns |
|---|---|---|
| `getSiteDomains(string $envId)` | `GET /sites/environments/{env_id}/domains` | `SiteDomains` |
| `addSiteDomain(string $envId, AddSiteDomainData $data)` | `POST /sites/environments/{env_id}/domains` | `OperationResponse` |
| `deleteSiteDomain(string $envId, string\|array $domainIds)` | `DELETE /sites/environments/{env_id}/domains` | `OperationResponse` |
| `changePrimaryDomain(string $envId, string $domainId, bool $runSearchAndReplace = true)` | `PUT /sites/environments/{env_id}/change-primary-domain` | `OperationResponse` |
| `getVerificationRecords(string $siteDomainId)` | `GET /sites/environments/domains/{site_domain_id}/verification-records` | `VerificationRecords` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\SiteDomains\AddSiteDomainData;

$kinsta->addSiteDomain($envId, new AddSiteDomainData(
    domain: 'example.com',
    addWithWwwSubdomain: true,
    setupType: 'quick',           // or 'avoid_downtime'
    isWildcardless: true,
    // customSslKey: '...', customSslCert: '...',
));

$domains = $kinsta->getSiteDomains($envId)->domains;

$records = $kinsta->getVerificationRecords($siteDomainId);
$records->records;           // verification records
$records->pointingRecords;

$kinsta->changePrimaryDomain($envId, $domainId);

// Delete one domain or several
$kinsta->deleteSiteDomain($envId, $domainId);
$kinsta->deleteSiteDomain($envId, [$domainId, $otherDomainId]);
```

### Site tools

[Kinsta docs: WordPress Site Tools](https://api-docs.kinsta.com/api-reference/wordpress-site-tools/clear-site-cache)

| Method | HTTP | Returns |
|---|---|---|
| `clearSiteCache(string $envId)` | `POST /sites/tools/clear-cache` | `OperationResponse` |
| `restartPhpEngine(string $envId)` | `POST /sites/tools/restart-php` | `OperationResponse` |
| `modifyPhpVersion(string $envId, string $phpVersion, ?bool $optOutFromAutomaticPhpUpdate = null)` | `PUT /sites/tools/modify-php-version` | `OperationResponse` |
| `changeSitePhpAllocation(string $siteId, PhpAllocationData $data)` | `POST /sites/{site_id}/change-site-php-allocation` | `OperationResponse` |
| `changeEnvironmentPhpAllocation(string $envId, PhpAllocationData $data)` | `POST /sites/environments/{env_id}/change-environment-php-allocation` | `OperationResponse` |
| `changeEnvironmentWebroot(string $envId, string $subfolder, ?bool $clearAllCache = null, ?bool $refreshPluginsAndThemes = null)` | `POST /sites/environments/{env_id}/change-webroot-subfolder` | `OperationResponse` |
| `listFiles(string $envId)` | `GET /sites/environments/{env_id}/file-list` | `FileListResult` |
| `getForceHttpsStatus(string $envId)` | `GET /sites/environments/{env_id}/force-https-status` | `ForceHttpsStatus` |
| `setForceHttpsStatus(string $envId, ForceHttpsType $type)` | `POST /sites/environments/{env_id}/force-https-status` | `OperationResponse` |
| `searchAndReplace(string $envId, SearchAndReplaceData $data)` | `POST /sites/tools/search-and-replace` | `OperationResponse` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\PhpAllocationData;
use Nickfairchild\KinstaPhpSdk\Dto\SiteTools\SearchAndReplaceData;
use Nickfairchild\KinstaPhpSdk\Enums\ForceHttpsType;

$kinsta->clearSiteCache($envId);
$kinsta->restartPhpEngine($envId);
$kinsta->modifyPhpVersion($envId, '8.3');

// PHP threads: live and standard staging environments (by site)...
$kinsta->changeSitePhpAllocation($siteId, new PhpAllocationData(threadCount: 4, threadMemory: 256));
// ...or a premium staging environment
$kinsta->changeEnvironmentPhpAllocation($envId, new PhpAllocationData(threadCount: 2, threadMemory: 256));

$kinsta->changeEnvironmentWebroot($envId, '/public');

$files = $kinsta->listFiles($envId);

// Force HTTPS
$kinsta->setForceHttpsStatus($envId, ForceHttpsType::ENABLED_REDIRECT_TO_PRIMARY);
$status = $kinsta->getForceHttpsStatus($envId)->status; // ?ForceHttpsType

// Search and replace across the database (performReplacement: false only previews matches)
$kinsta->searchAndReplace($envId, new SearchAndReplaceData(
    search: 'http://old.example.com',
    replace: 'https://new.example.com',
    performReplacement: true,
    isClearCache: true,
));
```

### WordPress tools

[Kinsta docs: WordPress Site Tools](https://api-docs.kinsta.com/api-reference/wordpress-site-tools/clear-site-cache) and [Themes & Plugins](https://api-docs.kinsta.com/api-reference/wordpress-site-themes-&-plugins/get-list-of-wp-site-plugins)

| Method | HTTP | Returns |
|---|---|---|
| `runWpCliCommand(string $envId, string $command)` | `POST /sites/environments/{env_id}/run-wp-cli-command` | `OperationResponse` |
| `generatePhpMyAdminLink(string $envId)` | `POST /sites/environments/{env_id}/pma-login-token` | `PhpMyAdminLink` |
| `getWordPressPlugins(string $envId)` | `GET /sites/environments/{env_id}/wp-plugins` | `WordPressPlugins` |
| `getWordPressThemes(string $envId)` | `GET /sites/environments/{env_id}/wp-themes` | `WordPressThemes` |
| `getWpAdminLoginLink(string $envId, string $email)` | `POST /sites/environments/{env_id}/wpa-login-url` | `WpAdminUserResult` |
| `checkWpAdminUserExists(string $envId, string $email)` | `GET /sites/environments/{env_id}/wpa-user-exists` | `WpAdminUserResult` |
| `createWpAdminUser(string $envId, WpAdminUserData $data)` | `POST /sites/environments/{env_id}/wpa-create-user` | `WpAdminUserResult` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserData;

// WP-CLI runs asynchronously; poll the operation for the result
$operation = $kinsta->runWpCliCommand($envId, 'wp cache flush');

$link = $kinsta->generatePhpMyAdminLink($envId);
echo $link->url, ' (expires ', $link->expiresAt, ')';

$plugins = $kinsta->getWordPressPlugins($envId);
$themes = $kinsta->getWordPressThemes($envId);

if (! $kinsta->checkWpAdminUserExists($envId, 'admin@example.com')->exists) {
    $kinsta->createWpAdminUser($envId, new WpAdminUserData(
        email: 'admin@example.com',
        firstName: 'Jane',
        lastName: 'Doe',
    ));
}

$login = $kinsta->getWpAdminLoginLink($envId, 'admin@example.com');
echo $login->loginUrl;
```

### Themes & plugins

[Kinsta docs: WordPress Site Themes & Plugins](https://api-docs.kinsta.com/api-reference/wordpress-site-themes-&-plugins/update-wp-plugin)

| Method | HTTP | Returns |
|---|---|---|
| `updatePlugin(string $envId, PluginUpdateData $data)` | `PUT /sites/environments/{env_id}/plugins` | `OperationResponse` |
| `bulkUpdatePlugins(string $envId, BulkPluginUpdateData $data)` | `PUT /sites/environments/{env_id}/plugins/bulk-update` | `OperationResponse` |
| `updateTheme(string $envId, ThemeUpdateData $data)` | `PUT /sites/environments/{env_id}/themes` | `OperationResponse` |
| `bulkUpdateThemes(string $envId, BulkThemeUpdateData $data)` | `PUT /sites/environments/{env_id}/themes/bulk-update` | `OperationResponse` |
| `getEnvironmentPlugins(string $envId)` | `GET /sites/environments/{env_id}/plugins` ⚠️ deprecated by Kinsta | `EnvironmentPlugins` |
| `getEnvironmentThemes(string $envId)` | `GET /sites/environments/{env_id}/themes` ⚠️ deprecated by Kinsta | `EnvironmentThemes` |

For listing, use `getWordPressPlugins()` and `getWordPressThemes()` ([WordPress tools](#wordpress-tools)) instead of the deprecated methods.

```php
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkPluginUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkThemeUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\PluginUpdateData;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\ThemeUpdateData;

$kinsta->updatePlugin($envId, new PluginUpdateData(name: 'akismet', updateVersion: '5.3'));
$kinsta->bulkUpdatePlugins($envId, new BulkPluginUpdateData(plugins: ['akismet', 'jetpack']));

$kinsta->updateTheme($envId, new ThemeUpdateData(name: 'twentytwentyfour', updateVersion: '1.2'));
$kinsta->bulkUpdateThemes($envId, new BulkThemeUpdateData(themes: ['twentytwentyfour']));
```

### Edge caching & CDN

[Kinsta docs: Edge Caching](https://api-docs.kinsta.com/api-reference/wordpress-edge-caching/clear-site-edge-cache) and [CDN](https://api-docs.kinsta.com/api-reference/wordpress-cdn/update-cdn-image-optimization)

| Method | HTTP | Returns |
|---|---|---|
| `clearEdgeCache(string $envId, ?bool $clearSubdirectories = null, ?string $url = null)` | `POST /sites/edge-caching/clear` | `OperationResponse` |
| `setEdgeCacheStatus(string $envId, bool $enabled)` | `PUT /sites/edge-caching/status` | `OperationResponse` |
| `clearCdnCache(string $envId, string $cdnCacheId)` | `POST /sites/cdn/clear-cache` | `OperationResponse` |
| `setImageOptimization(string $envId, ImageOptimizationType $type)` | `PUT /sites/cdn/image-optimization` | `OperationResponse` |

```php
use Nickfairchild\KinstaPhpSdk\Enums\ImageOptimizationType;

$kinsta->setEdgeCacheStatus($envId, enabled: true);

// Clear everything, or one URL (optionally including subdirectories)
$kinsta->clearEdgeCache($envId);
$kinsta->clearEdgeCache($envId, clearSubdirectories: true, url: 'https://example.com/blog');

// The CDN cache ID comes from the environment
$environment = $kinsta->getSiteEnvironments($siteId)->environments[0];
$kinsta->clearCdnCache($environment->id, $environment->cdnEdgeCache);

$kinsta->setImageOptimization($envId, ImageOptimizationType::LOSSLESS);
```

### Bot protection

[Kinsta docs: WordPress Bot Protection](https://api-docs.kinsta.com/api-reference/wordpress-bot-protection/get-the-bot-protection-level-of-an-environment)

| Method | HTTP | Returns |
|---|---|---|
| `getBotProtectionLevel(string $envId)` | `GET /sites/environments/{env_id}/bot-protection/level` | `?BotProtectionLevel` |
| `setBotProtectionLevel(string $envId, ProtectionLevel $level)` | `PUT /sites/environments/{env_id}/bot-protection/level` | `OperationResponse` |
| `getAiCrawlersBlocking(string $envId)` | `GET /sites/environments/{env_id}/bot-protection/ai-crawlers` | `?AiCrawlersStatus` |
| `setAiCrawlersBlocking(string $envId, string $status)` | `PUT /sites/environments/{env_id}/bot-protection/ai-crawlers` | `OperationResponse` |
| `getWpAutomationsStatus(string $envId)` | `GET /sites/environments/{env_id}/bot-protection/wp-automations` | `?WpAutomationsStatus` |
| `setWpAutomationsStatus(string $envId, string $status)` | `PUT /sites/environments/{env_id}/bot-protection/wp-automations` | `OperationResponse` |
| `getBotProtectionExceptionRules(string $envId)` | `GET /sites/environments/{env_id}/bot-protection/exception-rules` | `?ExceptionRules` |
| `addBotProtectionExceptionRule(string $envId, ExceptionRuleData $data)` | `POST /sites/environments/{env_id}/bot-protection/exception-rules` | `OperationResponse` |
| `removeBotProtectionExceptionRule(string $envId, string $exceptionRuleId)` | `DELETE /sites/environments/{env_id}/bot-protection/exception-rules` | `OperationResponse` |

The `get*` methods return `null` when bot protection isn't available for the environment.

```php
use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\ExceptionRuleData;
use Nickfairchild\KinstaPhpSdk\Enums\ProtectionLevel;

$kinsta->setBotProtectionLevel($envId, ProtectionLevel::CHALLENGE_BOTS);
$level = $kinsta->getBotProtectionLevel($envId)?->level; // ?ProtectionLevel

// 'enabled' or 'disabled'
$kinsta->setAiCrawlersBlocking($envId, 'enabled');
$kinsta->setWpAutomationsStatus($envId, 'enabled');

// type: ip | path | user_agent | country; action: skip | challenge | block
$kinsta->addBotProtectionExceptionRule($envId, new ExceptionRuleData(
    type: 'ip',
    action: 'skip',
    value: '203.0.113.10',
));

foreach ($kinsta->getBotProtectionExceptionRules($envId)?->exceptionRules ?? [] as $rule) {
    echo "{$rule->type} {$rule->value} -> {$rule->action}\n";
}

$kinsta->removeBotProtectionExceptionRule($envId, $ruleId);
```

### Backups

[Kinsta docs: Backups](https://api-docs.kinsta.com/api-reference/backups/get-manual-schedule-and-system-generated-backups)

| Method | HTTP | Returns |
|---|---|---|
| `backups(string $envId)` | `GET /sites/environments/{env_id}/backups` | `BackupEnvironment` |
| `addManualBackup(string $envId, AddManualBackupRequestData $data)` | `POST /sites/environments/{env_id}/manual-backups` | `OperationResponse` |
| `restoreBackup(string $targetEnvId, RestoreBackupRequestData $data)` | `POST /sites/environments/{target_env_id}/backups/restore` | `OperationResponse` |
| `removeBackup(int $backupId)` | `DELETE /sites/environments/backups/{backup_id}` | `OperationResponse` |
| `downloadableBackups(string $envId)` | `GET /sites/environments/{env_id}/downloadable-backups` | `DownloadableBackupEnvironment` |
| `addDownloadableBackup(string $envId)` | `POST /sites/environments/{env_id}/downloadable-backups` | `OperationResponse` |
| `nextDownloadableBackupAvailable(string $envId)` | `GET /sites/environments/{env_id}/next-downloadable-backup-available` | `NextDownloadableBackup` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\AddManualBackupRequestData;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Backups\RestoreBackupRequestData;

$kinsta->addManualBackup($envId, new AddManualBackupRequestData(tag: 'before-deploy'));

$backups = $kinsta->backups($envId);

$kinsta->restoreBackup($targetEnvId, new RestoreBackupRequestData(
    backupId: 123456,
    notifiedUserId: $userId,
));

$kinsta->removeBackup(123456);

// Check whether a new downloadable backup can be created yet
$next = $kinsta->nextDownloadableBackupAvailable($envId);
if ($next->isBackupAvailable) {
    $kinsta->addDownloadableBackup($envId);
}
```

### SFTP / SSH

[Kinsta docs: WordPress Site Environments](https://api-docs.kinsta.com/api-reference/wordpress-site-environments/get-sftpssh-status)

| Method | HTTP | Returns |
|---|---|---|
| `getSshStatus(string $envId)` | `GET /sites/environments/{env_id}/ssh/get-status` | `SftpStatus` |
| `toggleSftpAccess(string $envId, bool $enabled)` | `POST /sites/environments/{env_id}/ssh/set-status` | `bool` |
| `toggleSftpPasswordAccess(string $envId, bool $enabled)` | `POST /sites/environments/{env_id}/ssh/set-password-status` | `bool` |
| `getSftpConfig(string $siteId, string $envId)` | `GET /sites/{site_id}/environments/{env_id}/ssh/config` | `SftpConfig` |
| `getSftpPassword(string $envId)` | `GET /sites/environments/{env_id}/ssh/password` | `SftpPassword` |
| `generateSftpPassword(string $envId)` | `POST /sites/environments/{env_id}/ssh/generate-password` | `bool` |
| `changeSftpPasswordExpiration(string $envId, string $interval)` | `POST /sites/environments/{env_id}/ssh/change-expiration-interval` | `bool` |
| `getSftpIpAllowlist(string $envId)` | `GET /sites/environments/{env_id}/ssh/get-allowed-ips` | `string[]` |
| `setSftpIpAllowlist(string $envId, array $ips)` | `POST /sites/environments/{env_id}/ssh/set-allowed-ips` | `bool` |

```php
$kinsta->toggleSftpAccess($envId, enabled: true);
$isEnabled = $kinsta->getSshStatus($envId)->isSshEnabled;

$config = $kinsta->getSftpConfig($siteId, $envId);
echo "{$config->username}@{$config->host}:{$config->port}\n{$config->sshCommand}";

$kinsta->generateSftpPassword($envId);
$password = $kinsta->getSftpPassword($envId)->password;

// hours_24 | days_7 | days_30 | days_90 | never
$kinsta->changeSftpPasswordExpiration($envId, 'days_30');

$kinsta->setSftpIpAllowlist($envId, ['203.0.113.10', '198.51.100.0/24']);
$ips = $kinsta->getSftpIpAllowlist($envId);
```

### Additional SFTP users

[Kinsta docs: Additional SFTP Users](https://api-docs.kinsta.com/api-reference/additional-sftp-users/get-list-of-additional-sftp-accounts)

| Method | HTTP | Returns |
|---|---|---|
| `getAdditionalSftpAccounts(string $envId)` | `GET /sites/environments/{env_id}/additional-sftp-accounts` | `SftpAccounts` |
| `addAdditionalSftpAccount(string $envId, SftpAccountData $data)` | `POST /sites/environments/{env_id}/additional-sftp-accounts` | `OperationResponse` |
| `toggleAdditionalSftpAccounts(string $envId, bool $enabled)` | `PUT /sites/environments/{env_id}/additional-sftp-accounts/toggle-status` | `OperationResponse` |
| `removeAdditionalSftpAccount(string $accountId)` | `DELETE /sites/environments/additional-sftp-accounts/{account_id}` | `OperationResponse` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Sftp\SftpAccountData;

$kinsta->toggleAdditionalSftpAccounts($envId, enabled: true);

$kinsta->addAdditionalSftpAccount($envId, new SftpAccountData(
    username: 'contractor',
    password: 'a-strong-password',
    rootDirectory: '/public/wp-content',
    permission: 'read',          // 'read' or 'write'
));

foreach ($kinsta->getAdditionalSftpAccounts($envId)->accounts as $account) {
    echo "{$account->username} ({$account->permission})\n";
}

$kinsta->removeAdditionalSftpAccount($accountId);
```

### Logs & redirect rules

[Kinsta docs: Logs](https://api-docs.kinsta.com/api-reference/logs/get-site-logs)

| Method | HTTP | Returns |
|---|---|---|
| `getLogs(string $envId, LogsQuery $query)` | `GET /sites/environments/{env_id}/logs` | `LogsResult` |
| `getRedirectRules(string $envId, ?RedirectRulesQuery $query = null)` | `GET /sites/environments/{env_id}/redirect-rules` | `RedirectRules` |
| `updateRedirectRules(string $envId, RedirectRulesUpdateData $data)` | `POST /sites/environments/{env_id}/redirect-rules` | `bool` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Logs\LogsQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesQuery;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesUpdateData;

// fileName: error | access | kinsta-cache-perf; lines: up to 20000
$logs = $kinsta->getLogs($envId, new LogsQuery(
    fileName: 'access',
    lines: 500,
    search: 'wp-login.php',
));
echo $logs->logs;

$rules = $kinsta->getRedirectRules($envId, new RedirectRulesQuery(
    limit: 20,
    key: 'from',               // domain | from | to | type
    order: 'ascend',           // ascend | descend
    searchQuery: '/old-',
    regexSearch: false,
));

// actionType: NEW | UPDATE | DELETE | DELETE_ALL; type: permanent | redirect
$kinsta->updateRedirectRules($envId, new RedirectRulesUpdateData(
    actionType: 'NEW',
    newValue: [
        'domain' => 'example.com',
        'from' => '/old-page',
        'to' => '/new-page',
        'type' => 'permanent',
    ],
));
```

### Analytics

[Kinsta docs: Analytics](https://api-docs.kinsta.com/api-reference/analytics/get-visits)

**Monthly usage (per site):**

| Method | HTTP | Returns |
|---|---|---|
| `getVisitsUsage(string $siteId)` | `GET /sites/{site_id}/usage/visits/this-month` | `UsageResult` |
| `getBandwidthUsage(string $siteId)` | `GET /sites/{site_id}/usage/bandwidth/this-month` | `UsageResult` |
| `getCdnBandwidthUsage(string $siteId)` | `GET /sites/{site_id}/usage/cdn-bandwidth/this-month` | `UsageResult` |

**Environment analytics.** Every method takes `(string $envId, AnalyticsQuery $query = new AnalyticsQuery)`; all paths are under `GET /sites/environments/{env_id}/analytics/`.

| Method | Path | Returns |
|---|---|---|
| `getVisits()` | `visits` | `AnalyticsResult` |
| `getVisitsDispersion()` | `visits-dispersion` | `AnalyticsResult` |
| `getBandwidth()` | `bandwidth` | `AnalyticsResult` |
| `getCdnBandwidth()` | `cdn-bandwidth` | `AnalyticsResult` |
| `getDiskSpace()` | `diskspace` | `AnalyticsResult` |
| `getResponseCodeBreakdown()` | `response-codes` | `AnalyticsResult` |
| `getTopCountries()` | `top-countries` | `AnalyticsResult` |
| `getTopCities()` | `top-cities` | `AnalyticsResult` |
| `getTopClientIps()` | `top-client-ips` | `AnalyticsResult` |
| `getTopAsns()` | `top-asns` | `AnalyticsResult` |
| `getTopBrowsers()` | `top-browsers` | `AnalyticsResult` |
| `getTopHosts()` | `top-hosts` | `AnalyticsResult` |
| `getTopReferrers()` | `top-referrers` | `AnalyticsResult` |
| `getTopUserAgents()` | `top-uas` | `AnalyticsResult` |
| `getBotTrafficFlow()` | `bot-protection/traffic-flow` | `BotProtectionAnalyticsResult` |
| `getBotTrafficRequests()` | `bot-protection/requests` | `BotProtectionAnalyticsResult` |
| `getBotProtectionResults()` | `bot-protection/protection-results` | `BotProtectionAnalyticsResult` |
| `getBotTopBreakdown()` | `bot-protection/top-breakdown` | `BotProtectionAnalyticsResult` |

`AnalyticsQuery` fields (all optional):

| Field | Query param | Values |
|---|---|---|
| `timeSpan` | `time_span` | `24_hours`, `7_days`, `30_days`, `60_days` (disk space doesn't accept `24_hours`) |
| `from` | `from` | Start of a custom range |
| `to` | `to` | End of a custom range |
| `timeZone` | `time_zone` | Disk space only, e.g. `+02:00`; defaults to `00:00` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Analytics\AnalyticsQuery;

// Defaults (Kinsta uses its own default time span)
$visits = $kinsta->getVisits($envId);

$visits = $kinsta->getVisits($envId, new AnalyticsQuery(timeSpan: '30_days'));

foreach ($visits->data as $series) {
    echo "{$series['name']}: {$series['total']}\n";

    foreach ($series['dataset'] as $point) {
        echo "  {$point['key']} => {$point['value']}\n";
    }
}

$usage = $kinsta->getVisitsUsage($siteId);
echo "Visits this month: {$usage->value}";

$flow = $kinsta->getBotTrafficFlow($envId, new AnalyticsQuery(timeSpan: '7_days'));
$flow->data; // raw array as returned by Kinsta
```

`AnalyticsResult` has `key` (string) and `data` (an array of series, each with `name`, `total` and `dataset`). `BotProtectionAnalyticsResult::$data` holds the endpoint's payload as an array.

### Company

[Kinsta docs: Company Users](https://api-docs.kinsta.com/api-reference/company-users/get-list-of-company-users), [API Keys](https://api-docs.kinsta.com/api-reference/api-keys/get-list-of-company-api-keys), [Available Regions](https://api-docs.kinsta.com/api-reference/available-regions/get-list-of-available-regions) and [Activity Logs](https://api-docs.kinsta.com/api-reference/activity-logs/get-list-of-company-activity-logs)

| Method | HTTP | Returns |
|---|---|---|
| `companyUsers()` | `GET /company/{company_id}/users` | `CompanyUser[]` |
| `companyUser(string $userId)` | `GET /company/{company_id}/users/{user_id}` | `CompanyUser` |
| `setCompanyCapability(string $userId, string $role)` | `POST /company/{company_id}/users/company-capabilities` | `OperationResponse` |
| `setSiteCapabilities(array $userIds, array $siteCapabilities)` | `POST /company/{company_id}/users/site-capabilities` | `OperationResponse` |
| `regions()` | `GET /company/{company_id}/available-regions` | `Region[]` |
| `apiKeys()` | `GET /company/{company_id}/api-keys` | `APIKey[]` |
| `activityLogs(...)` | `GET /company/{company_id}/activity-logs` | `ActivityLogs` |
| `companyPlugins(PluginThemePayload $payload)` | `GET /company/{company_id}/wp-plugins` | `array{total, last_updated_at, items: Plugin[]}` |
| `companyThemes(PluginThemePayload $payload)` | `GET /company/{company_id}/wp-themes` | `array{total, last_updated_at, items: Theme[]}` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Company\PluginThemePayload;
use Nickfairchild\KinstaPhpSdk\Dto\Company\SiteCapabilityData;
use Nickfairchild\KinstaPhpSdk\Enums\Category;
use Nickfairchild\KinstaPhpSdk\Enums\Language;

foreach ($kinsta->companyUsers() as $user) {
    echo "{$user->fullName} <{$user->email}>\n";

    foreach ($user->capabilities as $capability) {
        echo "  {$capability->role}\n";
    }
}

$user = $kinsta->companyUser($userId);

// company-admin | company-developer | company-billing
$kinsta->setCompanyCapability($userId, 'company-developer');

// site-admin | site-developer
$kinsta->setSiteCapabilities([$userId], [
    new SiteCapabilityData(siteId: $siteId, role: 'site-developer'),
]);

$regions = $kinsta->regions();
$keys = $kinsta->apiKeys();

// All filters are optional
$logs = $kinsta->activityLogs(
    limit: 20,
    offset: 0,
    category: Category::SITE_ACTIONS,
    siteId: $siteId,
    language: Language::ENGLISH,
);

echo "{$logs->total} entries\n";
foreach ($logs->items as $entry) {
    echo "{$entry->type} at {$entry->createdAt}\n";
}

// Plugins and themes installed across the company
$plugins = $kinsta->companyPlugins(new PluginThemePayload(limit: 50, search: 'woo'));
$themes = $kinsta->companyThemes(new PluginThemePayload());
```

`activityLogs()` also accepts `idInitiatedBy` and `idApiKey` filters.

### Domains & DNS

[Kinsta docs: Domains](https://api-docs.kinsta.com/api-reference/domains/get-list-of-domains-by-company-id)

| Method | HTTP | Returns |
|---|---|---|
| `domains()` | `GET /domains` | `Domain[]` |
| `dnsRecordsForDomain(string $domainId)` | `GET /domains/{domain_id}/dns-records` | `DNSRecord[]` |
| `createDnsRecord(string $domainId, DNSRecord $dnsRecord)` | `POST /domains/{domain_id}/dns-records` | `OperationResponse` |
| `updateDnsRecord(string $domainId, DNSRecordUpdate $dnsRecord)` | `PUT /domains/{domain_id}/dns-records` | `OperationResponse` |
| `deleteDnsRecord(string $domainId, DNSRecordDelete $dnsRecord)` | `DELETE /domains/{domain_id}/dns-records` | `OperationResponse` |

```php
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecord;
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordDelete;
use Nickfairchild\KinstaPhpSdk\Dto\Domains\DNSRecordUpdate;

$domains = $kinsta->domains();
$records = $kinsta->dnsRecordsForDomain($domainId);

$kinsta->createDnsRecord($domainId, new DNSRecord(
    type: 'A',
    name: 'www',
    ttl: 3600,
    resourceRecords: [['value' => '203.0.113.10']],
));

$kinsta->updateDnsRecord($domainId, new DNSRecordUpdate(
    type: 'A',
    name: 'www',
    ttl: 3600,
    newResourceRecords: [['value' => '203.0.113.20']],
    removedResourceRecords: [['value' => '203.0.113.10']],
));

$kinsta->deleteDnsRecord($domainId, new DNSRecordDelete(type: 'A', name: 'www'));
```

### Additional tools

[Kinsta docs: WordPress Site Tools – denied IPs](https://api-docs.kinsta.com/api-reference/wordpress-site-tools/get-list-of-denied-ips)

| Method | HTTP | Returns |
|---|---|---|
| `getDeniedIps(string $envId)` | `GET /sites/tools/denied-ips` | `DeniedIpsResult` |
| `updateDeniedIps(string $envId, array $ips)` | `PUT /sites/tools/denied-ips` | `bool` |

```php
$denied = $kinsta->getDeniedIps($envId);

// Replaces the full list
$kinsta->updateDeniedIps($envId, ['203.0.113.66', '198.51.100.7']);
```

### Operations & utilities

[Kinsta docs: Operations](https://api-docs.kinsta.com/api-reference/operations/get-the-status-information-of-an-operation) and [API Key validation](https://api-docs.kinsta.com/api-reference/authentication/validate-api-key)

| Method | HTTP | Returns |
|---|---|---|
| `getOperation(string $operationId)` | `GET /operations/{operation_id}` | `Operation` (`message`, `status`, `data`) |
| `validateApiKey()` | `GET /validate` | `array` (raw response) |

```php
$check = $kinsta->validateApiKey();

$operation = $kinsta->getOperation($operationId);
```

---

## Enums

All in the `Nickfairchild\KinstaPhpSdk\Enums` namespace.

| Enum | Used by | Cases → value |
|---|---|---|
| `ProtectionLevel` | `setBotProtectionLevel()`, `BotProtectionLevel::$level` | `BLOCK_MALICIOUS_TRAFFIC` → `block-malicious-traffic`, `BLOCK_AUTOMATIONS` → `block-automations`, `CHALLENGE_BOTS` → `challenge-bots`, `CHALLENGE_EVERYONE` → `challenge-everyone` |
| `ForceHttpsType` | `setForceHttpsStatus()`, `ForceHttpsStatus::$status` | `ENABLED_FOR_ALL`, `ENABLED_REDIRECT_TO_PRIMARY`, `DISABLED` |
| `ImageOptimizationType` | `setImageOptimization()` | `OFF` → `off`, `LOSSY` → `lossy`, `LOSSLESS` → `lossless` |
| `Category` | `activityLogs()` | `SITE_ACTIONS`, `KINSTA_DNS`, `MIGRATIONS`, `BILLING`, `NOTIFICATIONS`, `USER_MANAGEMENT`, `PERSONAL_SETTINGS`, `SAML_SSO` |
| `Language` | `activityLogs()` | `DANISH` → `da`, `GERMAN` → `de`, `ENGLISH` → `en`, `SPANISH` → `es`, `FRENCH` → `fr`, `ITALIAN` → `it`, `JAPANESE` → `ja`, `DUTCH` → `nl`, `PORTUGUESE` → `pt`, `SWEDISH` → `sv` |

Other fixed-value parameters are plain strings. The documented values are shown in the examples above.

## Error handling

The client throws on any 4xx or 5xx response. Every API error is raised as a `Nickfairchild\KinstaPhpSdk\Exceptions\KinstaException`, with the Saloon response attached:

```php
use Nickfairchild\KinstaPhpSdk\Exceptions\KinstaException;

try {
    $kinsta->site('not-a-real-site-id');
} catch (KinstaException $exception) {
    $exception->response->status();   // e.g. 404
    $exception->response->json();     // Kinsta's error body
    $exception->getMessage();
}
```

## Testing your code

The client is a Saloon connector, so you can fake responses with Saloon's [`MockClient`](https://docs.saloon.dev/the-basics/testing) without touching the real API:

```php
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\Sites\GetSiteRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

$mockClient = new MockClient([
    GetSiteRequest::class => MockResponse::make([
        'site' => [
            'id' => 'site-1',
            'name' => 'my-site',
            'display_name' => 'My Site',
            'status' => 'live',
            'environments' => [],
        ],
    ]),
]);

$kinsta = (new Kinsta('fake-key', 'fake-company'))->withMockClient($mockClient);

$site = $kinsta->site('site-1');

$mockClient->assertSent(GetSiteRequest::class);
```

To inspect raw requests and responses while debugging, use Saloon's `debug()`:

```php
$kinsta->debug()->site($siteId);
```

## Known limitations

These behaviours follow the Kinsta docs but haven't been confirmed against the live API:

- **`wp-plugins` / `wp-themes` filters**: the docs define optional `status` and `column` filters as a JSON body on a GET request. They aren't implemented, because it's unclear whether the API reads a GET body.
- **Disk-space `time_zone`**: required by Kinsta but with no documented default; the SDK sends `00:00` unless you set `timeZone`.
- **`install_mode` on `createSite()`**: marked both required and deprecated by Kinsta, with conflicting defaults. The SDK sends `new`.
- **Image optimisation `off`**: sent as the string `"off"`. The spec writes it as an unquoted YAML `off`, which some YAML parsers read as `false`.
- **`getEnvironment()`**: `GET /sites/environments/{env_id}` isn't in the Kinsta docs. It's kept for compatibility but may stop working; use `getSiteEnvironments()` instead.
- **Deprecated endpoints**: `getEnvironmentPlugins()` and `getEnvironmentThemes()` call endpoints Kinsta has deprecated. Use `getWordPressPlugins()` / `getWordPressThemes()`.
- **No input validation**: the SDK passes values through as given. Kinsta's limits (12-character environment names, allowed `time_span` values and so on) are enforced by the API.

## Development

```bash
composer install

composer test            # Pest
composer analyse         # PHPStan (use --memory-limit=1G if it runs out of memory)
composer format          # Laravel Pint
composer type-coverage
```

`phpunit.xml.dist` is committed; create a local `phpunit.xml` (gitignored) to override it.

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md).
