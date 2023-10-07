//
//  TabView.swift
//  MOBILE
//
//  Created by Timothé  FRANCK on 07/10/2023.
//

import SwiftUI

struct MyTabView: View {
    @State private var tabSelection: Int = 1

    var body: some View {
        TabView (selection: $tabSelection) {
            HomeView()
                .tag(1)

            AreaView()
                .tag(2)
            
            ProfileView()
                .tag(3)
        }
        .overlay(alignment: .bottom) {
            CustomTabView(tabSelection: $tabSelection)
        }
    }
}

struct MyTabView_Previews: PreviewProvider {
    static var previews: some View {
        MyTabView()
    }
}
