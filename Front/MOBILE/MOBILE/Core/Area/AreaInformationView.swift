import SwiftUI
import Alamofire

struct AreaInformationView: View {
    var areaID: Int
    @State private var areaData: YourResponse?
    @State private var isAreaDeleted = false

    var body: some View {
        ZStack {
            if let data = areaData {
                List {
                    Section(header: Text("Area Information")) {
                        Text("Name: \(data.name)")
                        Text("Description: \(data.description)")
                        Text("Activated: \(data.activated ? "Activated" : "Not Activated")")
                    }
                    Section(header: Text("Action Selected")) {
                        ForEach(data.action, id: \.id) { action in
                            Text("Action ID: \(action.id)")
                            Text("Action Name: \(action.name?.replacingOccurrences(of: "[ACTION]", with: "") ?? "N/A")")
                        }
                    }
                    Section(header: Text("Réactions")) {
                        ForEach(data.action.flatMap { $0.reactions }, id: \.id) { reaction in
                            Text("Réaction ID: \(reaction.id)")
                            Text("Réaction Name: \(reaction.services.service_name.replacingOccurrences(of: "[REACTION]", with: ""))")
                        }
                    }
                    Button (action: {
                        delSpecificArea()
                    }) {
                        HStack {
                            SettingsRowView(imageName: "arrow.left.circle.fill", title: "Delete Area")
                        }
                    }
                }
                
            } else {
                Text("Loading...")
            }
        }
        .onAppear {
            getSpecificArea()
        }
        .alert(isPresented: $isAreaDeleted) {
            Alert(
                title: Text("Area deleted"),
                message: Text("Area deleted with sucess"),
                dismissButton: .default(Text("OK")) {
                    NavigationLink(destination: HomeView()) {
                        EmptyView() // Utilisé pour activer la navigation
                    }
                }
            )
        }
    }

    func delSpecificArea() {
        let apiURL = "http://localhost:8080/api/area/\(areaID)"

        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]

            AF.request(apiURL, method: .delete, headers: headers)
                .validate()
                .response { response in
                    switch response.result {
                    case .success:
                        print("Suppression réussie")
                        isAreaDeleted = true

                    case .failure(let error):
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

    func getSpecificArea() {
        let apiURL = "http://localhost:8080/api/area/\(areaID)"
        
        print(apiURL)

        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]

            AF.request(apiURL, method: .get, headers: headers)
                .validate()
                .responseDecodable(of: [YourResponse].self) { response in
                    switch response.result {
                    case .success(let data):
                        areaData = data.first

                    case .failure(let error):
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


    struct YourResponse: Decodable {
        let id: Int
        let name: String
        let description: String
        let activated: Bool
        let action: [Action] // Une liste d'actions
    }

    struct Action: Decodable {
        let id: Int
        let name: String?
        let description: String?
        let activated: Bool
        let services: Services
        let reactions: [Reaction]
    }

    struct Services: Decodable {
        let id: Int
        let service_name: String
        let service_description: String
        let url: String
        let working: Bool
    }

    struct Reaction: Decodable {
        let id: Int
        let activated: Bool
        let action_id: Int
        let services: Services
    }
}

struct AreaInformationView_Previews: PreviewProvider {
    static var previews: some View {
        AreaInformationView(areaID: 1)
            .padding()
            .previewLayout(.sizeThatFits)
    }
}

