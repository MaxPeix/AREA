<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="columns">
      <div class="column column1 scrollable-area" :style="{ backgroundColor: currentTheme.bloc }">Current Areas
        <div class="card" :style="{ backgroundColor: currentTheme.buttons}" v-for="(area, index) in areas" :key="index">
          <div class="card-content">
            <div class="card-header"> <!-- Nouvelle div pour le texte "Area" -->
              <p class="area-text">{{ area }}</p>
            </div>
            <div class="card-footer">
              <b-switch :value="true" class="small-success-button">
              </b-switch>
            </div>
          </div>
        </div>
        <div class="card" :style="{ backgroundColor: currentTheme.buttons}">
          <div class="card-content card-plus">+</div>
        </div>
      </div>
      <div class="column is-three-fifths">
        <div class="center" @click="movetotasks">
          <img class="logo" :src="currentLogo" />
          <div class="text_areas" :style="{ color: currentTheme.buttons }">See my areas</div>
        </div>
      </div>
      <div class="column">  
        <div></div>
      </div>
    </div>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import logo_bleu from '../components/icons/logo_bleu.png';
import logo_vert from '../components/icons/logo_vert.png';
import logo_gris from '../components/icons/logo_gris.png';
import defaultpfp from '../assets/default_pfp.png';

export default {
    name: 'Home',
    data() {
      return {
        defaultpfp,
        logo_bleu,
        logo_vert,
        logo_gris,
        backgroundColor: themes.default.backgroundColor,
        areas: ["Area 1", "Area 2", "Area 3", "area 5", "area 6", "areaaaa", "etsufhs"]
      };
    },
    computed: {
    currentLogo() {
        if (this.backgroundColor === themes.default.backgroundColor) {
          return this.logo_bleu;
        } else if (this.backgroundColor === themes.light.backgroundColor) {
          return this.logo_vert;
        } else if (this.backgroundColor === themes.dark.backgroundColor) {
          return this.logo_gris;
        } else {
          return this.logo_bleu;
        }
    },
    currentTheme() {
        if (this.backgroundColor === themes.default.backgroundColor) {
          return themes.default;
        } else if (this.backgroundColor === themes.light.backgroundColor) {
          return themes.light;
        } else if (this.backgroundColor === themes.dark.backgroundColor) {
          return themes.dark;
        } else {
          return themes.default;
        }
      },
    },
    methods: {
        movetotasks() {
        this.$router.push('/tasks');
        },
    }
};
</script>

<style scoped>

.center {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  height: 100%;
}

.wrapper {
  /* width: 100%; */
  height: 102vh;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  font-size: 64px;
}

.custom-font {
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
  font-size: 64px;
}
.logo {
  width: 200px;
  height: 200px;
}

/* Positionner le bouton en bas à gauche */
.card-footer {
  text-align: left;
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 8px; /* Espace autour du bouton */
}

/* Reduire la taille de la carte */
.card {
  margin: 10px;
  padding: 20px;
  border-radius: 16px; /* Coins arrondis de la carte */
  width: 80%;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 40px; /* Espace sous le texte "Area" */
  margin-top: -40px;
  margin-left: -30px;
}

.small-success-button {
  font-size: 30px; /* Ajustez la taille de la police selon vos préférences */
  padding: 2px 4px; /* Ajustez le rembourrage selon vos préférences */
}

.area-text {
  font-size: 22px; /* Ajustez la taille de la police selon vos préférences */
  margin-bottom: 4px; /* Espace sous le texte "Area" */
}

.column {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.column1  {
  margin-top: 15px;
  margin-left: 20px;
  font-size: 32px;
  color: #000;
  border-radius: 24px;
  font-weight: bold;
}

.scrollable-area {
  overflow-y: auto;
  max-height: 100vh; /* Ajustez la hauteur maximale selon vos besoins */
}

/* Cacher la scrollbar dans WebKit (Chrome et Safari) */
.scrollable-area::-webkit-scrollbar {
  width: 0.1em; /* Largeur de la scrollbar minimale */
}

.scrollable-area::-webkit-scrollbar-thumb {
  background-color: transparent; /* Couleur du bouton de défilement transparente */
}

.card-plus {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 36px; /* Adjust the font size as needed */
  padding: 12px; /* Adjust the padding as needed */
  border-radius: 16px; /* Adjust the border radius to match other cards */
}

</style>
