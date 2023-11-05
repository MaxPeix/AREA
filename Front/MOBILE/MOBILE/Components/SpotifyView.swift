//
//  SpotifyView.swift
//  MOBILE
//
//  Created by Hugo Dubois on 23/10/2023.
//

import SwiftUI
import Alamofire

struct TokenCheckResponseSpotify: Decodable {
    let spotify: Bool
}

struct SpotifyConnectView: View {
    @State private var isConnectedToSpotify: Bool = false
    @State private var errorMessage: String? = nil
    
    var body: some View {
        VStack {
            if !isConnectedToSpotify {
                Button("Se connecter") {
                    connectSpotify { success in
                        if success {
                            self.isConnectedToSpotify = true
                        } else {
                            self.isConnectedToSpotify = false
                        }
                    }
                }
                if let error = errorMessage {
                    Text(error).foregroundColor(.red)
                }
            } else {
                Text("Connected to Spotify")
            }
        }
        .onAppear(perform: checkSpotifyConnectionStatus)
    }
    
    func checkSpotifyConnectionStatus() {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            
            AF.request("http://127.0.0.1:8080/api/checktokens", method: .get, headers: headers)
                .responseDecodable(of: TokenCheckResponseSpotify.self) { response in
                    switch response.result {
                    case .success(let tokenStatus):
                        self.isConnectedToSpotify = tokenStatus.spotify
                    case .failure(let error):
                        self.errorMessage = "Erreur lors de la vérification de la connexion à Spotify: \(error.localizedDescription)"
                    }
                }
        } else {
            errorMessage = "AuthToken est nul"
        }
    }
    
    func connectSpotify(completion: @escaping (Bool) -> Void) {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            
            AF.request("http://127.0.0.1:8080/api/spotify-callback", method: .get, headers: headers)
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
                    case .failure(let error):
                        errorMessage = "Erreur lors de la connexion à Spotify: \(error.localizedDescription)"
                        completion(false)
                    }
                }
        } else {
            errorMessage = "AuthToken est nul"
        }
    }
}

struct SpotifyConnectView_Previews: PreviewProvider {
    static var previews: some View {
        SpotifyConnectView()
    }
}
