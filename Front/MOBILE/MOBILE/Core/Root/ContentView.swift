//
//  ContentView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 27/09/2023.
//

import SwiftUI

struct ContentView: View {
    @Environment(\.colorScheme) var colorScheme

    var body: some View {
        NavigationStack {
            ZStack {
                Color("background")
                    .ignoresSafeArea()
                VStack {
                    if colorScheme == .dark {
                        Image("LogoDark")
                            .resizable()
                            .frame(width: 100, height: 100)
                    } else {
                        Image("LogoLight")
                            .resizable()
                            .frame(width: 100, height: 100)
                    }
                    // Image
                    
                    // Form fields
                    
                    // Sign in button
                    
                    // Sign up button
                }
            }
        }
    }
}

#Preview {
    ContentView()
}
