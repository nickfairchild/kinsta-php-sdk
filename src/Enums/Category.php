<?php

namespace Nickfairchild\KinstaPhpSdk\Enums;

enum Category: string
{
    case SITE_ACTIONS = 'siteActions';
    case KINSTA_DNS = 'kinstaDns';
    case MIGRATIONS = 'migrations';
    case BILLING = 'billing';
    case NOTIFICATIONS = 'notifications';
    case USER_MANAGEMENT = 'userManagement';
    case PERSONAL_SETTINGS = 'personalSettings';
    case SAML_SSO = 'samlSso';
}
