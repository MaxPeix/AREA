//
//  ContentView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 25/09/2023.
//

import SwiftUI

struct ContentView: View {
    @State private var username = ""
    @State private var password = ""
    @State private var isActive: Bool = false
    @Environment(\.colorScheme) var colorScheme

    var body: some View {
        NavigationView {
            ZStack {
                Color("background")
                    .ignoresSafeArea()
                VStack {
                    if colorScheme == .dark {
                        Image("LogoDark")
                            .resizable()
                            .frame(width: 100, height: 100)
                    } else {
                        Image("LogoLight")
                            .resizable()
                            .frame(width: 100, height: 100)
                    }
                    Text("Welcome Back")
                        .font(.largeTitle)
                        .bold()
                        .padding()
                    NavigationLink("", destination: LoginScreen(), isActive: $isActive)
                        .frame(width: 0, height: 0)
                        .hidden()
                    .onAppear {
                        startTimer()
                    }
                }
            }
        }
        .navigationBarHidden(true)
    }
    func startTimer() {
        Timer.scheduledTimer(withTimeInterval: 3.0, repeats: false) { _ in
                isActive = true
        }
    }
}

struct ContentView_Preview: PreviewProvider {
    static var previews: some View {
        ContentView()
    }
}

//#Preview {
//    ContentView()
//}
