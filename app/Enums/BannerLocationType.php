<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class BannerLocationType extends Enum
{
    //based on banner location id(banner-package)not in used
    const HomeLocationTop =   1;
    const HomeLocationMiddle = 2;
    const BannerLocationTop =   3;
    const BannerLocationMiddle = 4;
}
