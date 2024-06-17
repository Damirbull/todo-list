<template>
    <div>
      <select v-model="selectedPriority" @change="filterTasks">
        <option value="">All</option>
        <option value="Low">Low</option>
        <option value="Medium">Medium</option>
        <option value="High">High</option>
      </select>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      selectedPriority: {
        type: String,
        required: true,
      },
    },
    methods: {
      filterTasks(event) {
        this.$emit('filter', event.target.value);
      },
    },
  };
  </script>
  