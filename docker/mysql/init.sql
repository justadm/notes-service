SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

CREATE DATABASE IF NOT EXISTS notes CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE notes;

CREATE TABLE IF NOT EXISTS notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  content TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_created (created_at),
  INDEX idx_updated (updated_at),
  FULLTEXT INDEX ft_title_content (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO notes (title, content) VALUES
('Добро пожаловать!', 'Это ваша первая заметка в системе. Вы можете редактировать или удалить её.'),
('Список покупок', 'Молоко, хлеб, яйца, сыр, масло'),
('Идеи для проекта', 'Реализовать REST API\nДобавить аутентификацию\nНастроить CI/CD\nПодключить мониторинг');
