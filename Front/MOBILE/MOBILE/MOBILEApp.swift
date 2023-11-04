//
//  MOBILEApp.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 25/09/2023.
//

import SwiftUI

@main
struct MOBILEApp: App {
    @AppStorage("isLoggedIn") var isLoggedIn: Bool = false

    var body: some Scene {
        WindowGroup {
            Group {
                if isLoggedIn {
                    MyTabView()
                } else {
                    ContentView()
                }
            }
            .onOpenURL { url in
                handleIncomingURL(url: url)
            }
        }
    }
    
    func handleIncomingURL(url: URL) {
        if let components = URLComponents(url: url, resolvingAgainstBaseURL: false),
           let jwtToken = components.queryItems?.first(where: { $0.name == "jwt" })?.value {
            UserDefaults.standard.set(jwtToken, forKey: "AuthToken")
            isLoggedIn = true
        }
    }
}

class AuthManager {
    static func getAuthToken() -> String? {
        return UserDefaults.standard.string(forKey: "AuthToken")
    }
}
