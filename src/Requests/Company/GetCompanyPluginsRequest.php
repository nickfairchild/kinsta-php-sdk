<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\Plugin;
use Nickfairchild\KinstaPhpSdk\Dto\Company\PluginThemePayload;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\PendingRequest;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class GetCompanyPluginsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::GET;

    public function __construct(
        protected string $companyId,
        protected PluginThemePayload $payload
    ) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/wp-plugins';
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        $data = $response->json('company.plugins');

        return [
            'total' => $data['total'],
            'last_updated_at' => $data['last_updated_at'],
            'items' => Plugin::collection($data['items']),
        ];
    }

    public function boot(PendingRequest $pendingRequest): void
    {
        $pendingRequest->body()->set([
            'offset' => $this->payload->offset,
            'limit' => $this->payload->limit,
            'search' => $this->payload->search,
            'status' => $this->payload->status,
            'column' => $this->payload->column,
            'order_by' => $this->payload->orderBy,
        ]);
    }
}
