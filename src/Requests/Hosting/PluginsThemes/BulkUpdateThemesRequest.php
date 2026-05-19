<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\PluginsThemes;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\PluginsThemes\BulkThemeUpdateData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class BulkUpdateThemesRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected string $envId,
        protected BulkThemeUpdateData $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/themes/bulk-update";
    }

    protected function defaultBody(): array
    {
        return [
            'theme_slugs' => $this->data->themeSlugs,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return OperationResponse::fromResponse($response);
    }
}
