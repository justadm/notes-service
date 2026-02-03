<template>
  <div class="note-item">
    <div class="note-header">
      <h3>{{ note.title }}</h3>
      <div class="note-actions">
        <button @click="$emit('edit', note)" class="btn-edit" title="Редактировать">
          ✏️
        </button>
        <button @click="handleDelete" class="btn-delete" title="Удалить">
          🗑️
        </button>
      </div>
    </div>
    
    <div class="note-content">
      {{ note.content || 'Нет содержимого' }}
    </div>
    
    <div class="note-footer">
      <span class="note-date">
        Создано: {{ formatDate(note.created_at) }}
      </span>
      <span v-if="note.updated_at !== note.created_at" class="note-date">
        Обновлено: {{ formatDate(note.updated_at) }}
      </span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NoteItem',
  props: {
    note: {
      type: Object,
      required: true,
    },
  },
  emits: ['edit', 'delete'],
  methods: {
    handleDelete() {
      if (confirm(`Удалить заметку "${this.note.title}"?`)) {
        this.$emit('delete', this.note.id);
      }
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    },
  },
};
</script>

<style scoped>
.note-item {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.note-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.note-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.note-header h3 {
  font-size: 1.25rem;
  color: #333;
  margin: 0;
  flex: 1;
  word-break: break-word;
}

.note-actions {
  display: flex;
  gap: 8px;
}

.btn-edit,
.btn-delete {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.btn-edit:hover {
  background-color: #e3f2fd;
}

.btn-delete:hover {
  background-color: #ffebee;
}

.note-content {
  color: #666;
  line-height: 1.6;
  margin-bottom: 16px;
  white-space: pre-wrap;
  word-break: break-word;
  min-height: 40px;
}

.note-footer {
  display: flex;
  justify-content: space-between;
  font-size: 0.85rem;
  color: #999;
  padding-top: 12px;
  border-top: 1px solid #eee;
}

.note-date {
  display: inline-block;
}

@media (max-width: 600px) {
  .note-footer {
    flex-direction: column;
    gap: 4px;
  }
}
</style>
