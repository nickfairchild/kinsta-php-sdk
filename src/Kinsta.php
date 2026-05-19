<?php

namespace Nickfairchild\KinstaPhpSdk;

use Nickfairchild\KinstaPhpSdk\Concerns\SupportsAdditionalToolsEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsAnalyticsEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsBackupEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsCachingEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsCompanyEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsDomainEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsEnvironmentPluginsThemesEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsLogsEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsSftpEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsSiteDomainsEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsSiteEnvironmentsEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsSiteToolsEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsUtilitiesEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsWordPressSitesEndpoints;
use Nickfairchild\KinstaPhpSdk\Concerns\SupportsWordPressToolsEndpoints;
use Nickfairchild\KinstaPhpSdk\Exceptions\KinstaException;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\OffsetPaginator;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

class Kinsta extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use SupportsAdditionalToolsEndpoints;
    use SupportsAnalyticsEndpoints;
    use SupportsBackupEndpoints;
    use SupportsCachingEndpoints;
    use SupportsCompanyEndpoints;
    use SupportsDomainEndpoints;
    use SupportsEnvironmentPluginsThemesEndpoints;
    use SupportsLogsEndpoints;
    use SupportsSftpEndpoints;
    use SupportsSiteDomainsEndpoints;
    use SupportsSiteEnvironmentsEndpoints;
    use SupportsSiteToolsEndpoints;
    use SupportsUtilitiesEndpoints;
    use SupportsWordPressSitesEndpoints;
    use SupportsWordPressToolsEndpoints;

    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $companyId,
    ) {}

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        return new KinstaException(
            $response,
            $senderException?->getMessage() ?? 'Request failed.',
            $senderException?->getCode() ?? 0,
        );
    }

    public function resolveBaseUrl(): string
    {
        return 'https://api.kinsta.com/v2';
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->apiKey);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends OffsetPaginator
        {
            protected ?int $perPageLimit = 20;

            protected function isLastPage(Response $response): bool
            {
                return $this->getOffset() >= $response->json('total');
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $request->createDtoFromResponse($response);
            }
        };
    }
}
