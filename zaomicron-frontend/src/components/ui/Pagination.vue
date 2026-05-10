<template>
  <nav class="pagination" aria-label="Пагинация">
    <button
      class="pagination-item"
      :disabled="currentPage === 1"
      :aria-disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
      aria-label="Предыдущая страница"
    >
      ←
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
      :aria-disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
      aria-label="Следующая страница"
    >
      →
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
  computed: {
    visiblePages() {
      const total = this.totalPages;
      const current = this.currentPage;
      const delta = 2; // сколько страниц показывать слева и справа от текущей
      const range = [];
      const rangeWithDots = [];

      for (let i = 1; i <= total; i++) {
        if (
          i === 1 ||
          i === total ||
          (i >= current - delta && i <= current + delta)
        ) {
          range.push(i);
        }
      }

      let last;
      for (const i of range) {
        if (last) {
          if (i - last === 2) {
            rangeWithDots.push(last + 1);
          } else if (i - last !== 1) {
            rangeWithDots.push('...');
          }
        }
        rangeWithDots.push(i);
        last = i;
      }
      return rangeWithDots;
    },
  },
  methods: {
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
  gap: 8px;
  margin-top: 30px;
  flex-wrap: wrap;
}

.pagination-item {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 40px;
  height: 40px;
  padding: 0 8px;
  border: 1px solid var(--color-border, #e0e0e0);
  border-radius: 4px;
  background: #fff;
  color: #333;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.pagination-item.active {
  background: #51ae45;
  border-color: #51ae45;
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
  min-width: 40px;
  height: 40px;
  color: #666;
}
</style>