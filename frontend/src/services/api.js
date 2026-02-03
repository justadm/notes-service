import axios from 'axios';

const API_BASE_URL = '/api/notes';

// Создаем инстанс axios с базовой конфигурацией
const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor для обработки ошибок
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    console.error('API Error:', error);
    
    if (error.response) {
      // Сервер ответил с ошибкой
      const message = error.response.data?.message || 'Произошла ошибка на сервере';
      throw new Error(message);
    } else if (error.request) {
      // Запрос был отправлен, но ответа не получено
      throw new Error('Сервер не отвечает. Проверьте подключение.');
    } else {
      // Ошибка при настройке запроса
      throw new Error('Ошибка при отправке запроса');
    }
  }
);

export default {
  /**
   * Получить все заметки
   */
  async getAllNotes() {
    const response = await apiClient.get('');
    return response.data.data || [];
  },

  /**
   * Получить заметку по ID
   */
  async getNoteById(id) {
    const response = await apiClient.get(`/${id}`);
    return response.data.data;
  },

  /**
   * Создать новую заметку
   */
  async createNote(noteData) {
    const response = await apiClient.post('', noteData);
    return response.data.data;
  },

  /**
   * Обновить заметку
   */
  async updateNote(id, noteData) {
    const response = await apiClient.put(`/${id}`, noteData);
    return response.data.data;
  },

  /**
   * Удалить заметку
   */
  async deleteNote(id) {
    const response = await apiClient.delete(`/${id}`);
    return response.data;
  },
};
