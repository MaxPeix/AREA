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

                    Button (action: {
                        authenticateUser { isAuthenticated, message in
                            if isAuthenticated {
                                // Authentification réussie, passez à une autre vue ou effectuez d'autres actions
                                print(message)
                            } else {
                                // Authentification échouée, affichez un message d'erreur
                                print(message)
                            }
                        }
                    }) {
                        HStack {
                            Text("Sign In")
                                .foregroundColor(Color("TextColor"))
                                .foregroundColor(.white)
                                .frame(width: 300, height: 50)
                                .background(Color("Bloc"))
                                .cornerRadius(10)
                                .font(.system(size: 24))
                        }
                    }
                    
//                    ButtonView(placeholder: "Sign In", consoleLog: "Log user in ..")
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
    func authenticateUser(completion: @escaping (Bool, String) -> Void) {
        let parameters: [String: Any] = [
            "email": email,
            "password": password
        ]

        AF.request("http://localhost:8000/api/login", method: .post, parameters: parameters)
            .validate()
            .responseDecodable(of: YourResponse.self) { response in
                debugPrint(response)
                
                switch response.result {
                case .success(let value):
                    print("Response: \(value)")

                    // Vérifiez si l'authentification a réussi
                    if value.success {
                        completion(true, "Authentification réussie")
                    } else {
                        completion(false, "Nom d'utilisateur ou mot de passe incorrect")
                    }
                case .failure(let error):
                    print("Error: \(error)")
                    completion(false, "Erreur de connexion lolo")
                }
            }
    }

}

#Preview {
    LoginView()
}

struct YourResponse: Decodable {
    let success: Bool
}

struct UserLogin {
    let login: String
    let password: String
}
