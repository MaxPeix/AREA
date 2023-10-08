<template>
  <div class="section full-height full-width" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <!-- Utilisation de 'columns' et 'column' pour centrer la carte -->
    <div class="columns is-centered">
      <div class="column is-one-third">
        <div class="card">
          <div class="card-content">
            <div class="content is-size-4 has-text-weight-bold" v-if="!loading">
              <p v-for="item in area" :key="item.id">{{ item.name }}</p>
            </div>
            <div class="content" v-if="!loading">
              <b-switch v-model="area[0].activated" :disabled="areaupdating" v-if="!loading" @input="updateAreaActivation" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="box has-background-success columns">
      <div class="column is-half">
        <h1 class="title is-4 has-text-weight-bold" v-if="!loading">Current name: {{ area[0].name }}</h1>
        <input class="input is-size-4" v-model="nameInput" placeholder="Saisissez le nouveau nom ici" v-if="!loading" />
      </div>
      <div class="column is-half">
        <h1 class="title is-4 has-text-weight-bold" v-if="!loading">Current description: {{ area[0].description }}</h1>
        <input class="input is-size-4" v-model="descriptionInput" placeholder="Saisissez la nouvelle description ici" v-if="!loading" />
      </div>
    </div>

    <div class="box has-background-success">
      <p class="title is-4 has-text-centered has-text-weight-bold">Logs</p>
      <div v-if="!loading">
        <div v-for="(log, index) in area[0].historique" :key="index">
          <p class="subtitle is-4 has-text-centered has-text-weight-bold">{{ log.informations_random }} - {{ log.created_at }}</p>
        </div>
      </div>
    </div>

    <div v-if="loading" class="has-text-centered is-size-4 has-text-weight-bold">
      Chargement en cours...
    </div>

    <div class="buttons is-centered">
      <b-button label="Update" type="is-primary" @click="updateArea" />
      <b-button type="is-danger" @click="deleteArea">Delete the area</b-button>
      <b-button type="is-info" @click="movetohome">Back to home</b-button>
    </div>

    <b-loading :is-full-page="true" v-model="loading" :can-cancel="false"></b-loading>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import { arrow } from '../assets/index';
import axios from 'axios';

export default {
  name: 'AreaEditor',
  props: ['id'],
  data() {
    return {
      arrow,
      backgroundColor: themes.light.backgroundColor,
      area: [],
      loading: true,
      nameInput: "",
      descriptionInput: "",
      areaupdating: false
    };
  },
  computed: {
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
    this.getArea();
  },
  methods: {
    test() {
      console.log(area[0].activated);
    },
    movetohome() {
      this.$router.push('/home');
    },
    getArea() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return; // Arrêter la fonction si le token n'est pas disponible
      }
      axios.get('http://localhost:8000/api/area/' + this.id, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then((response) => {
        console.log('Réponse du serveur :', response.data);
        this.area = response.data;
      })
      .catch((error) => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        this.loading = false;
      });
    },
    updateAreaActivation() {
      this.areaupdating = true;
      const token = localStorage.getItem('token');
      axios.put('http://localhost:8000/api/area/' + this.id, {
        activated: this.area[0].activated,
      }, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then((response) => {
        console.log('Réponse du serveur :', response.data)
        this.$buefy.snackbar.open({
          message: this.area[0].name + ' a été ' + (this.area[0].activated ? 'activé' : 'désactivé'),
          type: this.area[0].activated ? 'is-success' : 'is-danger',
        });
      })
      .catch((error) => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        this.areaupdating = false;
      });
    },
    updateArea() {
      const token = localStorage.getItem('token');
      axios.put('http://localhost:8000/api/area/' + this.id, {
        name: this.nameInput,
        description: this.descriptionInput,
        activated: this.area[0].activated,
      }, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then((response) => {
        console.log('Réponse du serveur :', response.data);
      })
      .catch((error) => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
      });
    },
    deleteArea() {
      if (confirm("Êtes-vous sûr de vouloir supprimer cet élément ?")) {
        const token = localStorage.getItem('token');
        axios.delete('http://localhost:8000/api/area/' + this.id, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          console.log('Réponse du serveur :', response.data);
        })
        .catch((error) => {
          console.error('Erreur lors de la récupération des tâches :', error);
        })
        .finally(() => {
        });
      }
    },
  }
};
</script>

<style scoped>

.full-height {
  min-height: 100vh; /* vh représente les Viewport Heights, 100vh indique 100% de la hauteur du viewport */
}

.full-width {
  width: 100%;
}

/* Assure que les inputs ne prennent pas toute la largeur de l'écran */
.input.is-size-4 {
  max-width: 100%;
  box-sizing: border-box;
}
</style>