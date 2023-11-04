# Utilisez une image de base contenant le SDK Swift
FROM swift:5.5

# Définissez le répertoire de travail
WORKDIR /app

# Copiez les fichiers de votre application mobile dans le conteneur
COPY . .

# Installez les dépendances de votre application Swift
RUN swift package resolve
RUN swift build --configuration release

# Exposez le port que votre application utilisera (si nécessaire)
EXPOSE 8080

# Démarrez votre application mobile Swift
CMD ["./YourMobileApp"]
