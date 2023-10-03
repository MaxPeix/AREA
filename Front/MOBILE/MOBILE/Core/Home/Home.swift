//
//  Home.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/10/2023.
//

import SwiftUI

struct Home: View {
    @Environment(\.colorScheme) var colorScheme
    @State private var cardcounter: Int = 1

    var body: some View {
        NavigationStack {
            ZStack {
                Color("background")
                    .ignoresSafeArea()

                RoundedRectangle(cornerRadius: 20)
                    .fill(Color.green.opacity(0.5))
                    .padding(EdgeInsets(top: 0, leading: 40, bottom: 40, trailing: 40))
                    .overlay(
                        VStack(spacing: 20) {
                            Text("Current Areas")
                                .font(.headline)
                                .foregroundColor(Color.black)
                                .padding(.top, 30)
                            
                            ScrollView {
                                VStack(spacing: 20) {
                                    ForEach(1...cardcounter, id: \.self) { num in
                                        CardView(title: "Area \(num)")
                                    }
                                }
                            }

                            AddCard {
                                cardcounter += 1
                            }
                            .offset(y: -30)

                            Spacer()
                        }
                    )
            }
        }
    }
}


struct Home_Previews: PreviewProvider {
    static var previews: some View {
        Home()
    }
}
