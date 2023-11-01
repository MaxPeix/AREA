//
//  Home.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/10/2023.
//

import SwiftUI
import Alamofire

struct HomeView: View {
    @Environment(\.colorScheme) var colorScheme
    @State private var cards: [Int] = [1]
    @State private var areas: [Area] = []
    @State private var isDataLoaded = false
    @State private var isActivate: Bool = false

    var body: some View {
        NavigationStack {
            ZStack {
                if !isDataLoaded {
                    Text("Loading ...")
                        .font(.headline)
                        .foregroundColor(.gray)
                        .padding(.top, 30)
                        .onAppear {
                            getAllArea()
                            isDataLoaded = true
                        }
                }
                Color("background")
                    .ignoresSafeArea()
                VStack(spacing: 20) {
                    Text("Current Areas")
                        .font(.headline)
                        .foregroundColor(Color.black)
                        .padding(.top, 30)
                    List {
                        ForEach(areas, id: \.id) { area in
                            CardView(title: area.name)
                                .listRowBackground(Color.clear)
                                .listRowSeparator(.hidden)
                            //                                .onTapGesture {
                            //                                    print("Card \(num) a été cliquée.")
                            //                                }
                        }
//                        .onDelete(perform: removeCard)
                    }
                    .listStyle(PlainListStyle())
                    .background(Color.clear)

                    NavigationLink {
                        CreateAreaView()
                    } label: {
                        AddCard()
                    }
                    .offset(y: -10)
                }
                .padding(EdgeInsets(top: 0, leading: 40, bottom: 40, trailing: 40))
            }
        }
    }

    func getAllArea() {
        let apiURL = "http://localhost:8000/api/area"

        struct YourResponse: Decodable {
            let status: String
            let user: User
            let authorisation: Authorisation
            let data: [Area]
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
                        areas = yourResponse

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

//    func removeCard(at offsets: IndexSet) {
//        cards.remove(atOffsets: offsets)
//    }

struct HomeView_Previews: PreviewProvider {
    static var previews: some View {
        HomeView()
    }
}

struct Area: Decodable {
    let id: Int
    let name: String
    let description: String
    let activated: Bool
    let action: [Action]
    let historique: [History]
}

struct Action: Decodable {
    let id: Int
    let name: String?
    let description: String?
    let activated: Bool
    let services: Service
    let reactions: [Reaction]
}

struct Reaction: Decodable {
    let id: Int
    let activated: Bool
    let action_id: Int
    let services: Service
}

struct Service: Decodable {
    let id: Int
    let service_name: String
    let service_description: String
    let url: String
    let working: Bool
}

struct History: Decodable {
    let pastRequest: String
}
