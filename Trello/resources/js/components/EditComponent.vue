<template>
  <v-dialog v-model="dialog" max-width="600px">
    <v-card>
      <v-card-title>Редактировать задачу</v-card-title>
      <v-card-text>
        <v-form ref="form">
          <v-text-field
            v-model="task.title"
            label="Название"
            :rules="[v => !!v || 'Название обязательно']"
          ></v-text-field>
          <v-textarea
            v-model="task.description"
            label="Описание"
            :rules="[v => !!v || 'Описание обязательно']"
          ></v-textarea>
          <v-select
            v-model="task.priority"
            :items="['Low', 'Medium', 'High']"
            label="Приоритет"
            :rules="[v => !!v || 'Приоритет обязателен']"
          ></v-select>
          <v-select
            v-model="task.status"
            :items="['To Do', 'In Progress', 'Done']"
            label="Статус"
            :rules="[v => !!v || 'Статус обязателен']"
          ></v-select>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="saveTask">Сохранить</v-btn>
        <v-btn style="margin-left: 85px;" color="grey" @click="closeDialog">Отмена</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    taskId: {
      type: Number,
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
        status: 'To Do',
      },
    };
  },
  methods: {
    async openDialog() {
      this.dialog = true;
      await this.fetchTaskDetails();
    },
    closeDialog() {
      this.dialog = false;
      this.resetTask();
      this.$emit('close');
    },
    async fetchTaskDetails() {
      try {
        const response = await axios.get(`/task/${this.taskId}`);
        this.task = response.data;
      } catch (error) {
        console.error('Ошибка получения данных задачи:', error);
      }
    },
    async saveTask() {
      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const response = await axios.post(`/update/${this.taskId}`, this.task, {
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
          },
        });

        this.$emit('task-updated', response.data);
        this.closeDialog();
      } catch (error) {
        console.error('Ошибка сохранения данных задачи:', error);
      }
    },
    resetTask() {
      this.task = {
        title: '',
        description: '',
        priority: 'Medium',
        status: 'To Do',
      };
    },
  },
};
</script>
