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
    @State private var action_option1: String = ""
    @State private var action_option2: String = ""
    @State private var reaction_option1: String = ""
    @State private var reaction_option2: String = ""
    @State private var desc_area: String = ""
    @State private var isActivate: Bool = true
    @State private var services: [AllService] = []
    @State private var actionServices: [AllService] = []
    @State private var reactionServices: [AllService] = []
    @State private var selectedActionService: Int?
    @State private var selectedReactionService: Int?
    @State private var selectedActionServiceName: String?
    @State private var showAlert = false
    @State private var errorMessage = ""
    @State private var isAreaCreated = false

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
                                Text(service.service_name.replacingOccurrences(of: "[ACTION]", with: "")).tag(service.id as Int?)
                            }
                        }
                    }
                    Section(header: Text("Options Actions")) {
                        if let selectedId = selectedActionService,
                           let selectedService = actionServices.first(where: { $0.id == selectedId }) {
                            if !selectedService.options.isEmpty {
                                if !selectedService.options[0].isEmpty {
                                    TextField(selectedService.options[0], text: $action_option1)
                                }
                                if selectedService.options.count > 1 && !selectedService.options[1].isEmpty {
                                    TextField(selectedService.options[1], text: $action_option2)
                                }
                            }
                        } else {
                            Text("No service selected")
                        }
                    }

                    Section(header: Text("Select Reaction Service")) {
                        Picker("Reaction Service", selection: $selectedReactionService) {
                            ForEach(reactionServices, id: \.id) { service in
                                Text(service.service_name.replacingOccurrences(of: "[REACTION]", with: "")).tag(service.id as Int?)
                            }
                        }
                    }

                    Section(header: Text("Options Reactions")) {
                        if let selectedId = selectedReactionService,
                           let selectedService = reactionServices.first(where: { $0.id == selectedId }) {
                            if !selectedService.options.isEmpty {
                                if !selectedService.options[0].isEmpty {
                                    TextField(selectedService.options[0], text: $reaction_option1)
                                }
                                if selectedService.options.count > 1 && !selectedService.options[1].isEmpty {
                                    TextField(selectedService.options[1], text: $reaction_option2)
                                }
                            }
                        } else {
                            Text("No service selected")
                        }
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
        .alert(isPresented: $showAlert) {
            Alert(
                title: Text("Error"),
                message: Text(errorMessage),
                dismissButton: .default(Text("OK"))
            )
        }
        .alert(isPresented: $isAreaCreated) {
            Alert(
                title: Text("Area Created"),
                message: Text("Area is now created"),
                dismissButton: .default(Text("OK")) {
                    NavigationLink(destination: HomeView()) {
                        EmptyView() // Utilisé pour activer la navigation
                    }
                }
            )
        }
    }


    func getAllService() {
        let apiURL = "http://localhost:8080/api/services"

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
        
        struct Response: Decodable {
            let message: String?
            // Ajoutez ici d'autres propriétés attendues de votre réponse JSON, rendant optionnelles celles qui peuvent manquer.
        }
        
        let apiURL = "http://localhost:8080/api/area"

        let config = [
            action_option1.isEmpty ? "" : action_option1,
            action_option2.isEmpty ? "" : action_option2,
            reaction_option1.isEmpty ? "" : reaction_option1,
            reaction_option2.isEmpty ? "" : reaction_option2,
        ]

        let parameters: [String: Any] = [
            "name": name_area,
            "description": desc_area,
            "activated": isActivate,
            "service_action_id": selectedActionService ?? 0,
            "service_reaction_id": selectedReactionService ?? 0,
            "config": config
        ]

        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer " + authToken
            ]

            AF.request(apiURL, method: .post, parameters: parameters, encoding: JSONEncoding.default, headers: headers)
                .validate()
                .response { response in
                    switch response.result {
                    case .success:
                        if let data = response.data {
                            print("Raw data: \(String(describing: String(data: data, encoding: .utf8)))")
                            isAreaCreated = true
                        }
                        do {
                            let _ = try response.result.get()
                        } catch {
                            print("Error during JSON decoding: \(error)")
                        }
                    case .failure(let error):
                        print("Request failed with error: \(error)")
                        self.showAlert = true
                    }
                }
        } else {
            print("AuthToken est nul")
        }
        
    }

    struct CreateAreaView_Previews: PreviewProvider {
        static var previews: some View {
            CreateAreaView()
        }
    }
}
