<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkPluginUpdateData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class BulkUpdatePluginsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected BulkPluginUpdateData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/plugins/bulk-update";
    }

    protected function defaultBody(): array
    {
        return [
            'plugin_slugs' => $this->data->pluginSlugs,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
