<template>
  <div id="app">
    <header class="app-header">
      <div class="header-content">
        <h1>📝 Bitrix Notes</h1>
        <p class="subtitle">Простой сервис для управления заметками</p>
      </div>
      <button @click="openCreateForm" class="btn-create">
        ➕ Новая заметка
      </button>
    </header>

    <main class="app-main">
      <NotesList
        :notes="notes"
        :loading="loading"
        :error="error"
        @edit="openEditForm"
        @delete="deleteNote"
        @retry="loadNotes"
      />
    </main>

    <NoteForm
      v-if="showForm"
      :note="editingNote"
      :onSave="saveNote"
      @close="closeForm"
    />

    <footer class="app-footer">
      <p>Тестовое задание | Fullstack Team Lead | 1С-Битрикс</p>
    </footer>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import NotesList from './components/NotesList.vue';
import NoteForm from './components/NoteForm.vue';
import api from './services/api';

export default {
  name: 'App',
  components: {
    NotesList,
    NoteForm,
  },
  setup() {
    const notes = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const showForm = ref(false);
    const editingNote = ref(null);

    // Загрузка всех заметок
    const loadNotes = async () => {
      loading.value = true;
      error.value = null;

      try {
        notes.value = await api.getAllNotes();
      } catch (err) {
        error.value = err.message;
        console.error('Failed to load notes:', err);
      } finally {
        loading.value = false;
      }
    };

    // Открыть форму создания
    const openCreateForm = () => {
      editingNote.value = null;
      showForm.value = true;
    };

    // Открыть форму редактирования
    const openEditForm = (note) => {
      editingNote.value = note;
      showForm.value = true;
    };

    // Закрыть форму
    const closeForm = () => {
      showForm.value = false;
      editingNote.value = null;
    };

    // Сохранить заметку (создать или обновить)
    const saveNote = async (noteData) => {
      try {
        if (noteData.id) {
          // Обновление существующей заметки
          await api.updateNote(noteData.id, {
            title: noteData.title,
            content: noteData.content,
          });
        } else {
          // Создание новой заметки
          await api.createNote({
            title: noteData.title,
            content: noteData.content,
          });
        }

        // Перезагрузка списка заметок
        await loadNotes();
      } catch (err) {
        console.error('Failed to save note:', err);
        throw err;
      }
    };

    // Удалить заметку
    const deleteNote = async (id) => {
      try {
        await api.deleteNote(id);
        
        // Удаляем заметку из локального списка
        notes.value = notes.value.filter(note => note.id !== id);
      } catch (err) {
        console.error('Failed to delete note:', err);
        alert('Не удалось удалить заметку: ' + err.message);
      }
    };

    // Загрузка заметок при монтировании компонента
    onMounted(() => {
      loadNotes();
    });

    return {
      notes,
      loading,
      error,
      showForm,
      editingNote,
      loadNotes,
      openCreateForm,
      openEditForm,
      closeForm,
      saveNote,
      deleteNote,
    };
  },
};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
}

#app {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.app-header {
  text-align: center;
  color: white;
  padding: 40px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 20px;
}

.header-content h1 {
  font-size: 2.5rem;
  margin-bottom: 8px;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.subtitle {
  font-size: 1.1rem;
  opacity: 0.9;
}

.btn-create {
  padding: 14px 28px;
  background-color: white;
  color: #667eea;
  border: none;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-create:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.btn-create:active {
  transform: translateY(0);
}

.app-main {
  margin: 20px 0;
}

.app-footer {
  text-align: center;
  color: white;
  padding: 40px 20px;
  opacity: 0.8;
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .header-content h1 {
    font-size: 2rem;
  }

  .btn-create {
    width: 100%;
  }
}
</style>
