//
//  Twitch.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/11/2023.
//

import SwiftUI
import Alamofire

struct TokenCheckResponseTwitch: Decodable {
    let twitch: Bool
}

struct TwitchConnectView: View {
    @State private var isConnectedToTwitch: Bool = false
    @State private var errorMessage: String? = nil
    
    var body: some View {
        VStack {
            if !isConnectedToTwitch {
                Button("Se connecter") {
                    connectTwitch { success in
                        if success {
                            self.isConnectedToTwitch = true
                        } else {
                            self.isConnectedToTwitch = false
                        }
                    }
                }
                if let error = errorMessage {
                    Text(error).foregroundColor(.red)
                }
            } else {
                Text("Connected to Twitch")
            }
        }
        .onAppear(perform: checkTwitchConnectionStatus)
    }
    
    func checkTwitchConnectionStatus() {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            
            AF.request("http://127.0.0.1:8000/api/checktokens", method: .get, headers: headers)
                .responseDecodable(of: TokenCheckResponseTwitch.self) { response in
                    switch response.result {
                    case .success(let tokenStatus):
                        self.isConnectedToTwitch = tokenStatus.twitch
                    case .failure(let error):
                        self.errorMessage = "Erreur lors de la vérification de la connexion à Twitch: \(error.localizedDescription)"
                    }
                }
        } else {
            errorMessage = "AuthToken est nul"
        }
    }
    
    func connectTwitch(completion: @escaping (Bool) -> Void) {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            AF.request("https://127.0.0.1:8000/api/twitch-callback", method: .get, headers: headers)
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

struct TwitchConnectView_Previews: PreviewProvider {
    static var previews: some View {
        TwitchConnectView()
    }
}
