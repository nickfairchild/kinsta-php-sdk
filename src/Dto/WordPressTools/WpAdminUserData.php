<?php

namespace Nickfairchild\KinstaPhpSdk\Dto\WordPressTools;

readonly class WpAdminUserData
{
    public function __construct(
        public string $email,
        public string $password,
        public string $firstName,
        public string $lastName,
    ) {}
}
