<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="movetohome"/>
    <div class="card" :style="{ backgroundColor: currentTheme.buttons }">
      <div class="card-content">
        <div class="card-header" v-if="!loading">
          <p class="header-font" v-for="item in area" :key="item.id">{{ item.name }}</p>
        </div>
        <div class="card-footer" v-if="!loading">
          <b-switch v-model="area[0].activated" class="small-success-button" v-if="!loading"></b-switch>
        </div>
      </div>
    </div>
    <div class="action-reaction-rectangle" :style="{ left:'20%' }">
      <button class="action-reaction-button header-font" v-if="!loading"> Current : {{ area[0].action[0].name }} </button>
      <b-select v-model="selectedAction" placeholder="Select an action" v-if="!loading">
        <option v-for="service in services" :value="service" v-if="service.service_name.includes('[ACTION]')">{{ service.service_name }}</option>
      </b-select>
    </div>
    <div class="action-reaction-rectangle" :style="{ left: '52%' }">
      <button class="action-reaction-button header-font" v-if="!loading"> Current : {{ area[0].action[0].reactions[0].services.service_name }}</button>
      <b-select v-model="selectedReaction" placeholder="Select a reaction" v-if="!loading">
        <option v-for="service in services" :value="service" v-if="service.service_name.includes('[REACTION]')">{{ service.service_name }}</option>
      </b-select>
    </div>
    <div class="logs">
      <p class="header-font" :style="{ alignItems: 'center', display: 'flex', justifyContent: 'center' }">Logs</p>
      <p class="header-font" :style="{ alignItems: 'center', display: 'flex', justifyContent: 'center' }" v-if="!loading"> {{ area[0].historique }}</p>
    </div>

    <!-- Écran de chargement -->
    <div v-if="loading" class="loading-indicator">
      Chargement en cours...
    </div>
    <div class="update">
      <b-button
        label="Update"
        type="is-primary"
        @click="updateArea" />
    </div>
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
      loading: true, // Ajout de la variable de chargement
      services: [],
      selectedAction: null,
      selectedReaction: null,
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
    this.getServices();
  },
  methods: {
    movetohome() {
      this.$router.push('/home');
    },
    getArea() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return; // Arrêter la fonction si le token n'est pas disponible
      }
      axios
        .get('http://localhost:8000/api/area/' + this.id, {
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
          this.loading = false; // Définir loading sur false lorsque la requête est terminée
        });
    },
    getServices() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8000/api/services', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        this.services = response.data;
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des services :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    updateArea() {

    },
  }
};
</script>

<style scoped>

.wrapper {
  width: 100%;
  height: 100vh;
  font-size: 32px;
  font-weight: bold;
  position: relative;
  padding-top: 100px;
}

.header-font {
  font-size: 32px;
  font-weight: bold;
  color: black;
}

.arrow {
  position: absolute;
  top: 0;
  margin-left: 60px;
  width: 100px;
  height: 100px;
  padding: 20px;
  transform:rotate(180deg);
  cursor: pointer;
}

.card-footer {
  text-align: left;
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 8px;
}

.card {
  margin: 10px;
  padding: 20px;
  margin-left: 75px;
  border-radius: 16px;
  width: 300px;
  height: 180px;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-left: -30px;
  box-shadow: none;
}

.small-success-button {
  font-size: 30px;
  padding: 2px 4px;
}

.action-reaction-rectangle {
  position: absolute;
  top: 50%;
  width: 500px;
  height: 300px;
  background-color: #9FCDA8;
  border-radius: 20px;
  align-items: center;
  display: flex;
  justify-content: center;
}

.action-reaction-button {
  background-color: transparent;
  border: none;
  cursor: pointer;
}

.logs {
  position: absolute;
  top: 20px;
  right: 15px;
  width: 300px;
  height: 700px;
  background-color: #9FCDA8;
  border-radius: 16px;
}

.loading-indicator {
  font-size: 24px;
  font-weight: bold;
  text-align: center;
  margin-top: 20px;
}

.update {
  position: absolute;
  top: 90%;
  left: 49%;
  transform: translate(-50%, -50%);
}

</style>