//
//  ButtonView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 02/10/2023.
//

import SwiftUI

struct ButtonView: View {
    let placeholder: String
    @State var consoleLog: String

    var body: some View {
        Button {
            print(consoleLog)
        } label: {
            HStack {
                Text(placeholder)
                    .foregroundColor(Color("TextColor"))
                    .foregroundColor(.white)
                    .frame(width: 300, height: 50)
                    .background(Color("Bloc"))
                    .cornerRadius(10)
                    .font(.system(size: 24))
            }
        }
    }
}

#Preview {
    ButtonView(placeholder: "Sign In", consoleLog: "")
}
