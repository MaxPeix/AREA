//
//  RegistrationView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 02/10/2023.
//

import SwiftUI

struct RegistrationView: View {
    @State private var email = ""
    @State private var password = ""
    @State private var confirmPassword = ""
    @Environment(\.colorScheme) var colorScheme
    @Environment(\.dismiss) var dismiss

    var body: some View {
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
                    
                    InputView(text: $confirmPassword, placeholder: "Confirm Password", isSecureField: true)
                        .autocapitalization(.none)
                    
                }
                .padding(.vertical, 32)
                ButtonView(placeholder: "Register", consoleLog: "register in")
                
                Button {
                    dismiss()
                } label: {
                    HStack {
                        Text("Already have an account ?")
                            
                    }
                }
            }
        }
    }
}

#Preview {
    RegistrationView()
}
