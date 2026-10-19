<template>
  <nav class="pagination" aria-label="Пагинация">
    <button
      class="pagination-item"
      :disabled="currentPage === 1"
      @click="changePage(1)"
      aria-label="Первая страница"
    >
      ««
    </button>
    <button
      class="pagination-item"
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
      aria-label="Предыдущая страница"
    >
      «
    </button>

    <template v-for="page in visiblePages" :key="page">
      <button
        v-if="page !== '...'"
        class="pagination-item"
        :class="{ active: page === currentPage }"
        :aria-current="page === currentPage ? 'page' : undefined"
        @click="changePage(page)"
      >
        {{ page }}
      </button>
      <span v-else class="pagination-ellipsis">…</span>
    </template>

    <button
      class="pagination-item"
      :disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
      aria-label="Следующая страница"
    >
      »
    </button>
    <button
      class="pagination-item"
      :disabled="currentPage === totalPages"
      @click="changePage(totalPages)"
      aria-label="Последняя страница"
    >
      »»
    </button>
  </nav>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    currentPage: {
      type: Number,
      required: true,
    },
    totalPages: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      windowWidth: window.innerWidth,
    };
  },
  computed: {
    delta() {
      return this.windowWidth < 600 ? 1 : 2;
    },
    visiblePages() {
      const total = this.totalPages;
      const current = this.currentPage;
      const delta = this.delta;
      const range = [];

      for (let i = 1; i <= total; i++) {
        if (i === 1 || i === total || (i >= current - delta && i <= current + delta)) {
          range.push(i);
        }
      }

      const result = [];
      let last = 0;
      for (const i of range) {
        if (last && i - last > 1) {
          result.push('...');
        }
        result.push(i);
        last = i;
      }
      return result;
    },
  },
  mounted() {
    window.addEventListener('resize', this.onResize);
  },
  beforeUnmount() {
    window.removeEventListener('resize', this.onResize);
  },
  methods: {
    onResize() {
      this.windowWidth = window.innerWidth;
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.$emit('page-changed', page);
      }
    },
  },
};
</script>

<style scoped>
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 6px;
  margin-top: 30px;
  flex-wrap: wrap;
}

.pagination-item {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  padding: 0 6px;
  border: 1px solid var(--color-border, #e0e0e0);
  border-radius: 4px;
  background: #fff;
  color: #333;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.pagination-item.active {
  background: var(--color-primary, #51ae45);
  border-color: var(--color-primary, #51ae45);
  color: white;
  cursor: default;
}

.pagination-item:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-item:not(:disabled):not(.active):hover {
  background: #f5f7fa;
}

.pagination-ellipsis {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 36px;
  height: 36px;
  color: #666;
}
</style>