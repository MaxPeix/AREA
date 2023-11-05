# Utilisez une image de base
FROM alpine

# Créez un répertoire pour les fichiers partagés
RUN mkdir -p /shared

# Copiez les fichiers partagés dans le répertoire du conteneur
COPY ./Shared /shared

# Commande par défaut, ce conteneur ne fait rien d'autre que copier les fichiers partagés
CMD ["sleep", "infinity"]
