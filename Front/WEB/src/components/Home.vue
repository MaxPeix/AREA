<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="columns">
      <div class="column column1 scrollable-area" :style="{ backgroundColor: currentTheme.bloc }">Current Areas
        <div class="card" :style="{ backgroundColor: currentTheme.buttons}" v-for="(area, index) in areas" :key="index">
          <div class="card-content">
            <div class="card-header" @click="moveToAreaEditor(area.id)">
              <p class="area-text">{{ area.name }}</p>
            </div>
            <div class="card-footer">
              <b-switch v-model="area.activated" :disabled="areaupdating" class="small-success-button" @input="updateAreaActivation(area)"/>
            </div>
          </div>
        </div>
        <div class="card" :style="{ backgroundColor: currentTheme.buttons}" @click="moveToAreaCreator">
          <div class="card-content card-plus">+</div>
        </div>
      </div>
      <div class="column is-three-fifths">
        <div class="center" @click="moveToAreas">
          <img class="logo" :src="currentLogo" />
          <div class="text_areas" :style="{ color: currentTheme.buttons }">See my areas</div>
        </div>
      </div>
      <div class="column">  
        <div></div>
      </div>
    </div>
    <div class="pfp-container">
      <img :src="defaultpfp" class="pfp" @click="moveToAccount"/>
    </div>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import logo_bleu from '../components/icons/logo_bleu.png';
import logo_vert from '../components/icons/logo_vert.png';
import logo_gris from '../components/icons/logo_gris.png';
import defaultpfp from '../assets/default_pfp.png';
import axios from 'axios';
import AreaCreatorForm from './AreaCreatorForm.vue';

export default {
    name: 'Home',
    props: ['areas'],
    components: {
      AreaCreatorForm,
    },
    data() {
      return {
        defaultpfp,
        logo_bleu,
        logo_vert,
        logo_gris,
        backgroundColor: themes.default.backgroundColor,
        canClose: true,
        areaupdating: false,
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
    mounted() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
      }
      this.getAreas();
    },
    methods: {
      updateAreaActivation(area) {
        this.areaupdating = true;
        const token = localStorage.getItem('token');
        axios.put('http://localhost:8000/api/area/' + area.id, {
          activated: area.activated,
        }, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          console.log('Réponse du serveur :', response.data)
          this.$buefy.snackbar.open({
            message: area.name + ' a été ' + (area.activated ? 'activé' : 'désactivé'),
            type: area.activated ? 'is-success' : 'is-danger',
          });
          this.getAreas();
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des tâches :', error);
        })
        .finally(() => {
          this.areaupdating = false;
        });
      },
      moveToAreas() {
        this.$router.push({ name: 'areas', params: { areas: this.areas } });
      },
      moveToAccount() {
        this.$router.push('/account');
      },
      moveToAreaEditor(areaId) {
        this.$router.push({ name: 'areaeditor', params: { id: areaId } });
      },
      moveToAreaCreator() {
        console.log("opening modal")
        this.canClose = true;
        this.$buefy.modal.open({
            parent: this,
            component: AreaCreatorForm,
            hasModalCard: true,
            props : {
              canClose: this.canClose,
            },
          }).$on('close', () => {
            this.canClose = false;
            this.getAreas();
          });
      },
      getAreas() {
        const token = localStorage.getItem('token');
        if (!token) {
          this.$router.push('/login');
          return; // Arrêter la fonction si le token n'est pas disponible
        }
        axios.get('http://localhost:8000/api/area', {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then(response => {
          console.log('Réponse du serveur :', response.data);
          this.areas = response.data;
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des tâches :', error);
        })
        .finally(() => {
          // Cacher le spinner de chargement
        });
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
  box-shadow: none;
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

.pfp-container {
  position: absolute;
  bottom: 0;
  right: 0;
  margin: 20px; /* Marge pour un espacement du bord de la page */
}

.pfp {
  width: 100px;
  height: 100px;
}

</style>
