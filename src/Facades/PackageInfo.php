<?php

namespace Codingwithrk\PackageInfo\Facades;

use Illuminate\Support\Facades\Facade;
use Codingwithrk\PackageInfo\PackageInfoData;

/**
 * @method static PackageInfoData|null getInfo()
 *
 * @see \Codingwithrk\PackageInfo\PackageInfo
 */
class PackageInfo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Codingwithrk\PackageInfo\PackageInfo::class;
    }
}
