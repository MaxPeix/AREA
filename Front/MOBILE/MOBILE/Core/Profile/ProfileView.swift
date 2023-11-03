//
//  ProfileView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 02/10/2023.
//

import SwiftUI

struct ServiceRowView2: View {
    let imageName: String
    let title: String
    var isConnected: Bool

    var body: some View {
        HStack {
            Image(imageName)
                .resizable()
                .aspectRatio(contentMode: .fit)
                .frame(width: 30, height: 30)
            Text(title)
            Spacer()
            if isConnected {
                Image(systemName: "checkmark.circle.fill")
                    .foregroundColor(.green)
            }
        }
        .padding(.vertical, 4)
    }
}

import SwiftUI

struct ProfileView: View {
    @AppStorage("isLoggedIn") var isLoggedIn: Bool = false
    @State private var showGoogleConnect: Bool = false
    @State private var showSpotifyConnect: Bool = false
    @State private var isConnectedToGoogle: Bool = false
    @State private var isConnectedToSpotify: Bool = false

    func onGoogleConnectViewDismiss() {
        self.isConnectedToGoogle = true
    }

    func onSpotifyConnectViewDismiss() {
        self.isConnectedToSpotify = true
    }

    var body: some View {
        NavigationView {
            ZStack {
                Color("background")
                List {
                    Section(header: Text("Account")) {
                        Text("Hello, Jimmy McGill 👋")
                        
                        HStack {
                            SettingsRowView(imageName: "gear", title: "Version")
                            Spacer()
                            Text("Alpha")
                        }
                        Button (action: {
                            isLoggedIn = false
                        }) {
                            HStack {
                                SettingsRowView(imageName: "arrow.left.circle.fill", title: "Sign out")
                            }
                        }
                    }
                    Section(header: Text("Overview")) {
                        ServiceRowView2(imageName: "LogoGmail", title: "Gmail", isConnected: isConnectedToGoogle)
                            .onTapGesture {
                                showGoogleConnect.toggle()
                            }
                            .sheet(isPresented: $showGoogleConnect, onDismiss: onGoogleConnectViewDismiss) {
                                GoogleConnectView()
                            }
                        ServiceRowView2(imageName: "LogoTwitch", title: "Twitch", isConnected: false)
                        ServiceRowView2(imageName: "LogoSpotify", title: "Spotify", isConnected: isConnectedToSpotify)
                            .onTapGesture {
                                showSpotifyConnect.toggle()
                            }
                            .sheet(isPresented: $showSpotifyConnect, onDismiss: onSpotifyConnectViewDismiss) {
                                SpotifyConnectView()
                            }
                    }
                }
            }
            .navigationBarTitle("Profile")
        }
    }
}

struct ProfileView_Previews: PreviewProvider {
    static var previews: some View {
        ProfileView()
    }
}
