<?php

namespace Codingwithrk\PackageInfo;

/**
 * Value object holding application package information.
 *
 * Mirrors the data returned by Flutter's package_info_plus:
 *   appName        - Human-readable application name.
 *   packageName    - Unique application identifier
 *                    (bundle ID on iOS, application ID on Android).
 *   version        - Marketing version string (e.g. "1.2.3").
 *   buildNumber    - Build / version code as a string (e.g. "42").
 *   installerStore - Package name of the store that installed the app
 *                    (e.g. "com.android.vending"), or an empty string
 *                    when the source is unknown or not applicable (iOS).
 */
readonly class PackageInfoData
{
    public function __construct(
        public string $appName,
        public string $packageName,
        public string $version,
        public string $buildNumber,
        public string $installerStore = '',
    ) {}

    /**
     * Construct from a raw associative array returned by the native bridge.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            appName: $data['appName'] ?? '',
            packageName: $data['packageName'] ?? '',
            version: $data['version'] ?? '',
            buildNumber: $data['buildNumber'] ?? '',
            installerStore: $data['installerStore'] ?? '',
        );
    }

    /**
     * Serialize to a plain array.
     */
    public function toArray(): array
    {
        return [
            'appName' => $this->appName,
            'packageName' => $this->packageName,
            'version' => $this->version,
            'buildNumber' => $this->buildNumber,
            'installerStore' => $this->installerStore,
        ];
    }
}
