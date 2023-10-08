<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <div class="columns">
      <div class="column column1 scrollable-area">
        <div class="card" :style="{ backgroundColor: currentTheme.buttons}" v-for="(area, index) in areas" :key="index">
          <b-loading :is-full-page="false" v-model="area.isLoading" :can-cancel="true"></b-loading>
          <div class="card-content" v-if="!area.isLoading">
            <div class="card-header">
              <div class="header-container">
                <p class="area-text">{{ area.name }}</p>
              </div>
            </div>
            <!-- Boutons déplacés vers le card-content -->
            <div class="button-container">
              <b-button v-if="area.activated" type="is-success">
                ON
              </b-button>
              <b-button v-else type="is-danger">
                OFF
              </b-button>
              <b-button @click="moveToAreaEditor(area.id)" type="is-info">
                Edit
              </b-button>
            </div>
          </div>
        </div>
        <b-button class="card" :style="{ backgroundColor: currentTheme.buttons}" @click="moveToAreaCreator">
          <div class="card-content card-plus">+</div>
        </b-button>
      </div>
      <!-- <div class="column is-three-fifths">
        <div class="center" @click="moveToAreas">
        </div>
      </div> -->
      <div class="column">  
        <div></div>
      </div>
    </div>
    <img class="logo" :src="currentLogo" />
    <b-button class="pfp-container2" @click="moveToAreas"
      type="is-info"
    >
      See my areas
    </b-button>
    <b-button class="pfp-container" @click="moveToAccount" type="is-dark">
      My account
    </b-button>
    <!-- <b-loading :is-full-page="true" v-model="loadingareas" :can-cancel="true"></b-loading> -->
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
        loadingareas: false
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
            });
        },
        getAreas() {
          const token = localStorage.getItem('token');
          if (!token) {
            this.$router.push('/login');
            return; // Arrêter la fonction si le token n'est pas disponible
          }
          this.loadingareas = true;
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
            this.loadingareas = false;
          });
        },
    }
};
</script>

<style scoped>

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.edit-button {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 5px 15px;
  border-radius: 5px;
  cursor: pointer;
}

.status-indicator {
  display: inline-block;
  padding: 3px 8px; /* taille réduite */
  border-radius: 5px;
  font-size: 12px; /* taille de texte réduite */
}

.active-status {
  background-color: green;
  color: white;
}

.inactive-status {
  background-color: #FF7F7F; /* rouge moins agressif */
  color: white;
}

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
  position: absolute;
  width: 40%;
  height: 60%;
  top: 50px;
  right: 80px;
  margin: 10px;
  z-index: 10; /* Assurez-vous qu'il est au-dessus des autres éléments, si nécessaire */
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
  width: 60%; /* Réduction de la largeur */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Ajout d'une petite ombre pour plus de profondeur */
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
  margin: 20px;
}

.pfp-container2 {
  position: absolute;
  bottom: 60px;
  right: 0;
  margin: 20px;
}

.pfp {
  width: 100px;
  height: 100px;
}

</style>
