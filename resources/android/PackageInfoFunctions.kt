package com.codingwithrk.plugins.package_info

import android.content.Context
import android.content.pm.PackageManager
import android.os.Build
import com.nativephp.mobile.bridge.BridgeError
import com.nativephp.mobile.bridge.BridgeFunction
import com.nativephp.mobile.bridge.BridgeResponse

object PackageInfoFunctions {

    /**
     * Returns application package information.
     *
     * Returned map keys:
     *  - appName        : display label from the launcher (e.g. "My App")
     *  - packageName    : application ID (e.g. "com.example.myapp")
     *  - version        : version name string (e.g. "1.2.3")
     *  - buildNumber    : version code as a string (e.g. "42")
     *  - installerStore : package name of the store that installed the app,
     *                     e.g. "com.android.vending" for Google Play, or ""
     *                     if installed via ADB / unknown source.
     */
    class GetInfo(private val context: Context) : BridgeFunction {
        override fun execute(parameters: Map<String, Any>): Map<String, Any> {
            return try {
                val packageManager = context.packageManager
                val packageName = context.packageName

                val packageInfo = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                    packageManager.getPackageInfo(packageName, PackageManager.PackageInfoFlags.of(0))
                } else {
                    @Suppress("DEPRECATION")
                    packageManager.getPackageInfo(packageName, 0)
                }

                val applicationInfo = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                    packageManager.getApplicationInfo(packageName, PackageManager.ApplicationInfoFlags.of(0))
                } else {
                    @Suppress("DEPRECATION")
                    packageManager.getApplicationInfo(packageName, 0)
                }

                val appName = packageManager.getApplicationLabel(applicationInfo).toString()

                val version = packageInfo.versionName ?: ""

                val buildNumber = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.P) {
                    packageInfo.longVersionCode.toString()
                } else {
                    @Suppress("DEPRECATION")
                    packageInfo.versionCode.toString()
                }

                val installerStore = try {
                    if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.R) {
                        packageManager.getInstallSourceInfo(packageName).installingPackageName ?: ""
                    } else {
                        @Suppress("DEPRECATION")
                        packageManager.getInstallerPackageName(packageName) ?: ""
                    }
                } catch (e: Exception) {
                    ""
                }

                BridgeResponse.success(
                    mapOf(
                        "appName" to appName,
                        "packageName" to packageName,
                        "version" to version,
                        "buildNumber" to buildNumber,
                        "installerStore" to installerStore
                    )
                )
            } catch (e: PackageManager.NameNotFoundException) {
                BridgeResponse.error(BridgeError.ExecutionFailed(e.message ?: "Package not found"))
            } catch (e: Exception) {
                BridgeResponse.error(BridgeError.ExecutionFailed(e.message ?: "Failed to retrieve package info"))
            }
        }
    }
}
