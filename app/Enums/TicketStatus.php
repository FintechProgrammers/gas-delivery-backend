<?php

namespace App\Enums;

use Rexlabs\Enum\Enum;

/**
 * The UserStatus enum.
 *
 * @method static self OPEN()
 * @method static self CLOSED()
 * @method static self PENDING()
 */
class TicketStatus extends Enum
{
    const  OPEN = 'open';
    const CLOSED = 'closed';
    const PENDING = 'pending';
}
