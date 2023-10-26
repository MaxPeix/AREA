//
//  CreateAreaView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 12/10/2023.
//

import SwiftUI
import Alamofire

struct CreateAreaView: View {
    @State private var name_area: String = ""
    @State private var desc_area: String = ""
    @State private var isActivate: Bool = false

    var body: some View {
        NavigationView {
            Form {
                Section(header: Text("Area Information")) {
                    TextField("Name of Area", text: $name_area)
                    TextField("Description of Area", text: $desc_area)
                    Toggle("Enable", isOn: $isActivate)
                }

                Button("Create Area") {
                    sendCreateAreaRequest()
                }
                .navigationBarTitle("Add a new area")
            }
        }
    }
    
    func sendCreateAreaRequest() {
        let apiURL = "http://localhost:8000/api/area"

        struct YourResponse: Decodable {
            let status: String
            let user: User
            let authorisation: Authorisation
        }

        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]

            AF.request(apiURL, method: .get, headers: headers)
                .validate()
                .responseDecodable(of: [Area].self) { response in

                    switch response.result {
                    case.success(let yourResponse):
                        print("Succès : \(yourResponse)")

                    case.failure(let error):
                        print("Erreur de requête : \(error)")

                        if let statusCode = response.response?.statusCode {
                            print("Statut de la réponse : \(statusCode)")
                        }
                    }
                }
        } else {
            print("AuthToken est nul")
        }
    }
}

struct CreateAreaView_Previews: PreviewProvider {
    static var previews: some View {
        CreateAreaView()
    }
}
