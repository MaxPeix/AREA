<template>
  <form action="">
      <div class="modal-card" style="width: 700px">
          <header class="modal-card-head">
              <p class="modal-card-title">Create an area</p>
          </header>
          <section class="modal-card-body">
              <b-field label="Name">
                <b-input v-model="name" type="name" placeholder="Name of the area" required></b-input>
              </b-field>
              <b-field label="Description">
                <b-input v-model="description" type="description" placeholder="Description of the area" required></b-input>
              </b-field>
              <b-field label="Action">
                <b-select v-model="selectedAction" placeholder="Select an action">
                  <option v-for="service in services" :value="service" v-if="service.service_name.includes('[ACTION]')">{{ service.service_name }}</option>
                </b-select>
              </b-field>
              <b-field label="Action Option 1" v-if="selectedAction && selectedAction.options[0]">
                <b-input v-model="selectedActionOpt1" type="selectedActionOpt1" :placeholder="actionOption1Placeholder" required></b-input>
              </b-field>
              <b-field label="Action Option 2" v-if="selectedAction && selectedAction.options[1]">
                <b-input v-model="selectedActionOpt2" type="selectedActionOpt2" :placeholder="actionOption2Placeholder" required></b-input>
              </b-field>
              <b-field label="Reactions">
                <b-select v-model="selectedReaction" placeholder="Select a reaction">
                  <option v-for="service in services" :value="service" v-if="service.service_name.includes('[REACTION]')">{{ service.service_name }}</option>
                </b-select>
              </b-field>
              <b-field label="Reaction Option 1" v-if="selectedReaction && selectedReaction.options[0]">
                <b-input v-model="selectedReactionOpt1" type="selectedReactionOpt1" :placeholder="reactionOption1Placeholder" required></b-input>
              </b-field>
              <b-field label="Reaction Option 2" v-if="selectedReaction && selectedReaction.options[1]">
                <b-input v-model="selectedReactionOpt2" type="selectedReactionOpt2" :placeholder="reactionOption2Placeholder" required></b-input>
              </b-field>
          </section>
          <footer class="modal-card-foot">
            <b-button
              label="Close"
              type="is-danger"
              @click="closeModal"
              v-if="canClose"
            />
            <b-button
              label="Create"
              type="is-primary"
              :loading="loading"
              @click="createArea" />
          </footer>
      </div>
  </form>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    canClose: Boolean,
  },
  mounted() {
    const token = localStorage.getItem('token');
    if (!token) {
      this.$router.push('/login');
    }
    this.getServices();
  },
  data() {
    return {
      name: null,
      description: null,
      selectedAction: null,
      selectedActionOpt1: null,
      selectedActionOpt2: null,
      selectedReaction: null,
      selectedReactionOpt1: null,
      selectedReactionOpt2: null,
      services: [],
      loading: false,
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    createArea() {
      const token = localStorage.getItem('token');
      if (!token) {
        this.$router.push('/login');
        return;
      }
      this.loading = true;
      axios.post('http://localhost:8000/api/area', {
        name: this.name,
        description: this.description,
        service_action_id: this.selectedAction.id,
        service_reaction_id: this.selectedReaction.id,
        config: [
          this.selectedActionOpt1 ?? "",
          this.selectedActionOpt2 ?? "",
          this.selectedReactionOpt1 ?? "",
          this.selectedReactionOpt2 ?? "",
        ],
        activated: true
      }, {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      })
      .then(response => {
        console.log(response.data);
        this.$buefy.notification.open({
          message: 'Area created',
          type: 'is-success',
        });
        this.closeModal();
      })
      .catch(error => {
        this.$buefy.notification.open({
          message: error.response.data.message,
          type: 'is-danger',
        });
        console.log('Erreur lors de la création de l\'area :', error.response.data.message);
      })
      .finally(() => {
        this.loading = false;
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
        console.log('Réponse du serveur :', response.data);
        this.services = response.data;
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des services :', error);
      })
      .finally(() => {
      });
    },
    findServiceName(serviceId) {
      const service = this.services.find(s => s.id === serviceId);
      return service ? service.service_name : 'Service non trouvé';
    },
  },
  computed: {
    actionOption1Placeholder() {
      return this.selectedAction ? this.selectedAction.options[0] : 'Action Option 1';
    },
    actionOption2Placeholder() {
      return this.selectedAction ? this.selectedAction.options[1] : 'Action Option 2';
    },
    reactionOption1Placeholder() {
      return this.selectedReaction ? this.selectedReaction.options[0] : 'Reaction Option 1';
    },
    reactionOption2Placeholder() {
      return this.selectedReaction ? this.selectedReaction.options[1] : 'Reaction Option 2';
    },
  },
}
</script>