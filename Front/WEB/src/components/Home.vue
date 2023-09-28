<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="columns">
      <div class="column">
        <div class="card" v-for="(area, index) in areas" :key="index">
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
        <div class="card">
          <div class="card-content">+</div>
        </div>
      </div>
      <div class="column is-three-fifths">
        <div class="center">
          <img class="logo" :src="currentLogo" />
          <div>See my areas</div>
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
				backgroundColor: themes.light.backgroundColor,
                areas: ["Area 1", "Area 2", "Area 3", "area 5", "area 6"]
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

.on_left {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100vh;
  color: #FFF;
  font-family: Inter;
  font-size: 64px;
}

.wrapper {
  /* width: 100%; */
  height: 100vh;
  color: #FFF;
  font-family: Inter;
  font-size: 64px;
}
.logo {
  width: 200px;
  height: 200px;
}

.pfp {
  width: 61px;
  height: 61px;
  border-radius: 71px;
  margin-bottom: 20px;
	margin-left: auto;
	margin-right: 20px;
}

.scrollable-column {
  height: 100%;
  overflow-y: auto;
}

/* Modifier la taille du bouton Success */
.is-success {
  background-color: #28a745; /* Couleur de fond du bouton success */
  color: #fff; /* Couleur du texte */
  font-size: 12px; /* Taille de la police */
  padding: 4px 8px; /* Rembourrage du bouton */
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
  background-color: #fff;
  margin: 10px;
  padding: 20px;
  border-radius: 5px; /* Coins arrondis de la carte */
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 40px; /* Espace sous le texte "Area" */
  margin-top: -40px;
  margin-left: -30px;
  font-weight: bold; /* Style du texte "Area" en gras, si désiré */
}

.small-success-button {
  font-size: 30px; /* Ajustez la taille de la police selon vos préférences */
  padding: 2px 4px; /* Ajustez le rembourrage selon vos préférences */
}

.area-text {
  font-size: 44px; /* Ajustez la taille de la police selon vos préférences */
  margin-bottom: 4px; /* Espace sous le texte "Area" */
}

</style>
