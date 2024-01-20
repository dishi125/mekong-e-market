<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Type extends Enum
{
    const Farmer =   1;
    const Retailer =   2;
    const Wholesaler = 3;
    const Buyer = 4;
}
