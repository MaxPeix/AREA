//
//  LoginView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 27/09/2023.
//

import SwiftUI

struct LoginView: View {
    @State private var email = ""
    @State private var password = ""
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
                            .padding(.vertical, 32)
                    } else {
                        Image("LogoLight")
                            .resizable()
                            .frame(width: 100, height: 100)
                            .padding(.vertical, 32)
                    }
                    VStack(spacing: 12) {
                        InputView(text: $email, placeholder: "Your Email")
                            .autocapitalization(.none)
                        InputView(text: $password, placeholder: "Your Password", isSecureField: true)
                            .autocapitalization(.none)
                    }
                    .padding(.vertical, 32)
                    ButtonView(placeholder: "Sign In", consoleLog: "Log user in ..")
                    NavigationLink {
                        RegistrationView()
                            .navigationBarBackButtonHidden()
                    } label: {
                        HStack {
                            Text("No Account ?")
                        }
                    }
                }
            }
        }
    }
}

#Preview {
    LoginView()
}
