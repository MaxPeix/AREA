//
//  ProfileView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 02/10/2023.
//

import SwiftUI
import JWTDecode

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

struct ProfileView: View {
    @AppStorage("isLoggedIn") var isLoggedIn: Bool = false
    @State private var showGoogleConnect: Bool = false
    @State private var showSpotifyConnect: Bool = false
    @State private var isConnectedToGoogle: Bool = false
    @State private var isConnectedToSpotify: Bool = false
    @State private var showDropboxConnect: Bool = false
    @State private var isConnectedToDropbox: Bool = false
    @State private var showGithubConnect: Bool = false
    @State private var isConnectedToGithub: Bool = false
    @State private var username: String = ""

    func onGoogleConnectViewDismiss() {
        self.isConnectedToGoogle = true
    }

    func onSpotifyConnectViewDismiss() {
        self.isConnectedToSpotify = true
    }
    
    func onDropboxConnectViewDismiss() {
        self.isConnectedToDropbox = true
    }
    
    func onGithubConnectViewDismiss() {
        self.isConnectedToGithub = true
    }
    
    func getUsernameFromToken() {
        guard let token = AuthManager.getAuthToken() else {
            print("No token found")
            return
        }
        do {
            let jwt = try decode(jwt: token)
            if let user = jwt.claim(name: "username").string {
                DispatchQueue.main.async {
                    self.username = user
                }
            }
        } catch {
            print("Invalid token")
        }
    }
    
    var body: some View {
            NavigationView {
                ZStack {
                    Color("background")
                    List {
                        Section(header: Text("Account")) {
                            Text("Hello, \(username)👋")
                            
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
                        ServiceRowView2(imageName: "LogoGoogle", title: "Google", isConnected: isConnectedToGoogle)
                            .onTapGesture {
                                showGoogleConnect.toggle()
                            }
                            .sheet(isPresented: $showGoogleConnect, onDismiss: onGoogleConnectViewDismiss) {
                                GoogleConnectView()
                            }
                        ServiceRowView2(imageName: "LogoDropBox", title: "Dropbox", isConnected: isConnectedToDropbox)
                            .onTapGesture {
                                showDropboxConnect.toggle()
                            }
                            .sheet(isPresented: $showDropboxConnect, onDismiss: onDropboxConnectViewDismiss) {
                                DropboxConnectView()
                            }
                        ServiceRowView2(imageName: "LogoGithub", title: "Github", isConnected: isConnectedToGithub)
                            .onTapGesture {
                                showGithubConnect.toggle()
                            }
                            .sheet(isPresented: $showGithubConnect, onDismiss: onGithubConnectViewDismiss) {
                                GithubConnectView()
                            }
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
            .onAppear {
                getUsernameFromToken()
            }
        }
    }
}

struct ProfileView_Previews: PreviewProvider {
    static var previews: some View {
        ProfileView()
    }
}
