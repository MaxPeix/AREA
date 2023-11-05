# AREA



## Project overview

The AREA project consists in the creation of a software suite that functions similarly to IFTTT.
This software suite is a divided into 3 parts:

- A server to implement all the features.
- A web application to use the app from a browser.
- A mobile application to use the app from a phone.

## Technical choices

This project is developped using theses technologies:

- Server = Laravel
- Database = PostgresSQL
- Front WEB = VueJS
- Mobile = Swift

## Organization tools
- Jira ![Alt text](https://www.google.com/url?sa=i&url=https%3A%2F%2Flogowik.com%2Fjira-logo-vector-51263.html&psig=AOvVaw3J12dRH4ksa0j1YtFL_Fk5&ust=1699280929909000&source=images&cd=vfe&opi=89978449&ved=0CBAQjRxqFwoTCOiK5fOIrYIDFQAAAAAdAAAAABAD)
- Discord ![Alt text](https://www.google.com/url?sa=i&url=https%3A%2F%2Ffr.wikipedia.org%2Fwiki%2FFichier%3ADiscord_Logo_sans_texte.svg&psig=AOvVaw2MYrtnZm5aGN_pF2wCO81Q&ust=1699280914478000&source=images&cd=vfe&opi=89978449&ved=0CBAQjRxqFwoTCNi-peyIrYIDFQAAAAAdAAAAABAD)
- Github (with mirroring) ![Alt text](https://www.google.com/url?sa=i&url=https%3A%2F%2Fwww.flaticon.com%2Ffree-icon%2Fgithub-logo_25231&psig=AOvVaw1BgVpACQNt0UznrtPdbCP-&ust=1699280954506000&source=images&cd=vfe&opi=89978449&ved=0CBAQjRxqFwoTCNDRrP-IrYIDFQAAAAAdAAAAABAD)

## Backend UML

![UML](doc/uml.png)

## Usage of website app

![Mobile](doc/uml.png)

## Usage of mobile app

![Mobile](doc/uml.png)

## Documentation
> Api Backend [link](https://documenter.getpostman.com/view/20779056/2s9YJc1hgo).

## Folder architecture

We have 3 subprojects in this repository:
- Server (under `Back`) which act as the master node of the application. It contains
  all the logic of the actions and reactions.
- Website front-end (under `Front/WEB`) which expose a website to interact with the AREA.
- Mobile front-end (under `Front/MOBILE`) which expose a mobile application to interact with
  the AREA.

## How to use

This project use __Docker__ and __docker-compose__ to be served:

```bash
$ docker-compose up -d --build
```

If you don't have docker, you can follow theses instructions :

In the folder /Back/:
```bash
$ php artisan serve
```

In the folder /Front/WEB/:
```bash
$ npm run dev   
```

### Made by:  
- [Gautier Bonhur](https://github.com/gautier337) - Back-end developer  
- [Rémi Maigrot](https://github.com/RemiMaigrot) - Back-end developer    
- [Max Peixoto](https://github.com/MaxPeix) - Web client developer  
- [Hugo Dubois](https://github.com/Hugo-code-dev) - Web client developer / Mobile client developer 
- [Timoté Franck](https://github.com/NameIsDume) - Mobile client developer  