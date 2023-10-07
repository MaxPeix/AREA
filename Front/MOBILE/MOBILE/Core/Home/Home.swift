//
//  Home.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/10/2023.
//

import SwiftUI

struct Home: View {
    @Environment(\.colorScheme) var colorScheme
    @State private var cards: [Int] = [1]

    var body: some View {
        NavigationStack {
            ZStack {
                Color("background")
                    .ignoresSafeArea()

                VStack(spacing: 20) {
                    Text("Current Areas")
                        .font(.headline)
                        .foregroundColor(Color.black)
                        .padding(.top, 30)

                    List {
                        ForEach(cards, id: \.self) { num in
                            CardView(title: "Area \(num)")
                                .listRowBackground(Color.clear)
                                .listRowSeparator(.hidden)
                        }
                        .onDelete(perform: removeCard)
                    }
                    .listStyle(PlainListStyle())
                    .background(Color.clear)

                    AddCard {
                        addCard()
                    }
                    .offset(y: -10)
                }
                .padding(EdgeInsets(top: 0, leading: 40, bottom: 40, trailing: 40))
            }
        }
    }
    
    func addCard() {
        let newCardNum = (cards.last ?? 0) + 1
        cards.append(newCardNum)
    }
    
    func removeCard(at offsets: IndexSet) {
        cards.remove(atOffsets: offsets)
    }
}

struct Home_Previews: PreviewProvider {
    static var previews: some View {
        Home()
    }
}
