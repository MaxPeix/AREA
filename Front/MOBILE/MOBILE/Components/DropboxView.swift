//
//  Twitch.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/11/2023.
//

import SwiftUI
import Alamofire

struct TokenCheckResponseDropbox: Decodable {
    let Dropbox: Bool
}

struct DropboxConnectView: View {
    @State private var isConnectedToDropbox: Bool = false
    @State private var errorMessage: String? = nil
    
    var body: some View {
        VStack {
            if !isConnectedToDropbox {
                Button("Se connecter") {
                    connectDropbox { success in
                        if success {
                            self.isConnectedToDropbox = true
                        } else {
                            self.isConnectedToDropbox = false
                        }
                    }
                }
                if let error = errorMessage {
                    Text(error).foregroundColor(.red)
                }
            } else {
                Text("Connected to Dropbox")
            }
        }
        .onAppear(perform: checkDropboxConnectionStatus)
    }
    
    func checkDropboxConnectionStatus() {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            
            AF.request("http://127.0.0.1:8080/api/checktokens", method: .get, headers: headers)
                .responseDecodable(of: TokenCheckResponseDropbox.self) { response in
                    switch response.result {
                    case .success(let tokenStatus):
                        self.isConnectedToDropbox = tokenStatus.Dropbox
                    case .failure(let error):
                        self.errorMessage = "Erreur lors de la vérification de la connexion à Dropbox: \(error.localizedDescription)"
                    }
                }
        } else {
            errorMessage = "AuthToken est nul"
        }
    }
    
    func connectDropbox(completion: @escaping (Bool) -> Void) {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]
            AF.request("http://127.0.0.1:8080/api/dropbox-callback", method: .get, headers: headers)
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

struct DropboxConnectView_Previews: PreviewProvider {
    static var previews: some View {
        DropboxConnectView()
    }
}
