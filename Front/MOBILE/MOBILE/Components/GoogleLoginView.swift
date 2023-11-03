////
////  GoogleView.swift
////  MOBILE
////
////  Created by Hugo Dubois on 09/10/2023.
////
//
//import SwiftUI
//import Alamofire
//
//struct TokenCheckResponseLogin: Decodable {
//    let google: Bool
//}
//    
//func checkGoogleConnectionStatus() {
//    if let authToken = AuthManager.getAuthToken() {
//        let headers: HTTPHeaders = [
//            "Authorization": "Bearer " + authToken
//        ]
//        
//        AF.request("http://127.0.0.1:8000/api/checktokens", method: .get, headers: headers)
//            .responseDecodable(of: TokenCheckResponse.self) { response in
//                switch response.result {
//                case .success(let tokenStatus):
//                    self.isConnectedToGoogle = tokenStatus.google
//                case .failure(let error):
//                    self.errorMessage = "Erreur lors de la vérification de la connexion à Google: \(error.localizedDescription)"
//                }
//            }
//    } else {
//        errorMessage = "AuthToken est nul"
//}
//
//func connectGoogle(completion: @escaping (Bool) -> Void) {
//        
//        AF.request("http://127.0.0.1:8000/api/oauth2callback", method: .get)
//                .responseString { response in
//                    switch response.result {
//                    case .success(let urlString):
//                        if let urlObj = URL(string: urlString) {
//                            UIApplication.shared.open(urlObj)
//                            completion(true)
//                        } else {
//                            self.errorMessage = "URL non valide"
//                            completion(false)
//                        }
//                    case .failure:
//                        completion(false)
//                }
//            }
//    }
