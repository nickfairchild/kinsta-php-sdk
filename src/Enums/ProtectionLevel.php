<?php

namespace Nickfairchild\KinstaPhpSdk\Enums;

enum ProtectionLevel: string
{
    case BLOCK_MALICIOUS_TRAFFIC = 'block-malicious-traffic';
    case BLOCK_AUTOMATIONS = 'block-automations';
    case CHALLENGE_BOTS = 'challenge-bots';
    case CHALLENGE_EVERYONE = 'challenge-everyone';
}
