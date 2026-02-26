<?php

namespace Codingwithrk\PackageInfo;

use Codingwithrk\PackageInfo\Events\PackageInfoRetrieved;

class PackageInfo
{
    /**
     * Retrieve application package information from the native platform.
     *
     * Returns a typed value object containing the application name, package
     * identifier, marketing version, build number, and installer store.
     * Returns null when called outside of a NativePHP Mobile environment.
     *
     * @example
     * $info = PackageInfo::getInfo();
     * echo $info->appName;     // "My App"
     * echo $info->version;     // "1.2.3"
     * echo $info->buildNumber; // "42"
     */
    public function getInfo(): ?PackageInfoData
    {
        if (! function_exists('nativephp_call')) {
            return null;
        }

        $result = nativephp_call('PackageInfo.GetInfo', '{}');

        if (! $result) {
            return null;
        }

        $decoded = json_decode($result, true);

        if (! is_array($decoded)) {
            return null;
        }

        $packageInfoData = PackageInfoData::fromArray($decoded);

        PackageInfoRetrieved::dispatch($packageInfoData);

        return $packageInfoData;
    }
}
