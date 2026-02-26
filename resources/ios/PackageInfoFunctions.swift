import Foundation

enum PackageInfoFunctions {

    /**
     Returns application package information.

     Returned dictionary keys:
       - appName        : CFBundleDisplayName falling back to CFBundleName (e.g. "My App")
       - packageName    : CFBundleIdentifier / bundle ID (e.g. "com.example.myapp")
       - version        : CFBundleShortVersionString / marketing version (e.g. "1.2.3")
       - buildNumber    : CFBundleVersion / build string (e.g. "42")
       - installerStore : always "" on iOS; present for API parity with Android.
     */
    class GetInfo: BridgeFunction {
        func execute(parameters: [String: Any]) throws -> [String: Any] {
            let bundle = Bundle.main
            let info = bundle.infoDictionary ?? [:]

            let appName = info["CFBundleDisplayName"] as? String
                ?? info["CFBundleName"] as? String
                ?? ""

            let packageName = bundle.bundleIdentifier ?? ""

            let version = info["CFBundleShortVersionString"] as? String ?? ""

            let buildNumber = info["CFBundleVersion"] as? String ?? ""

            // iOS does not expose the originating store via a public API.
            // Return an empty string for API parity with Android.
            let installerStore = ""

            return BridgeResponse.success(data: [
                "appName": appName,
                "packageName": packageName,
                "version": version,
                "buildNumber": buildNumber,
                "installerStore": installerStore
            ])
        }
    }
}
