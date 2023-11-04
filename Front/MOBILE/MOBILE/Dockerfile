# Utilisez une image de base contenant le SDK Swift
FROM swift:5.5

# Définissez le répertoire de travail
WORKDIR /app

# Copiez les fichiers de votre application mobile dans le conteneur
COPY . .

# Installez les dépendances de votre application Swift
RUN swift package resolve
RUN swift build --configuration release

# Démarrez votre application mobile Swift
CMD ["./MOBILE"]
