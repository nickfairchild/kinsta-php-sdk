<?php

namespace Nickfairchild\KinstaPhpSdk\Concerns;

use Nickfairchild\KinstaPhpSdk\Dto\OperationResponse;
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\PhpMyAdminLink;
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WordPressPlugins;
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WordPressThemes;
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserData;
use Nickfairchild\KinstaPhpSdk\Dto\WordPressTools\WpAdminUserResult;
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\CheckWpAdminUserExistsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\CreateWpAdminUserRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GeneratePhpMyAdminLinkRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWordPressPluginsRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWordPressThemesRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\GetWpAdminLoginLinkRequest;
use Nickfairchild\KinstaPhpSdk\Requests\Hosting\WordPressTools\RunWpCliCommandRequest;

/** @mixin Kinsta */
trait SupportsWordPressToolsEndpoints
{
    public function runWpCliCommand(string $envId, string $command): OperationResponse
    {
        $request = new RunWpCliCommandRequest($envId, $command);

        return $this->send($request)->dto();
    }

    public function generatePhpMyAdminLink(string $envId): PhpMyAdminLink
    {
        $request = new GeneratePhpMyAdminLinkRequest($envId);

        return $this->send($request)->dto();
    }

    public function getWordPressPlugins(string $envId): WordPressPlugins
    {
        $request = new GetWordPressPluginsRequest($envId);

        return $this->send($request)->dto();
    }

    public function getWordPressThemes(string $envId): WordPressThemes
    {
        $request = new GetWordPressThemesRequest($envId);

        return $this->send($request)->dto();
    }

    public function getWpAdminLoginLink(string $envId, string $email): WpAdminUserResult
    {
        $request = new GetWpAdminLoginLinkRequest($envId, $email);

        return $this->send($request)->dto();
    }

    public function checkWpAdminUserExists(string $envId, string $email): WpAdminUserResult
    {
        $request = new CheckWpAdminUserExistsRequest($envId, $email);

        return $this->send($request)->dto();
    }

    public function createWpAdminUser(string $envId, WpAdminUserData $data): WpAdminUserResult
    {
        $request = new CreateWpAdminUserRequest($envId, $data);

        return $this->send($request)->dto();
    }
}
