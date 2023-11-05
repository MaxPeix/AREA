//
//  AreaView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 07/10/2023.
//

import SwiftUI
import Alamofire

struct AreaView: View {
    @State private var areaHistoriques: [AreaHistorique] = []
    
    var body: some View {
        List(areaHistoriques, id: \.id) { item in
            VStack(alignment: .leading) {
                Text(item.name).font(.headline)
                Text(item.description).font(.subheadline)
            }
        }
        .onAppear(perform: loadData)
    }
    
    private func loadData() {
        if let authToken = AuthManager.getAuthToken() {
            let headers: HTTPHeaders = [
                "Authorization": "Bearer \(authToken)"
            ]
            
            AF.request("http://localhost:8080/api/areahistorique", headers: headers)
                .responseDecodable(of: [AreaHistorique].self) { response in
                    switch response.result {
                    case .success(let areaHistoriques):
                        self.areaHistoriques = areaHistoriques
                    case .failure(let error):
                        print("Request failed with error: \(error)")
                    }
                    DispatchQueue.main.asyncAfter(deadline: .now() + 10) {
                        self.loadData()
                    }
                }
        } else {
            print("Authentication token not available.")
        }
    }
}

struct AreaHistorique: Decodable {
    var id: Int
    var users_id: Int
    var name: String
    var description: String
    var informations_random: String?
    var created_at: String
    var updated_at: String
}

struct AreaView_Previews: PreviewProvider {
    static var previews: some View {
        AreaView()
    }
}
