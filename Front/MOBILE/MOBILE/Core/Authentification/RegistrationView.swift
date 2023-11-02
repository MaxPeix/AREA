//
//  RegistrationView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 02/10/2023.
//

import SwiftUI
import Alamofire

struct RegistrationView: View {
    @State private var username = ""
    @State private var email = ""
    @State private var password = ""
    @State private var showAlert = false
    @State private var errorMessage = "Registration problem"
    @Environment(\.colorScheme) var colorScheme
    @Environment(\.dismiss) var dismiss
    @AppStorage("isLoggedIn") var isLoggedIn: Bool = false
    
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
                    InputView(text: $username, placeholder: "Your Username")
                        .autocapitalization(.none)
                    
                    InputView(text: $email, placeholder: "Your Email")
                        .autocapitalization(.none)
                    
                    InputView(text: $password, placeholder: "Your Password", isSecureField: true)
                        .autocapitalization(.none)
                    
                }
                .padding(.vertical, 32)
                Button (action: {
                    registerUser { isAuthenticated, message in
                        if isAuthenticated {
                            print(message)
                        } else {
                            print(message)
                        }
                    }
                }) {
                    HStack {
                        Text("Register")
                            .foregroundColor(Color("TextColor"))
                            .frame(width: 300, height: 50)
                            .background(Color("Bloc"))
                            .cornerRadius(10)
                            .font(.system(size: 24))
                    }
                }
//                ButtonView(placeholder: "Register", consoleLog: "register in")
                
                Button {
                    dismiss()
                } label: {
                    HStack {
                        Text("Already have an account ?")
                            
                    }
                }
            }
        }.alert(isPresented: $showAlert) {
            Alert(
                title: Text("Error"),
                message: Text(errorMessage),
                dismissButton: .default(Text("OK"))
            )
        }
    }
    
    func registerUser(completion: @escaping (Bool, String) -> Void) {
        let parameters: [String: Any] = [
            "username": username,
            "email": email,
            "password": password
        ]

        AF.request("http://localhost:8000/api/register", method: .post, parameters: parameters)
            .validate()
            .responseDecodable(of: YourResponse.self) { response in
                debugPrint(response)

                switch response.result {
                case .success(let value):
                    print("Response: \(value)")

                    if value.status == "success" {
                        completion(true, "Registration successful")
                        
                        let token = value.authorisation.token
                        UserDefaults.standard.set(token, forKey: "AuthToken")
                        isLoggedIn = true

                    } else {
                        completion(false, "Can't register")
                    }
                case .failure(let error):
                    print("Error: \(error)")
                    self.showAlert = true
                    completion(false, "Erreur de register lolo")
                }
            }
    }
}

#Preview {
    RegistrationView()
}
