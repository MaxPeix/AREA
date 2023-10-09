//
//  AddCard.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/10/2023.
//

import SwiftUI

struct AddCard: View {
    var addAction: () -> Void

    var body: some View {
        RoundedRectangle(cornerRadius: 20)
            .fill(Color("Button"))
            .frame(width: 350, height: 100)
            .overlay(
                Button(action: addAction) {
                    Image(systemName: "plus")
                        .resizable()
                        .frame(width: 40, height: 40)
                        .foregroundColor(Color.white)
                },
                alignment: .center
            )
    }
}

struct AddCard_Previews: PreviewProvider {
    static var previews: some View {
        AddCard {
            print("Button clicked!")
        }
    }
}
