import SwiftUI
import Alamofire

struct AllService: Identifiable, Decodable, Hashable {
    var id: Int
    var service_name: String
    var service_description: String
    var apikey: String
    var url: String
    var working: Bool
    var options: [String]
}

struct exService: Decodable {
    let id: Int
    let service_name: String
    let service_description: String
    let apikey: String
    let url: String
    let working: Bool
    let created_at: String?
    let updated_at: String?
    let options: [String]
}

struct CreateAreaView: View {
    @State private var name_area: String = ""
    @State private var desc_area: String = ""
    @State private var isActivate: Bool = false
    @State private var services: [AllService] = []
    @State private var actionServices: [AllService] = []
    @State private var reactionServices: [AllService] = []
    @State private var selectedActionService: Int?
    @State private var selectedReactionService: Int?
    @State private var selectedActionServiceName: String?

    var body: some View {
        ZStack {
            Color("background")
                .ignoresSafeArea()
            VStack {
                Form {
                    Section(header: Text("Area Information")) {
                        TextField("Name of Area", text: $name_area)
                        TextField("Description of Area", text: $desc_area)
                        Toggle("Enable", isOn: $isActivate)
                    }
                    
                    Section(header: Text("Select Action Service")) {
                        Picker("Action Service", selection: $selectedActionService) {
                            ForEach(actionServices, id: \.id) { service in
                                Text(service.service_name).tag(selectedActionService)
                                //                                Text(service.service_name.replacingOccurrences(of: "[ACTION]", with: "")).tag(service.id)
                            }
                        }
                        .id(UUID())  // Add an id to force SwiftUI to refresh this view
                    }
                    Section(header: Text("Select Reaction Service")) {
                        Picker("Reaction Service", selection: $selectedReactionService) {
                            ForEach(reactionServices, id: \.id) { service in
                                Text(service.service_name.replacingOccurrences(of: "[REACTION]", with: "")).tag(service.id)
                            }
                        }
                        .id(UUID())  // Add an id to force SwiftUI to refresh this view
                    }
                    
                    Button("Create Area") {
                        sendCreateAreaRequest()
                    }
                    .navigationBarTitle("Add a new area")
                }
            }
        }
        .onAppear {
            getAllService()
        }
    }


    func getAllService() {
        let apiURL = "http://localhost:8000/api/services"

        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]

            AF.request(apiURL, method: .get, headers: headers)
                .validate()
                .responseDecodable(of: [AllService].self) { response in
                    switch response.result {

                    case .success(let services):
                        self.services = services
                        self.actionServices = self.services.filter { $0.service_name.contains("[ACTION]") }
                        self.reactionServices = self.services.filter { $0.service_name.contains("[REACTION]") }

                        print("Action Services: \(self.actionServices.map { $0.id })")
                        print("Reaction Services: \(self.reactionServices.map { $0.id })")

                        if let firstActionService = self.actionServices.first {
                            self.selectedActionService = firstActionService.id
                        }
                        if let firstReactionService = self.reactionServices.first {
                            self.selectedReactionService = firstReactionService.id
                        }

                    case .failure(let error):
                        print("Erreur : \(error)")
                    }
                }
        } else {
            print("AuthToken est nul")
        }
    }

    func sendCreateAreaRequest() {
        let apiURL = "http://localhost:8000/api/area"

        let parameters: [String: Any] = [
            "name": name_area,
            "description": desc_area,
            "activated": isActivate,
            "action_service_id": selectedActionService ?? 0,
            "reaction_service_id": selectedReactionService ?? 0
        ]

        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]

            AF.request(apiURL, method: .post, parameters: parameters, encoding: JSONEncoding.default, headers: headers)
                .validate()
                .responseDecodable(of: AllService.self) { response in
                    switch response.result {
                    case .success(let data):
                        print("Succès : \(data)")

                    case .failure(let error):
                        print("Erreur : \(error)")
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
