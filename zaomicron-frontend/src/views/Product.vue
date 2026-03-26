<template>
  <div class="product-page">
    <div class="product-detail-layout">
      <!-- Блок изображений -->
      <div class="product-gallery">
        <div class="product-main-image">
          <i :class="product.icon"></i>
        </div>
        <div class="product-thumbnails">
          <div v-for="n in 3" :key="n" class="thumbnail">
            <i class="fas fa-image"></i>
          </div>
        </div>
      </div>

      <!-- Информация о товаре -->
      <div class="product-info">
        <h1 class="product-title">{{ product.name }}</h1>
        <div class="product-sku">Артикул: {{ product.sku }}</div>
        <div class="product-price">{{ formatPrice(product.price) }} ₽</div>
        <div class="product-stock" :class="{ 'low-stock': product.stock < 10 }">
          <i class="fas fa-check-circle"></i> В наличии: {{ product.stock }} шт.
        </div>

        <div class="product-quantity">
          <div class="quantity-label">Количество:</div>
          <div class="quantity-control">
            <button class="quantity-btn" @click="decrement">−</button>
            <input type="number" v-model.number="quantity" class="quantity-input" min="1" :max="product.stock">
            <button class="quantity-btn" @click="increment">+</button>
          </div>
        </div>

        <div class="product-actions">
          <button class="btn btn-primary btn-large" @click="addToCart">
            <i class="fas fa-shopping-cart"></i> Добавить в корзину
          </button>
          <button class="btn btn-secondary btn-icon" @click="toggleFavorite">
            <i :class="isFavorite ? 'fas fa-heart' : 'far fa-heart'"></i>
          </button>
        </div>

        <div class="product-short-desc">
          <h3>Краткое описание</h3>
          <p>{{ product.shortDescription }}</p>
        </div>
      </div>
    </div>

    <!-- Табы -->
    <div class="product-tabs">
      <div class="tabs-header">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          class="tab-btn"
          :class="{ active: activeTab === tab.id }"
          @click="activeTab = tab.id"
        >
          {{ tab.name }}
        </button>
      </div>
      <div class="tabs-content">
        <div v-show="activeTab === 'description'" class="tab-pane">
          <h3>Полное описание</h3>
          <div v-html="product.fullDescription"></div>
        </div>
        <div v-show="activeTab === 'specs'" class="tab-pane">
          <h3>Технические характеристики</h3>
          <table class="specs-table">
            <tr v-for="(value, key) in product.specs" :key="key">
              <th>{{ key }}</th>
              <td>{{ value }}</td>
            </tr>
          </table>
        </div>
        <div v-show="activeTab === 'docs'" class="tab-pane">
          <h3>Документация</h3>
          <ul class="docs-list">
            <li v-for="doc in product.docs" :key="doc.name">
              <i class="fas fa-file-pdf"></i>
              <a :href="doc.url" target="_blank">{{ doc.name }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Product',
  data() {
    return {
      quantity: 1,
      activeTab: 'description',
      isFavorite: false,
    //поменять не забудь
      product: {
        id: this.$route.params.id,
        name: 'Цифровой манометр DM-100',
        sku: 'DM-100-2024',
        price: 15800,
        stock: 25,
        icon: 'fas fa-chart-line',
        shortDescription: 'Высокоточный цифровой манометр с ЖК-дисплеем, диапазон измерения 0-100 бар. Класс точности 0.5%. Защита IP65.',
        fullDescription: `<p>Цифровой манометр DM-100 предназначен для точного измерения давления жидкостей и газов, неагрессивных к медным сплавам. Прибор оснащен большим ЖК-дисплеем с подсветкой, что обеспечивает комфортное считывание показаний в любых условиях.</p>
        <p><strong>Основные преимущества:</strong></p>
        <ul>
          <li>Высокая точность измерений (класс 0.5%)</li>
          <li>Широкий диапазон рабочих температур: -20°C до +70°C</li>
          <li>Защита от влаги и пыли IP65</li>
          <li>Питание от батареи CR2032 (срок службы до 2 лет)</li>
          <li>Возможность выбора единиц измерения: бар, МПа, кгс/см², psi</li>
        </ul>
        <p>Прибор прошел полную метрологическую аттестацию и может использоваться в промышленных условиях.</p>`,
        specs: {
          'Диапазон измерения': '0…100 бар',
          'Класс точности': '0.5%',
          'Защита': 'IP65',
          'Питание': 'CR2032, 3В',
          'Рабочая температура': '-20…+70°C',
          'Присоединение': 'G1/2, G1/4'
        },
        docs: [
          { name: 'Паспорт изделия', url: '/docs/dm-100-manual.pdf' },
          { name: 'Сертификат поверки', url: '/docs/dm-100-cert.pdf' },
          { name: 'Руководство по эксплуатации', url: '/docs/dm-100-guide.pdf' }
        ]
      },
      tabs: [
        { id: 'description', name: 'Описание' },
        { id: 'specs', name: 'Характеристики' },
        { id: 'docs', name: 'Документация' },
        
      ]
    };
  },
  methods: {
    increment() {
      if (this.quantity < this.product.stock) this.quantity++;
    },
    decrement() {
      if (this.quantity > 1) this.quantity--;
    },
    addToCart() {
      // Эмит события или вызов Vuex
      console.log('Добавлено в корзину:', {
        id: this.product.id,
        name: this.product.name,
        price: this.product.price,
        quantity: this.quantity
      });
    
    },
    toggleFavorite() {
      this.isFavorite = !this.isFavorite;
      // Сохранить состояние в store или localStorage
      console.log('Избранное:', this.isFavorite);
    },
    formatPrice(price) {
      return new Intl.NumberFormat('ru-RU').format(price);
    }
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler(newId) {
        // Здесь будет загрузка товара по ID из API
        if (newId) {
          console.log('Загружаем товар с ID:', newId);
          // this.fetchProduct(newId);
        }
      }
    }
  }
};
</script>

<style scoped>
/* Общие стили для страницы товара */
.product-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Детальная сетка */
.product-detail-layout {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  margin-bottom: 50px;
}

/* Галерея изображений */
.product-gallery {
  position: sticky;
  top: 20px;
}

.product-main-image {
  aspect-ratio: 1 / 1;
  background: #f8f9fa;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 80px;
  color: #51AE45;
  margin-bottom: 15px;
  border: 1px solid #e0e0e0;
}

.product-thumbnails {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.thumbnail {
  width: 80px;
  height: 80px;
  background: #f8f9fa;
  border-radius: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: #999;
  cursor: pointer;
  border: 1px solid #e0e0e0;
  transition: all 0.3s;
}

.thumbnail:hover {
  border-color: #51AE45;
}

/* Информация о товаре */
.product-title {
  font-size: 28px;
  font-weight: 700;
  color: #333;
  margin-bottom: 10px;
}

.product-sku {
  color: #666;
  font-size: 14px;
  margin-bottom: 15px;
}

.product-price {
  font-size: 28px;
  font-weight: 700;
  color: #51AE45;
  margin: 20px 0 10px;
}

.product-stock {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #4CAF50;
  font-weight: 500;
  margin-bottom: 25px;
}

.product-stock.low-stock {
  color: #ff9800;
}

.product-stock i {
  font-size: 14px;
}

/* Количество */
.product-quantity {
  margin: 25px 0;
}

.quantity-label {
  font-weight: 600;
  margin-bottom: 10px;
  color: #333;
}

.quantity-control {
  display: flex;
  align-items: center;
  gap: 10px;
  max-width: 140px;
}

.quantity-btn {
  width: 36px;
  height: 36px;
  border: 1px solid #ddd;
  background: #fff;
  font-size: 18px;
  font-weight: 600;
  cursor: pointer;
  border-radius: 4px;
  transition: all 0.2s;
}

.quantity-btn:hover {
  background: #f5f7fa;
  border-color: #51AE45;
}

.quantity-input {
  width: 60px;
  height: 36px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
  padding: 0 5px;
}

/* Кнопки действий */
.product-actions {
  display: flex;
  gap: 15px;
  margin: 30px 0;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  border-radius: 4px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  text-decoration: none;
}

.btn-primary {
  background: #51AE45;
  color: #fff;
}

.btn-primary:hover {
  background: #3e8c34;
}

.btn-secondary {
  background: #f5f7fa;
  color: #333;
  border: 1px solid #ddd;
}

.btn-secondary:hover {
  background: #e9ecef;
}

.btn-large {
  padding: 12px 24px;
  font-size: 16px;
}

.btn-icon {
  width: 48px;
  padding: 0;
  font-size: 20px;
}

.btn-icon i {
  margin: 0;
}

/* Краткое описание */
.product-short-desc {
  border-top: 1px solid #eee;
  padding-top: 20px;
}

.product-short-desc h3 {
  margin-bottom: 10px;
  font-size: 18px;
  color: #333;
}

.product-short-desc p {
  color: #666;
  line-height: 1.6;
}

/* Табы */
.product-tabs {
  margin-top: 40px;
  border-top: 1px solid #e0e0e0;
}

.tabs-header {
  display: flex;
  gap: 5px;
  border-bottom: 1px solid #e0e0e0;
  flex-wrap: wrap;
}

.tab-btn {
  padding: 12px 24px;
  background: none;
  border: none;
  font-size: 16px;
  font-weight: 600;
  color: #666;
  cursor: pointer;
  transition: all 0.3s;
  position: relative;
}

.tab-btn.active {
  color: #51AE45;
}

.tab-btn.active::after {
  content: '';
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 2px;
  background: #51AE45;
}

.tab-btn:hover:not(.active) {
  color: #333;
}

.tabs-content {
  padding: 30px 0;
}

.tab-pane {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Таблица характеристик */
.specs-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

.specs-table th,
.specs-table td {
  padding: 12px;
  border-bottom: 1px solid #eee;
  text-align: left;
}

.specs-table th {
  width: 200px;
  font-weight: 600;
  color: #333;
  background: #f8f9fa;
}

.specs-table td {
  color: #666;
}

/* Документы */
.docs-list {
  list-style: none;
  padding: 0;
  margin-top: 15px;
}

.docs-list li {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid #eee;
}

.docs-list i {
  color: #e74c3c;
  font-size: 20px;
}

.docs-list a {
  color: #51AE45;
  text-decoration: none;
}

.docs-list a:hover {
  text-decoration: underline;
}

/* Адаптивность */
@media (max-width: 992px) {
  .product-detail-layout {
    gap: 30px;
  }
  .product-title {
    font-size: 24px;
  }
  .product-price {
    font-size: 24px;
  }
}

@media (max-width: 768px) {
  .product-page {
    padding: 15px;
  }
  .product-detail-layout {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  .product-gallery {
    position: static;
  }
  .product-main-image {
    font-size: 60px;
  }
  .thumbnail {
    width: 60px;
    height: 60px;
  }
  .product-actions {
    flex-direction: column;
  }
  .btn-large {
    width: 100%;
  }
  .btn-icon {
    width: 100%;
    padding: 12px;
  }
  .tabs-header {
    gap: 0;
  }
  .tab-btn {
    flex: 1;
    text-align: center;
    padding: 12px;
  }
  .specs-table th,
  .specs-table td {
    display: block;
    width: 100%;
    padding: 8px;
  }
  .specs-table th {
    background: none;
    font-weight: 600;
  }
  .specs-table tr {
    border-bottom: 1px solid #eee;
    display: block;
    margin-bottom: 10px;
  }
}

@media (max-width: 480px) {
  .product-title {
    font-size: 20px;
  }
  .product-price {
    font-size: 22px;
  }
  .product-sku {
    font-size: 12px;
  }
  .quantity-control {
    max-width: 120px;
  }
  .quantity-btn {
    width: 32px;
    height: 32px;
  }
  .quantity-input {
    width: 50px;
    height: 32px;
  }
}
</style>