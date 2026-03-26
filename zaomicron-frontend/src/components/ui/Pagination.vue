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
<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 30px;
}
.pagination-item {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border: 1px solid #e0e0e0;
  border-radius: 4px;
  text-decoration: none;
  color: #333;
  transition: all 0.2s;
}
.pagination-item.active {
  background: #51AE45;
  border-color: #51AE45;
  color: white;
}
.pagination-item:hover:not(.active) {
  background: #f5f7fa;
}
</style>