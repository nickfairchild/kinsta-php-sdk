<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\AiCrawlersStatus;
use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\BotProtectionLevel;
use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\ExceptionRuleData;
use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\ExceptionRules;
use Nickfairchild\KinstaPhpSdk\Dto\BotProtection\WpAutomationsStatus;
use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Enums\ProtectionLevel;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\AddExceptionRuleRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetAiCrawlersBlockingRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetBotProtectionLevelRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetExceptionRulesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\GetWpAutomationsStatusRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\RemoveExceptionRuleRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\SetAiCrawlersBlockingRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\SetBotProtectionLevelRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\BotProtection\SetWpAutomationsStatusRequest;

/** @mixin Kinsta */
trait SupportsBotProtectionEndpoints
{
    public function getBotProtectionLevel(string $envId): ?BotProtectionLevel
    {
        $request = new GetBotProtectionLevelRequest($envId);

        return $this->send($request)->dto();
    }

    public function setBotProtectionLevel(string $envId, ProtectionLevel $level): OperationResponse
    {
        $request = new SetBotProtectionLevelRequest($envId, $level);

        return $this->send($request)->dto();
    }

    public function getAiCrawlersBlocking(string $envId): ?AiCrawlersStatus
    {
        $request = new GetAiCrawlersBlockingRequest($envId);

        return $this->send($request)->dto();
    }

    public function setAiCrawlersBlocking(string $envId, string $status): OperationResponse
    {
        $request = new SetAiCrawlersBlockingRequest($envId, $status);

        return $this->send($request)->dto();
    }

    public function getWpAutomationsStatus(string $envId): ?WpAutomationsStatus
    {
        $request = new GetWpAutomationsStatusRequest($envId);

        return $this->send($request)->dto();
    }

    public function setWpAutomationsStatus(string $envId, string $status): OperationResponse
    {
        $request = new SetWpAutomationsStatusRequest($envId, $status);

        return $this->send($request)->dto();
    }

    public function getBotProtectionExceptionRules(string $envId): ?ExceptionRules
    {
        $request = new GetExceptionRulesRequest($envId);

        return $this->send($request)->dto();
    }

    public function addBotProtectionExceptionRule(string $envId, ExceptionRuleData $data): OperationResponse
    {
        $request = new AddExceptionRuleRequest($envId, $data);

        return $this->send($request)->dto();
    }

    public function removeBotProtectionExceptionRule(string $envId, string $exceptionRuleId): OperationResponse
    {
        $request = new RemoveExceptionRuleRequest($envId, $exceptionRuleId);

        return $this->send($request)->dto();
    }
}
