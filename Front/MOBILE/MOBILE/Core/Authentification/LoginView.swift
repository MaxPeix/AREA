//
//  LoginView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 27/09/2023.
//

import SwiftUI
import Alamofire

struct LoginView: View {
    @State private var email = ""
    @State private var password = ""
    @State private var showAlert = false
    @State private var errorMessage = "Identification invalid"
    @Environment(\.colorScheme) var colorScheme
    @AppStorage("isLoggedIn") var isLoggedIn: Bool = false
    
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
                    
                    Button (action: {
                        authenticateUser { isAuthenticated, message in
                            if isAuthenticated {
                                print(message)
                            } else {
                                print(message)
                            }
                        }
                    }) {
                        HStack {
                            Text("Log In")
                                .foregroundColor(Color("TextColor"))
                                .frame(width: 300, height: 50)
                                .background(Color("Bloc"))
                                .cornerRadius(10)
                                .font(.system(size: 24))
                        }
                    }
                    Button(action: {
                        self.connectGoogle { success in
                            if success {
                                print("Connexion Google réussie")
                            } else {
                                self.showAlert = true
                                self.errorMessage = "Erreur de connexion avec Google"
                            }
                        }
                    }) {
                        HStack {
                            Image("LogoGoogle")
                                .resizable()
                                .scaledToFit()
                                .frame(width: 24, height: 24)
                            Text("Google")
                                .foregroundColor(Color("TextColor"))
                                .font(.system(size: 24))
                        }
                        .frame(width: 300, height: 50)
                        .background(Color("Bloc"))
                        .cornerRadius(10)
                    }

                    
                    NavigationLink {
                        RegistrationView()
                            .navigationBarBackButtonHidden()
                    } label: {
                        HStack {
                            Text("No Account ?")
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
    }
    
    func authenticateUser(completion: @escaping (Bool, String) -> Void) {
        let parameters: [String: Any] = [
            "email": email,
            "password": password
        ]
        
        AF.request("http://localhost:8080/api/login", method: .post, parameters: parameters)
            .validate()
            .responseDecodable(of: YourResponse.self) { response in
                debugPrint(response)
                
                switch response.result {
                case .success(let value):
                    print("Response: \(value)")
                    
                    if value.status == "success" {
                        completion(true, "Authentification réussie")
                        
                        let token = value.authorisation.token
                        UserDefaults.standard.set(token, forKey: "AuthToken")
                        isLoggedIn = true
                        
                    } else {
                        completion(false, "Nom d'utilisateur ou mot de passe incorrect")
                    }
                case .failure(let error):
                    print("Error: \(error)")
                    self.showAlert = true
                    completion(false, "Erreur de connexion")
                }
            }
    }
    func connectGoogle(completion: @escaping (Bool) -> Void) {

            AF.request("http://127.0.0.1:8080/api/oauth2callback?mobile=true", method: .get)
                    .responseString { response in
                        switch response.result {
                        case .success(let urlString):
                            if let urlObj = URL(string: urlString) {
                                UIApplication.shared.open(urlObj)
                                completion(true)
                            } else {
                                self.errorMessage = "URL non valide"
                                completion(false)
                            }
                        case .failure:
                            completion(false)
                    }
                }
        }
}

#Preview {
    LoginView()
}

struct YourResponse: Decodable {
    let status: String
    let user: User
    let authorisation: Authorisation
}

struct User: Decodable {
    let id: Int
    let email: String
}

struct Authorisation: Decodable {
    let token: String
    let type: String
}

struct UserLogin {
    let login: String
    let password: String
}
