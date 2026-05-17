<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Company;

use Nickfairchild\KinstaPhpSdk\Dto\Company\ActivityLogs;
use Nickfairchild\KinstaPhpSdk\Enums\Category;
use Nickfairchild\KinstaPhpSdk\Enums\Language;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetActivityLogsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $companyId,
        protected readonly ?int $limit = null,
        protected readonly ?int $offset = null,
        protected readonly ?Category $category = null,
        protected readonly ?string $siteId = null,
        protected readonly ?string $idInitiatedBy = null,
        protected readonly ?string $idApiKey = null,
        protected readonly ?Language $language = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/company/'.$this->companyId.'/activity-logs';
    }

    protected function defaultQuery(): array
    {
        return [
            'limit' => $this->limit,
            'offset' => $this->offset,
            'category' => $this->category?->value,
            'site_id' => $this->siteId,
            'id_initiated_by' => $this->idInitiatedBy,
            'id_api_key' => $this->idApiKey,
            'language' => $this->language?->value,
        ];
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return ActivityLogs::collect($response->json('company'));
    }
}
