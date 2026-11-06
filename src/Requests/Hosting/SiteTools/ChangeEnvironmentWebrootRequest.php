<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\SiteTools;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class ChangeEnvironmentWebrootRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $envId,
        protected string $subfolder,
        protected ?bool $clearAllCache = null,
        protected ?bool $refreshPluginsAndThemes = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/change-webroot-subfolder";
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'web_root_subfolder' => $this->subfolder,
            'clear_all_cache' => $this->clearAllCache,
            'refresh_plugins_and_themes' => $this->refreshPluginsAndThemes,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
