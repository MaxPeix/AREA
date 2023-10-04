//
//  SettingsRowView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 03/10/2023.
//

import SwiftUI

struct SettingsRowView: View {
    let imageName: String
    let title: String

    var body: some View {
        HStack(spacing: 12) {
            Image(systemName: imageName)
                .imageScale(.small)
                .font(.title)
                .foregroundColor(Color("TextColor"))
                
            Text(title)
                .font(.subheadline)
                .foregroundColor(Color("TextColor"))
        }
    }
}

#Preview {
    SettingsRowView(imageName: "gear", title: "Version")
}
