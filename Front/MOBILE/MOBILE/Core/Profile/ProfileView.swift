//
//  ProfileView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 02/10/2023.
//

import SwiftUI

struct ProfileView: View {
    @AppStorage("isLoggedIn") var isLoggedIn: Bool = false
    @State private var showGoogleConnect: Bool = false

    var body: some View {
        NavigationStack {
            ZStack {
                Color("background")
                List {
                    Section("Account") {
                        Text("Hello, Jimmy McGill 👋")
                        
                        HStack {
                            SettingsRowView(imageName: "gear", title: "Version")
                            Spacer()
                            Text("Alpha")
                        }
                        Button (action: {
                            UserDefaults.resetStandardUserDefaults()
                            isLoggedIn = false
                        }) {
                        HStack {
                            SettingsRowView(imageName: "arrow.left.circle.fill", title: "Sign out")
                            }
                        }
//                        Button {
//                            print("Sign out")
//                        } label: {
//                            SettingsRowView(imageName: "arrow.left.circle.fill", title: "Sign out")
//                        }
                        
                    }
                    
                    Section("Overview") {
                        ServiceRowView(imageName: "LogoDiscord", title: "Discord")
                        ServiceRowView(imageName: "LogoYoutube", title: "Youtube")
                        ServiceRowView(imageName: "LogoDrive", title: "Drive")
                            .onTapGesture {
                                showGoogleConnect.toggle()
                            }
                        .sheet(isPresented: $showGoogleConnect) {
                            GoogleConnectView()
                        }
                        ServiceRowView(imageName: "LogoGmail", title: "Gmail")
                        ServiceRowView(imageName: "LogoTwitch", title: "Twitch")
                        ServiceRowView(imageName: "LogoSpotify", title: "Spotify")
                        ServiceRowView(imageName: "LogoFranceInter", title: "Radio France")
                    }
                }
            }
        }
    }
}

#Preview {
    ProfileView()
}
