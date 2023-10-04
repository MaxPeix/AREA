//
//  ServiceRowView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 03/10/2023.
//

import SwiftUI

struct ServiceRowView: View {
    let imageName: String
    let title: String
    
    var body: some View {
        Button {
            print("Sign out")
        } label: {
            HStack(spacing: 12) {
                Image(imageName)
                    .resizable()
                    .imageScale(.small)
                    .frame(width: 30, height: 30)
                    .font(.title)
                    .foregroundColor(Color("TextColor"))
                        
                Text(title)
                    .font(.subheadline)
                    .foregroundColor(Color("TextColor"))
            }
        }
    }
}

#Preview {
    ServiceRowView(imageName: "LogoDiscord", title: "Discord")
}
