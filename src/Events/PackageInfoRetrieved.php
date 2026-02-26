<?php

namespace Codingwithrk\PackageInfo\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Codingwithrk\PackageInfo\PackageInfoData;

/**
 * Dispatched after package information has been successfully retrieved
 * from the native platform.
 *
 * @example
 * use Native\Mobile\Attributes\OnNative;
 * use Codingwithrk\PackageInfo\Events\PackageInfoRetrieved;
 *
 * #[OnNative(PackageInfoRetrieved::class)]
 * public function handlePackageInfoRetrieved(PackageInfoData $info): void
 * {
 *     // $info->appName, $info->version, $info->buildNumber …
 * }
 */
class PackageInfoRetrieved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly PackageInfoData $info,
    ) {}
}
