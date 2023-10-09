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
            if isLoggedIn {
                MyTabView()
            } else {
                ContentView()
            }
        }
    }
}

class AuthManager {
    static func getAuthToken() -> String? {
        return UserDefaults.standard.string(forKey: "AuthToken")
    }

    
}
