<template>

  <div>
    <filter-component
      :selectedPriority="selectedPriority"
      @filter="handleFilter"
    ></filter-component>

    <div v-if="filteredTasks.length === 0" class="no-tasks-found">
      Ничего не найдено
    </div>
  <draggable>
      <v-card
        class="mx-auto mb-4"
        width="344"
        hover
        v-for="task in filteredTasks"
        :key="task.id"
        @click="openModal(task.id)"
      >
      <v-card-title>{{ task.title }}</v-card-title>
      <v-card-subtitle>{{ task.priority }}</v-card-subtitle>
      <v-card-text>{{ task.description }}</v-card-text>
      <v-card-actions>
        <v-btn color="blue darken-1" @click.stop="deleteTask(task.id)">Удалить</v-btn>
        <v-btn style="margin-left: 85px;" color="blue darken-1" @click.stop="openEditDialog(task.id)">Редактировать</v-btn>
      </v-card-actions>
    </v-card>
    </draggable>
    <task-modal
      v-if="selectedTaskId !== null"
      :task-id="selectedTaskId"
      :key="`task-modal-${selectedTaskId}`"
      ref="taskModal"
      @close="handleModalClose"
    ></task-modal>

    <modal-component
      ref="modalComponent"
      :column="column"
      @task-created="handleTaskCreated"
    ></modal-component>

    <edit-task-component
      v-if="selectedTaskId !== null"
      :task-id="selectedTaskId"
      :key="`edit-task-${selectedTaskId}`"
      ref="editTaskComponent"
      @close="handleEditDialogClose"
      @task-updated="handleTaskUpdated"
    ></edit-task-component>
  </div>
</template>
<script>
import ModalComponent from './ModalCreatComponent.vue';
import FilterComponent from './FilterComponent.vue';
import TaskModal from './ModalDetalComponent.vue';
import EditComponent from './EditComponent.vue';
import draggable from 'vuedraggable';
export default {
  props: {
    column: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      tasks: [],
      selectedPriority: '',
      selectedTaskId: null,
    };
  },
  mounted() {
    this.fetchTasks();
  },
  methods: {
    fetchTasks() {
      const url = this.selectedPriority ? `/filter?priority=${this.selectedPriority}` : '/task';

      fetch(url)
        .then(response => response.json())
        .then(data => {
          this.tasks = data;
        })
        .catch(error => {
          console.error('Error fetching tasks:', error);
        });
    },

    handleTaskCreated(newTask) {
      this.tasks.push(newTask);
      this.filterTasks();
      this.$refs.modalComponent.updateTaskStatus(this.column);
    },

    handleFilter(priority) {
      this.selectedPriority = priority;
      this.filterTasks();
    },

    filterTasks() {
      const url = this.selectedPriority ? `/filter?priority=${this.selectedPriority}` : '/task';

      fetch(url)
        .then(response => response.json())
        .then(data => {
          this.tasks = data;
        })
        .catch(error => {
          console.error('Error fetching tasks:', error);
        });
    },

    openModal(taskId) {
      this.selectedTaskId = taskId;
      this.$nextTick(() => {
        if (this.$refs.taskModal) {
          this.$refs.taskModal.openDialog();
        }
      });
    },

    handleModalClose() {
      this.selectedTaskId = null;
    },

    handleTaskUpdated(updatedTask) {
      const index = this.tasks.findIndex(task => task.id === updatedTask.id);
      if (index !== -1) {
        this.$set(this.tasks, index, updatedTask);
      } else {
        this.tasks.push(updatedTask);
      }
      this.filterTasks();
      this.selectedTaskId = null;
    },

    openEditDialog(taskId) {
      this.selectedTaskId = taskId;
      this.$nextTick(() => {
        if (this.$refs.editTaskComponent) {
          this.$refs.editTaskComponent.openDialog();
        }
      });
    },

    handleEditDialogClose() {
      this.selectedTaskId = null;
    },

    deleteTask(taskId) {
      if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/delete/${taskId}`, {
          method: 'GET',
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
          },
        })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(() => {
            this.fetchTasks();
          })
          .catch(error => {
            console.error('Error deleting task:', error);
          });
      }
    },
    
  },

  computed: {
    filteredTasks() {
      const priorityOrder = {
        'High': 1,
        'Medium': 2,
        'Low': 3,
      };

      return this.tasks
        .filter(task => task.status === this.column)
        .sort((a, b) => priorityOrder[a.priority] - priorityOrder[b.priority]);
    },
    
  },

  components: {
    'modal-component': ModalComponent,
    'filter-component': FilterComponent,
    'task-modal': TaskModal,
    'edit-task-component': EditComponent,
    'draggable': draggable,
  },
};
</script>
