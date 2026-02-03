<template>
  <div class="notes-list">
    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Загрузка заметок...</p>
    </div>

    <div v-else-if="error" class="error">
      <p>{{ error }}</p>
      <button @click="$emit('retry')" class="btn-retry">Попробовать снова</button>
    </div>

    <div v-else-if="notes.length === 0" class="empty-state">
      <div class="empty-icon">📝</div>
      <h3>У вас пока нет заметок</h3>
      <p>Создайте свою первую заметку, нажав на кнопку "Новая заметка"</p>
    </div>

    <div v-else class="notes-grid">
      <NoteItem
        v-for="note in notes"
        :key="note.id"
        :note="note"
        @edit="$emit('edit', note)"
        @delete="$emit('delete', $event)"
      />
    </div>
  </div>
</template>

<script>
import NoteItem from './NoteItem.vue';

export default {
  name: 'NotesList',
  components: {
    NoteItem,
  },
  props: {
    notes: {
      type: Array,
      required: true,
    },
    loading: {
      type: Boolean,
      default: false,
    },
    error: {
      type: String,
      default: null,
    },
  },
  emits: ['edit', 'delete', 'retry'],
};
</script>

<style scoped>
.notes-list {
  min-height: 400px;
}

.loading,
.error,
.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 400px;
  color: white;
  text-align: center;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid rgba(255, 255, 255, 0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.loading p {
  margin-top: 16px;
  font-size: 1.1rem;
}

.error p {
  font-size: 1.1rem;
  margin-bottom: 16px;
}

.btn-retry {
  padding: 12px 24px;
  background-color: white;
  color: #667eea;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.btn-retry:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.empty-state {
  padding: 40px 20px;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 16px;
  opacity: 0.8;
}

.empty-state h3 {
  font-size: 1.5rem;
  margin-bottom: 8px;
}

.empty-state p {
  font-size: 1rem;
  opacity: 0.9;
}

.notes-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  padding: 20px 0;
}

@media (max-width: 768px) {
  .notes-grid {
    grid-template-columns: 1fr;
  }
}
</style>
