//
//  AddCard.swift
//  MOBILE
//
//  Created by Hugo Dubois on 03/10/2023.
//

import SwiftUI

struct AddCard: View {
    var body: some View {
        RoundedRectangle(cornerRadius: 20)
            .fill(Color.green)
            .frame(width: 280, height: 180)
            .overlay(
                Button(action: {
                    print("Cliqued")
                }) {
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
        AddCard()
    }
}
