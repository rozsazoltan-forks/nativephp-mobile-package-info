## codingwithrk/package-info

A NativePHP Mobile plugin that provides an API for querying information about the
application package — mirroring the API of Flutter's `package_info_plus` plugin.

### Installation

```bash
composer require codingwithrk/package-info
```

### PHP Usage (Livewire / Blade)

Use the `PackageInfo` facade to retrieve all package fields in a single call:

@verbatim
<code-snippet name="Retrieve package information" lang="php">
use Codingwithrk\PackageInfo\Facades\PackageInfo;

$info = PackageInfo::getInfo();

// $info is a PackageInfoData value object (or null outside NativePHP Mobile)
echo $info->appName;        // "My App"
echo $info->packageName;    // "com.example.myapp"
echo $info->version;        // "1.2.3"
echo $info->buildNumber;    // "42"
echo $info->installerStore; // "com.android.vending" | ""
</code-snippet>
@endverbatim

### Available Methods

| Method | Returns | Description |
|---|---|---|
| `PackageInfo::getInfo()` | `PackageInfoData\|null` | Retrieve all package information in one call |

### PackageInfoData Properties

| Property | Type | Description |
|---|---|---|
| `appName` | `string` | Human-readable display name (e.g. "My App") |
| `packageName` | `string` | Unique identifier — bundle ID on iOS, application ID on Android |
| `version` | `string` | Marketing version string (e.g. "1.2.3") |
| `buildNumber` | `string` | Version code / build number (e.g. "42") |
| `installerStore` | `string` | Originating store (e.g. "com.android.vending"), empty string on iOS or unknown source |

### Events

The `PackageInfoRetrieved` event is dispatched automatically after a successful
`getInfo()` call. Listen for it with the `#[OnNative]` attribute in a Livewire component:

@verbatim
<code-snippet name="Listening for PackageInfoRetrieved" lang="php">
use Native\Mobile\Attributes\OnNative;
use Codingwithrk\PackageInfo\Events\PackageInfoRetrieved;
use Codingwithrk\PackageInfo\PackageInfoData;

#[OnNative(PackageInfoRetrieved::class)]
public function handlePackageInfoRetrieved(PackageInfoData $info): void
{
    $this->appName     = $info->appName;
    $this->version     = $info->version;
    $this->buildNumber = $info->buildNumber;
}
</code-snippet>
@endverbatim

### JavaScript Usage (Vue / React / Inertia)

@verbatim
<code-snippet name="Using PackageInfo in JavaScript" lang="javascript">
import { packageInfo } from '@codingwithrk/package-info';

const info = await packageInfo.getInfo();

console.log(info.appName);        // "My App"
console.log(info.packageName);    // "com.example.myapp"
console.log(info.version);        // "1.2.3"
console.log(info.buildNumber);    // "42"
console.log(info.installerStore); // "com.android.vending" | ""
</code-snippet>
@endverbatim

### Vue 3 Component Example

@verbatim
<code-snippet name="Vue 3 component" lang="javascript">
<script setup>
import { ref, onMounted } from 'vue';
import { packageInfo } from '@codingwithrk/package-info';

const info = ref(null);

onMounted(async () => {
    info.value = await packageInfo.getInfo();
});
</script>

<template>
    <div v-if="info">
        <p>{{ info.appName }} v{{ info.version }} (build {{ info.buildNumber }})</p>
    </div>
</template>
</code-snippet>
@endverbatim
