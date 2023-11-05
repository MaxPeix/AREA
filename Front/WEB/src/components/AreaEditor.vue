<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="movetohome"/>
    <div class="card" :style="{ backgroundColor: currentTheme.buttons }">
      <div class="card-content">
        <div class="card-header" v-if="!loading">
          <p class="header-font" v-for="item in area" :key="item.id">{{ item.name }}</p>
        </div>
        <div class="card-footer" v-if="!loading">
          <b-switch v-model="area[0].activated" class="small-success-button" :disabled="areaupdating" v-if="!loading" @input="updateAreaActivation"/>
        </div>
        <p class="subheader-font" v-if="!loading && area && area[0] && area[0].action[0] && area[0].action[0].services && area[0].action[0].services.service_name"> Action : {{ area[0].action[0].services.service_name }} </p>
        <p class="subheader-font" v-if="!loading && area && area[0] && area[0].action[0] && area[0].action[0].reactions[0] && area[0].action[0].reactions[0].services && area[0].action[0].reactions[0].services.service_name"> Reaction : {{ area[0].action[0].reactions[0].services.service_name }} </p>
      </div>
    </div>
    <div class="action-reaction-rectangle" :style="{ left:'20%', backgroundColor: currentTheme.bloc2 }">
      <button class="action-reaction-button header-font" v-if="!loading"> Current name: {{ area[0].name }} </button>
      <input
        class="action-reaction-input header-font"
        v-model="nameInput"
        placeholder="Saisissez le nouveau nom ici"
        v-if="!loading"
      />
    </div>
    <div class="action-reaction-rectangle" :style="{ left: '52%', backgroundColor: currentTheme.bloc2 }">
      <button class="action-reaction-button header-font" v-if="!loading"> Current description: {{ area[0].description }}</button>
      <input
        class="action-reaction-input header-font"
        v-model="descriptionInput"
        placeholder="Saisissez la nouvelle description ici"
        v-if="!loading"
      />
    </div>
    <div class="logs" :style="{ backgroundColor: currentTheme.bloc2 }">
      <p class="header-font" :style="{ alignItems: 'center', display: 'flex', justifyContent: 'center' }">Logs</p>
      <div v-if="!loading">
        <div v-for="(log, index) in area[0].historique" :key="index">
          <p class="header-font" :style="{ alignItems: 'center', display: 'flex', justifyContent: 'center' }">{{ log.informations_random }} - {{ log.created_at }}</p>
        </div>
      </div>
    </div>
    <div v-if="loading" class="loading-indicator">
      Chargement en cours...
    </div>
    <div class="update">
      <b-button
        label="Update"
        type="is-primary"
        @click="updateArea" />
    </div>
    <button class="delete-button" @click="deleteArea">Delete</button>
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
      backgroundColor: themes.default.backgroundColor,
      area: [],
      loading: true,
      nameInput: "",
      descriptionInput: "",
      areaupdating: false,
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
    const themeName = localStorage.getItem('theme');
    if (themeName && themes[themeName]) {
      this.backgroundColor = themes[themeName].backgroundColor;
    } else {
      this.backgroundColor = themes.default.backgroundColor;
    }
  },
  methods: {
    updateAreaActivation() {
      this.areaupdating = true;
      const token = localStorage.getItem('token');
      axios.put('http://localhost:8080/api/area/' + this.id, {
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
    movetohome() {
      this.$router.push('/home');
    },
    getArea() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8080/api/area/' + this.id, {
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
    updateArea() {
      const token = localStorage.getItem('token');
      axios.put('http://localhost:8080/api/area/' + this.id, {
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
        this.movetohome();
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
        axios.delete('http://localhost:8080/api/area/' + this.id, {
          headers: {
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          console.log('Réponse du serveur :', response.data);
          this.movetohome();
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

/* Base styling */
.wrapper {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  height: 100vh;
  background-color: #F0F0F0;
  padding-top: 20px;
}

.header-font, .subheader-font {
  color: #333;
}

/* Arrow styling */
/* Arrow styling */
.arrow {
  position: absolute;
  top: 20px;
  left: 20px;
  width: 50px;
  cursor: pointer;
  transform: rotate(180deg);
}

/* Card styling */
.card {
  background-color: #FFFFFF;
  border-radius: 8px;
  padding: 20px;
  width: 80%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

.card-header, .card-footer {
  box-shadow: none;
  border: none;
  display: flex;
  justify-content: space-between;
}

/* Switch button */
.small-success-button {
  font-size: 1rem;
}

/* Action-Reaction styling */
.action-reaction-rectangle {
  border-radius: 8px;
  padding: 20px;
  width: 80%;
  margin-bottom: 20px;
}

.action-reaction-button, .action-reaction-input {
  width: 100%;
  padding: 10px;
  margin: 5px 0;
  border: 1px solid #ccc;
  border-radius: 4px;
}

/* Logs styling */
.logs {
  border-radius: 8px;
  padding: 20px;
  width: 80%;
  height: 300px;
  overflow-y: auto;
  margin-bottom: 20px;
}

/* Loading and Update Button */
.loading-indicator, .update {
  margin-top: 20px;
}

/* Delete Button */
.delete-button {
  background-color: #FF4C4C;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 20px;
}

</style>
