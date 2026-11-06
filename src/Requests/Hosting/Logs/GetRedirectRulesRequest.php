<?php

namespace Nickfairchild\KinstaPhpSdk\Requests\Hosting\Logs;

use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRules;
use Nickfairchild\KinstaPhpSdk\Dto\Logs\RedirectRulesQuery;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetRedirectRulesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $envId,
        protected ?RedirectRulesQuery $redirectRulesQuery = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/sites/environments/{$this->envId}/redirect-rules";
    }

    protected function defaultQuery(): array
    {
        if ($this->redirectRulesQuery === null) {
            return [];
        }

        return array_filter([
            'limit' => $this->redirectRulesQuery->limit,
            'offset' => $this->redirectRulesQuery->offset,
            'key' => $this->redirectRulesQuery->key,
            'order' => $this->redirectRulesQuery->order,
            'search_query' => $this->redirectRulesQuery->searchQuery,
            'regex_search' => $this->redirectRulesQuery->regexSearch === null ? null : ($this->redirectRulesQuery->regexSearch ? 'true' : 'false'),
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): mixed
    {
        return RedirectRules::fromResponse($response->json());
    }
}
