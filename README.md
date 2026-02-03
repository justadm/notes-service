# Notes Service - REST API

Тестовое задание: RESTful API сервис заметок на PHP с UI на Vue.js

## 🚀 Быстрый старт

```bash
# Вариант 1: Автоматически
chmod +x start.sh
./start.sh

# Вариант 2: Вручную
docker-compose up -d --build
```

Подождите 20-30 секунд для инициализации.

## 📍 Доступ

- **Приложение**: http://localhost:8080
- **API**: http://localhost:8080/api/notes
- **Swagger**: http://localhost:8080/docs (или /docs/swagger-ui.html)
- **phpMyAdmin**: http://localhost:8081 (root/root)

## ✅ Чек-лист сдачи

- Репозиторий публичный (GitHub/GitLab/Bitbucket)
- `docker-compose up -d --build` поднимает всё приложение
- UI доступен по `http://localhost:8080`
- Swagger доступен по `http://localhost:8080/docs/swagger-ui.html`
- PHPUnit проходит: `docker-compose exec app ./vendor/bin/phpunit`
- В README есть инструкция по развёртке

## 📡 API Endpoints

### Базовый URL
```
http://localhost:8080/api/notes
```

### Методы

#### Получить все заметки
```bash
GET /api/notes
```

#### Получить заметку по ID
```bash
GET /api/notes/{id}
```

#### Создать заметку
```bash
POST /api/notes
Content-Type: application/json

{
  "title": "Новая заметка",
  "content": "Содержимое"
}
```

#### Обновить заметку
```bash
PUT /api/notes/{id}
Content-Type: application/json

{
  "title": "Обновленная заметка",
  "content": "Новое содержимое"
}
```

#### Удалить заметку
```bash
DELETE /api/notes/{id}
```

## 🧪 Тестирование

```bash
# PHPUnit тесты
docker-compose exec app ./vendor/bin/phpunit

# Curl примеры
curl http://localhost:8080/api/notes

curl -X POST http://localhost:8080/api/notes \
  -H "Content-Type: application/json" \
  -d '{"title":"Test","content":"Hello"}'
```

## 🧾 Swagger генерация

Swagger/OpenAPI генерируется из PHP-атрибутов:

```bash
docker-compose exec app composer openapi:generate
```

Автогенерация при старте контейнера (опционально):

```bash
GENERATE_OPENAPI=1 docker-compose up -d --build
```

## 📁 Структура

```
notes-service/
├── src/
│   ├── Controller/
│   │   └── NoteController.php
│   ├── Service/
│   │   └── NoteService.php
│   ├── Repository/
│   │   └── NoteRepository.php
│   ├── Validator/
│   │   └── NoteValidator.php
│   ├── Model/
│   │   └── Note.php
│   └── Database.php
├── public/
│   └── index.php
├── tests/
│   └── NoteValidatorTest.php
├── frontend/
│   └── src/
│       ├── components/
│       └── services/
├── docker/
└── docs/
```

## 🛠 Стек

- PHP 8.1
- MySQL 8.0
- Vue.js 3
- Docker
- PHPUnit
- Swagger

## 🔧 Как работает сборка фронтенда

Фронтенд собирается автоматически при старте контейнера `app`. Это позволяет запускать проект одной командой без отдельного `node`-сервиса.

## 🏗 Архитектура

- **Controller Layer** - HTTP обработка
- **Service Layer** - Бизнес-логика
- **Repository Layer** - Работа с БД
- **Validator Layer** - Валидация данных
- **Model Layer** - Сущности

## 🧠 Архитектурные решения (кратко)

- Слоистая архитектура для разделения ответственности и удобства тестирования.
- PDO + подготовленные запросы для безопасности и читаемости.
- Валидация на уровне сервиса для единых правил при create/update.
- Docker + Nginx + PHP-FPM для воспроизводимого окружения.
- Автосборка фронта в контейнере `app`, чтобы запуск был одной командой.

## 📊 База данных

```sql
CREATE TABLE notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  content TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## 🔐 Безопасность

- SQL Injection защита (PDO prepared statements)
- XSS защита (strip_tags)
- Валидация данных
- CORS настроен

## 📈 Производительность

- Индексы на created_at, updated_at
- Gzip сжатие в Nginx
- PDO persistent connections

## 🛑 Остановка

```bash
docker-compose down

# С удалением БД
docker-compose down -v
```

## 📝 Лицензия

MIT
