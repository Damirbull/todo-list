<template>
  <v-dialog v-model="dialog" max-width="600px">
    <v-card v-if="task">
      <v-card-title>{{ task.title }}</v-card-title>
      <v-card-subtitle>{{ task.priority }}</v-card-subtitle>
      <v-card-text>{{ task.description }} <br>
        {{ formatDate(task.created_at) }}
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="closeDialog">Закрыть</v-btn>
      </v-card-actions>
    </v-card>
    <v-card v-else>
      <v-card-title>Задача не найдена</v-card-title>
      <v-card-text>Проверьте правильность идентификатора задачи.</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="closeDialog">Закрыть</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
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
      task: null,
    };
  },
  methods: {
    openDialog() {
      this.fetchTaskDetails();
    },
    closeDialog() {
      this.dialog = false;
      this.$emit('close'); // Посылаем событие close в родительский компонент
    },
    fetchTaskDetails() {
      fetch(`/task/${this.taskId}`)
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          this.task = data;
          this.dialog = true; // Открываем модальное окно после успешной загрузки данных
        })
        .catch(error => {
          console.error('Error fetching task:', error);
          // Обработка ошибки загрузки задачи
        });
    },
    formatDate(dateString) {
      // Пример простого форматирования даты
      return new Date(dateString).toLocaleDateString(); // или другой способ форматирования по вашему выбору
    },
  },
};
</script>
