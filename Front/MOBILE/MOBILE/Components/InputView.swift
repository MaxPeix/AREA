//
//  InputView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 27/09/2023.
//

import SwiftUI

struct InputView: View {
    @Binding var text: String
    let placeholder: String
    var isSecureField = false
    var textColor: Color = Color("TextColor")


    var body: some View {
        VStack/*(alignment: .leading/*, spacing: 12*/)*/ {
//            Text(title)
//                .foregroundColor(Color("TextColor"))
//                .fontWeight(.semibold)
//                .font(.footnote)

            if isSecureField {
                SecureField(placeholder, text: $text)
                    .padding()
                    .frame(width: 300, height: 50)
                    .background(Color("BlocTwo"))
                    .cornerRadius(10)
                    .foregroundColor(Color("TextColor"))
            } else {
                TextField(placeholder, text: $text)
                    .padding()
                    .frame(width: 300, height: 50)
                    .background(Color("BlocTwo"))
                    .cornerRadius(10)
                    .foregroundColor(Color("TextColor"))
            }
        }
    }
}

struct InputView_Preview: PreviewProvider {
    static var previews: some View {
        InputView(text: .constant(""), placeholder: "johndoe@example.com")
    }
}
