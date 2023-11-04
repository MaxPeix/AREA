//
//  GithubView.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/11/2023.
//

import SwiftUI
import Alamofire

struct TokenCheckResponseGithub: Decodable {
    let github: Bool
}

struct GithubConnectView: View {
    @State private var isConnectedToGithub: Bool = false
    @State private var errorMessage: String? = nil
    
    var body: some View {
        VStack {
            if !isConnectedToGithub {
                Button("Se connecter") {
                    connectGithub { success in
                        if success {
                            self.isConnectedToGithub = true
                        } else {
                            self.isConnectedToGithub = false
                        }
                    }
                }
                if let error = errorMessage {
                    Text(error).foregroundColor(.red)
                }
            } else {
                Text("Connected to Github")
            }
        }
        .onAppear(perform: checkGithubConnectionStatus)
    }
    
    func checkGithubConnectionStatus() {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            
            AF.request("http://127.0.0.1:8000/api/checktokens", method: .get, headers: headers)
                .responseDecodable(of: TokenCheckResponseGithub.self) { response in
                    switch response.result {
                    case .success(let tokenStatus):
                        self.isConnectedToGithub = tokenStatus.github
                    case .failure(let error):
                        self.errorMessage = "Erreur lors de la vérification de la connexion à Github: \(error.localizedDescription)"
                    }
                }
        } else {
            errorMessage = "AuthToken est nul"
        }
    }
    
    func connectGithub(completion: @escaping (Bool) -> Void) {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            AF.request("http://127.0.0.1:8000/api/github-callback", method: .get, headers: headers)
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
        } else {
            errorMessage = "AuthToken est nul"
        }
    }
}

struct GithubConnectView_Previews: PreviewProvider {
    static var previews: some View {
        GithubConnectView()
    }
}
