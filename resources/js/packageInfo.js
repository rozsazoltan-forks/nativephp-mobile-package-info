/**
 * PackageInfo Plugin for NativePHP Mobile
 *
 * Provides an API for querying information about the application package,
 * mirroring Flutter's package_info_plus plugin.
 *
 * @example
 * import { packageInfo } from '@codingwithrk/package-info';
 *
 * const info = await packageInfo.getInfo();
 * console.log(info.appName);     // "My App"
 * console.log(info.packageName); // "com.example.myapp"
 * console.log(info.version);     // "1.2.3"
 * console.log(info.buildNumber); // "42"
 */

const baseUrl = '/_native/api/call';

/**
 * Internal bridge call helper.
 * @private
 * @param {string} method
 * @param {Object} [params={}]
 * @returns {Promise<any>}
 */
async function bridgeCall(method, params = {}) {
    const response = await fetch(baseUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
        },
        body: JSON.stringify({ method, params })
    });

    const result = await response.json();

    if (result.status === 'error') {
        throw new Error(result.message ?? 'Native call failed');
    }

    const nativeResponse = result.data;
    if (nativeResponse && nativeResponse.data !== undefined) {
        return nativeResponse.data;
    }

    return nativeResponse;
}

/**
 * @typedef {Object} PackageInfoData
 * @property {string} appName        - Human-readable application name.
 * @property {string} packageName    - Unique application identifier
 *                                     (bundle ID on iOS, application ID on Android).
 * @property {string} version        - Marketing version string (e.g. "1.2.3").
 * @property {string} buildNumber    - Build / version code as a string (e.g. "42").
 * @property {string} installerStore - Package name of the store that installed the app
 *                                     (e.g. "com.android.vending"), or "" if unknown / iOS.
 */

/**
 * Retrieve application package information from the native platform.
 *
 * @returns {Promise<PackageInfoData>}
 */
export async function getInfo() {
    return bridgeCall('PackageInfo.GetInfo');
}

/**
 * PackageInfo namespace object for default-import usage.
 *
 * @example
 * import packageInfo from '@codingwithrk/package-info';
 * const info = await packageInfo.getInfo();
 */
export const packageInfo = {
    getInfo,
};

export default packageInfo;
