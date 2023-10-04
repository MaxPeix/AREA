<template>
  <div class="wrapper" :style="{ backgroundColor: currentTheme.backgroundColor }">
    <img class="arrow" :src="arrow" @click="movetohome"/>
    <section>
      <b-button
          label="Create an area"
          type="is-primary"
          size="is-medium"
          @click="isComponentModalActive = true" 
          class="create-area-rectangle"/>
          <b-modal
          v-model="isComponentModalActive"
          has-modal-card
          trap-focus
          :destroy-on-hide="false"
          aria-role="dialog"
          aria-label="Example Modal"
          close-button-aria-label="Close"
          aria-modal
      >
          <template #default="props">
            <modal-form :form-data="formProps" :actions="actions" :reactions="reactions" @close="isComponentModalActive = false" @create="createArea"></modal-form>
          </template>
      </b-modal>
    </section>
  </div>
</template>

<script>
import { themes } from '../themes/themes.js';
import { arrow } from '../assets/index';
import axios from 'axios';


const ModalForm = {
      props: ['formData', 'actions', 'reactions'],
      template: `
          <form action="">
              <div class="modal-card" style="width: 700px">
                  <header class="modal-card-head">
                      <p class="modal-card-title">Create an area</p>
                      <button
                          type="button"
                          class="delete"
                          @click="$emit('close')"/>
                  </header>
                  <section class="modal-card-body">
                      <b-field label="Name">
                        <b-input v-model="formData.name" type="name" placeholder="Name of the area" required></b-input>
                      </b-field>
                      <b-field label="Description">
                        <b-input v-model="formData.description" type="description" placeholder="Description of the area" required></b-input>
                      </b-field>
                      <b-field label="Action">
                        <b-select v-model="formData.selectedAction" placeholder="Select an action">
                          <option v-for="action in actions" :value="action">{{ action.serviceName }}</option>
                        </b-select>
                      </b-field>
                      <b-field label="Reaction">
                        <b-select v-model="formData.selectedReaction" placeholder="Select a reaction">
                          <option v-for="reaction in reactions" :value="reaction">{{ reaction.serviceName }}</option>
                        </b-select>
                      </b-field>
                  </section>
                  <footer class="modal-card-foot">
                      <b-button
                          label="Close"
                          @click="$emit('close')" />
                      <b-button
                          label="Create"
                          type="is-primary"
                          @click="$emit('create')" />
                  </footer>
              </div>
          </form>
      `,
}

export default {
  name: 'AreaCreator',
  components: {
          ModalForm
      },
  data() {
    return {
      arrow,
      backgroundColor: themes.light.backgroundColor,
      isComponentModalActive: false,
      formProps: {
          name: "",
          description: "",
          selectedAction: "",
          selectedReaction: "",
      },
      actions: ['Action 1', 'Action 2', 'Action 3'], // Remplacez par vos propres données
      reactions: ['Reaction 1', 'Reaction 2', 'Reaction 3'], // Remplacez par vos propres données
      services: [],
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
    this.getServices();
    this.getActions();
    this.getReactions();
  },
  methods: {
    movetohome() {
      this.$router.push('/home');
    },
    async createArea() {
      console.log(this.formProps.name);
      console.log(this.formProps.description);
      console.log(this.formProps.selectedAction);
      console.log(this.formProps.selectedReaction);
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      let area_new_id = "";
      let action_new_id = "";
      const apiUrl = 'http://localhost:8000/api/area';
      const requestData = {
        name: this.formProps.name,
        description: this.formProps.description,
        activated: "true"
      };
      try {
        /* Area */
        const response = await axios.post(apiUrl, requestData, {
          headers: {
              Authorization: `Bearer ${token}`
          },
        })
        console.log('Réponse du serveur :', response.data);
        area_new_id = response.data.id;

        /* Action */
        const apiUrlAction = 'http://localhost:8000/api/actions/' + area_new_id;
        const requestDataAction = {
          services_id: this.formProps.selectedAction.services_id,
          activated: "true"
        };
        console.log(apiUrlAction);

        const response2 = await axios.post(apiUrlAction, requestDataAction, {
          headers: {
              Authorization: `Bearer ${token}`
          },
        })
        console.log('Réponse du serveur 2 :', response2.data);
        action_new_id = response2.data.id;

        /* Reaction */
        const apiUrlReaction = 'http://localhost:8000/api/reactions/' + area_new_id;
        const requestDataReaction = {
          actions_id: action_new_id,
          services_id: this.formProps.selectedReaction.services_id,
          activated: "true"
        };
        const response3 = await axios.post(apiUrlReaction, requestDataReaction, {
          headers: {
              Authorization: `Bearer ${token}`
          },
        })
        console.log('Réponse du serveur 3 :', response3.data);
      } catch (error) {
        console.error(error)
      }
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
        console.log('Réponse du serveur :', response.data);
        this.services = response.data;
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    getActions() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      axios.get('http://localhost:8000/api/actions', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log('Réponse du serveur :', response.data);
        this.actions = response.data.map(action => ({
          ...action,
          serviceName: this.findServiceName(action.services_id),
        }));
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    getReactions() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return; // Arrêter la fonction si le token n'est pas disponible
      }
      axios.get('http://localhost:8000/api/reactions', {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log('Réponse du serveur :', response.data);
        this.reactions = response.data.map(reaction => ({
          ...reaction,
          serviceName: this.findServiceName(reaction.services_id),
        }));
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des tâches :', error);
      })
      .finally(() => {
        // Cacher le spinner de chargement
      });
    },
    findServiceName(serviceId) {
      const service = this.services.find(s => s.id === serviceId);
      return service ? service.service_name : 'Service non trouvé';
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

    section {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .create-area-rectangle {
      width: 300px;
      height: 150px;
      background-color: #9FCDA8;
      border-radius: 20px;
      align-items: center;
      display: flex;
      justify-content: center;
    }

  </style>