<div align="center">

# ⚡ Zaomicron

**Портфолио-версия веб-проекта для поставщика электронных компонентов и измерительного оборудования**

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat-square&logo=vue.js&logoColor=white)](https://vuejs.org/)
[![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev/)
[![Vuetify](https://img.shields.io/badge/Vuetify-3-1867C0?style=flat-square&logo=vuetify&logoColor=white)](https://vuetifyjs.com/)
[![Pinia](https://img.shields.io/badge/Pinia-FFD859?style=flat-square&logo=pinia&logoColor=black)](https://pinia.vuejs.org/)
[![MySQL](https://img.shields.io/badge/MySQL-8.4-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker&logoColor=white)](https://www.docker.com/)

[📖 О проекте](#-о-проекте) • [🎬 Демонстрация](#-видео-демонстрация) • [✨ Возможности](#-возможности) • [🛠 Технологии](#-технологии) • [📁 Структура](#-структура-проекта) • [🚀 Запуск](#-запуск) • [📄 Лицензия](#-лицензия) • [📬 Контакты](#-контакты)

</div>

---

## 📖 О проекте

**Zaomicron** — полноценный веб-проект, разработанный как портфолио-демонстрация навыков full-stack разработки. Приложение представляет собой каталог продукции для поставщика электронных компонентов и измерительного оборудования.

* **Backend** реализован на Laravel с REST API.
* **Frontend** — SPA-приложение на Vue 3 (Vite, Vue Router, Pinia, Vuetify).
* Проект полностью контейнеризирован с помощью Docker для быстрого и удобного развертывания.

> ⚠️ **Важное замечание:** Название «Zaomicron» и все материалы проекта используются исключительно в демонстрационных целях в рамках личного портфолио автора.

---

## 🎬 Видео-демонстрация

<div align="center">
  <video src="./zaomicron-frontend/src/assets/video/demo.mp4" controls muted playsinline preload="metadata" width="100%" style="max-width: 900px; border-radius: 8px;">
    Ваш браузер не поддерживает встроенное воспроизведение видео. 
    <a href="./zaomicron-frontend/src/assets/video/demo.mp4">Скачать видеодемонстрацию</a>.
  </video>
</div>

---

## ✨ Возможности

| Раздел | Функциональность |
| :--- | :--- |
| **🏠 Главная страница** | Промо-блоки, интерактивная витрина товаров, сквозная навигация. |
| **🗂️ Каталог** | Дерево категорий, динамические фильтры, быстрый поиск, карточки товаров. |
| **📦 Страница товара** | Детальные технические характеристики, галерея, блок сопутствующих товаров. |
| **📰 Контент** | Информационные страницы (о компании, услуги, сертификаты, юридические данные, статьи). |
| **🛒 Корзина и Избранное** | Полноценный менеджмент состояний на стороне клиента с использованием Pinia. |
| **✉️ Формы** | Обратная связь, подписка, запросы на партнёрство и отправка заказов. |

---

## 🛠 Технологии

* **Backend:** PHP 8.2, Laravel 12
* **Frontend:** Vue 3, Vite, Vue Router 4
* **UI & Состояние:** Vuetify 3, Pinia
* **Данные & API:** REST API, MySQL 8.4
* **Контейнеризация:** Composer, npm, Docker Compose

---

## 📁 Структура проекта

```text
.
├── app/                       # Laravel-контроллеры, модели и бизнес-логика
├── bootstrap/                 # Скрипты инициализации Laravel
├── config/                    # Конфигурационные файлы фреймворка
├── database/                  # Миграции базы данных и сидеры (seeders)
├── docker/                    # Конфигурации для MySQL и Docker entrypoint
├── public/                    # Публичные файлы и единая точка входа index.php
├── resources/                 # Blade-шаблоны и исходные общие ресурсы
├── routes/                    # Определение Web- и API-маршрутов
├── tests/                     # Автотесты (PHPUnit)
├── zaomicron-frontend/        # Исходный код SPA-приложения на Vue.js
│   └── src/
│       └── assets/
│           └── video/
│               └── demo.mp4   # 🎬 Видео-ролик демонстрации интерфейса
├── Dockerfile                 # Инструкции сборки контейнера приложения
├── docker-compose.yml         # Сценарий развертывания локального окружения
├── composer.json              # Зависимости Backend (PHP)
├── package.json               # Зависимости корневой сборки
├── vite.config.js             # Конфигурация сборщика Vite
├── README.md                  # Документация проекта
└── LICENSE                    # Файл лицензии
```

---

## 🚀 Запуск

### Вариант 1 — С использованием Docker Compose (Рекомендуется)

Выполните команду в корневой директории:
```bash
docker compose up --build
```
После успешного развертывания приложение будет доступно по адресу: **http://localhost:8000**

> ⚙️ **Примечание:** Docker Compose автоматически поднимает веб-сервер и СУБД MySQL. Все параметры окружения в конфигурационных файлах оптимизированы для демонстрационного запуска и не подходят для production-среды.

### Вариант 2 — Локальный запуск (Без Docker)

**Системные требования:** PHP 8.2+, Composer, Node.js & npm, установленный экземпляр MySQL.

1. Создайте файл конфигурации `.env` в корне проекта и укажите ваши параметры подключения к БД.
2. Последовательно выполните установку зависимостей и выполните миграции:

```bash
# Установка серверных зависимостей
composer install

# Установка зависимостей сборщика корня
npm install

# Установка зависимостей Frontend-приложения
npm install --prefix zaomicron-frontend

# Генерация ключа приложения Laravel и запуск миграций базы данных
php artisan key:generate
php artisan migrate

# Сборка клиентского SPA-приложения
npm run build

# Запуск локального сервера разработки
php artisan serve
```

---

## 📄 Лицензия

⚠️ Проект распространяется на условиях **ограниченной лицензии**, приведённой в файле `LICENSE`. Данный код **не является** лицензией MIT или любой другой открытой/свободной лицензией. 

* Сторонние библиотеки и пакеты внутри проекта подчиняются собственным вендорским лицензиям.
* **Вкратце:** Исходный код открыт исключительно для ознакомления и изучения архитектуры. Запрещается копирование, модификация, распространение или использование кода в коммерческих или некоммерческих целях без предварительного письменного согласия автора. Полные юридические условия доступны в файле `LICENSE`.

---

## 📬 Контакты

* **Автор:** Темирова Ангелина
* **Email:** temirovaangelina00@gmail.com

<div align="center">

© 2026 Темирова Ангелина. Все права защищены.

</div>
