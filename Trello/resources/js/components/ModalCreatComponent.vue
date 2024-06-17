<template>
  <v-dialog v-model="dialog" max-width="600px">
    <template v-slot:activator="{ on }">
      <v-btn color="primary" dark v-on="on">Добавить задачу</v-btn>
    </template>
    <v-card>
      <v-card-title>Create Task in {{ column }}</v-card-title>
      <v-card-text>
        <form @submit.prevent="createTask">
          <div>
            <label for="title">Title:</label><br>
            <input type="text" v-model="task.title" id="title" name="title">
          </div>
          <div v-if="errors.title" class="alert alert-danger">{{ errors.title[0] }}</div>

          <div>
            <label for="description">Description:</label><br>
            <textarea v-model="task.description" id="description" name="description"></textarea>
          </div>
          <div v-if="errors.description" class="alert alert-danger">{{ errors.description[0] }}</div>

          <div>
            <label for="priority">Priority:</label><br>
            <select v-model="task.priority" id="priority" name="priority">
              <option value="Low">Low</option>
              <option value="Medium" selected>Medium</option>
              <option value="High">High</option>
            </select>
          </div>

          <div>
            <button type="submit">Create Task</button>
          </div>
          <div v-if="successMessage" class="alert alert-success">{{ successMessage }}</div>
        </form>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    column: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      dialog: false,
      task: {
        title: '',
        description: '',
        priority: 'Medium',
      },
      errors: {},
      successMessage: '',
    };
  },
  methods: {
    async createTask() {
      try {
        const response = await axios.post('/tasks', {
          title: this.task.title,
          description: this.task.description,
          priority: this.task.priority,
          status: this.column,
        });
        this.task.title = '';
        this.task.description = '';
        this.successMessage = "Task created successfully!";
        this.errorMessage = '';
        this.errors = '';
        const newTask = response.data; 
        this.$emit('task-created', newTask); 
        this.updateTaskStatus(this.column);
        this.successMessage = 'Task created successfully!';
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.successMessage = "";
          this.errors = error.response.data.errors;
        } else {
          console.error('An error occurred while creating the task:', error);
        }
      }
    },
    updateTaskStatus(column) {

      console.log('Updating task status for column:', column);

    },
  },
};
</script>
