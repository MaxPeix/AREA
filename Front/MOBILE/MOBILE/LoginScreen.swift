//
//  LoginScreen.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 26/09/2023.
//

import SwiftUI

struct LoginScreen: View {
    @State private var username = ""
    @State private var password = ""
    @State private var showHomeScreen = false
    @Environment(\.colorScheme) var colorScheme

    var body: some View {
        NavigationView {
            ZStack {
                Color("background")
                    .ignoresSafeArea()
                VStack {
                    Text("Login")
                        .bold()
                        .padding()
                    TextField("Email", text: $username)
                        .padding()
                        .frame(width: 300, height: 50)
                        .background(Color("BlocTwo"))
                        .cornerRadius(10)

                    TextField("Password", text: $password)
                        .padding()
                        .frame(width: 300, height: 50)
                        .background(Color("BlocTwo"))
                        .cornerRadius(10)

                    Button("Login") {
                    }
                    .foregroundColor(.white)
                    .frame(width: 300, height: 50)
                    .background(Color("Bloc"))
                    .cornerRadius(10)
                    
                    NavigationLink(destination: Text("Successfully Logged In"), isActive: $showHomeScreen) {
                        EmptyView()
                    }
                        
                }
            }
        }
        .navigationBarHidden(true)
    }
}

#Preview {
    LoginScreen()
}
