//
//  CardView.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/10/2023.
//

import SwiftUI

struct CardView: View {
    var title: String
    var areaID: Int
    @State private var isSwitched: Bool = true
//    @State private var isShowingDetails = false // Nouvelle variable d'état pour suivre si les détails sont affichés

    var body: some View {
        NavigationLink(destination: AreaInformationView(areaID: areaID)) {
            RoundedRectangle(cornerRadius: 20)
                .fill(Color("Button"))
                .frame(width: 300, height: 130)
                .shadow(color: Color.black.opacity(0.2), radius: 5, x: 0, y: 5)
                .overlay(
                    VStack {
                        Text(title)
                            .font(.system(size: 32, weight: .bold))
                            .foregroundColor(.black)
                        
                        Toggle("", isOn: $isSwitched)
                            .labelsHidden()
                            .opacity(isSwitched ? 0.5 : 1.0)
                    }
                        .padding()
                )
                .overlay(
                    Image(systemName: "arrow.right")
                        .resizable()
                        .frame(width: 40, height: 25)
                        .padding(20),
                    alignment: .bottomTrailing
                )
        }
    }
}


struct CardView_Previews: PreviewProvider {
    static var previews: some View {
        CardView(title: "Area 1", areaID: 1)
            .padding()
            .previewLayout(.sizeThatFits)
    }
}
