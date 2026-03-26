<template>
  <nav class="pagination">
    <a
      v-for="page in pages"
      :key="page"
      class="pagination-item"
      :class="{ active: page === currentPage }"
      href="#"
      @click.prevent="$emit('page-changed', page)"
    >{{ page }}</a>
    <a href="#" class="pagination-item" @click.prevent="$emit('page-changed', currentPage + 1)">→</a>
  </nav>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    currentPage: Number,
    totalPages: Number
  },
  computed: {
    pages() {
      const pages = [];
      for (let i = 1; i <= this.totalPages; i++) {
        pages.push(i);
      }
      return pages;
    }
  }
};
</script>