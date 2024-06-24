<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The UserStatus enum.
 *
 * @method static self ACTIVE()
 * @method static self BLOCKED()
 * @method static self SUSPENDED()
 * @method static self IN_ACTIVE()
 */
class UserStatus extends Enum
{
    const ACTIVE = 'active';
    const BLOCKED = 'blocked';
    const SUSPENDED = 'suspended';
    const IN_ACTIVE = 'in_active';
}
