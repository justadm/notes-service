<template>
  <div class="note-form-overlay" @click.self="close">
    <div class="note-form">
      <div class="form-header">
        <h2>{{ isEditMode ? 'Редактировать заметку' : 'Новая заметка' }}</h2>
        <button @click="close" class="btn-close">✕</button>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="title">Заголовок *</label>
          <input
            id="title"
            v-model="formData.title"
            type="text"
            placeholder="Введите заголовок заметки"
            maxlength="200"
            required
            autofocus
          />
          <div class="char-counter">{{ formData.title.length }}/200</div>
        </div>

        <div class="form-group">
          <label for="content">Содержимое</label>
          <textarea
            id="content"
            v-model="formData.content"
            placeholder="Введите содержимое заметки"
            rows="8"
            maxlength="5000"
          ></textarea>
          <div class="char-counter">{{ formData.content.length }}/5000</div>
        </div>

        <div v-if="error" class="error-message">
          {{ error }}
        </div>

        <div class="form-actions">
          <button type="button" @click="close" class="btn btn-cancel">
            Отмена
          </button>
          <button type="submit" class="btn btn-submit" :disabled="loading">
            {{ loading ? 'Сохранение...' : 'Сохранить' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NoteForm',
  props: {
    note: {
      type: Object,
      default: null,
    },
    onSave: {
      type: Function,
      required: true,
    },
  },
  emits: ['close'],
  data() {
    return {
      formData: {
        title: '',
        content: '',
      },
      loading: false,
      error: null,
    };
  },
  computed: {
    isEditMode() {
      return !!this.note;
    },
  },
  mounted() {
    if (this.note) {
      this.formData.title = this.note.title;
      this.formData.content = this.note.content || '';
    }
  },
  methods: {
    close() {
      this.$emit('close');
    },
    async handleSubmit() {
      this.error = null;

      if (!this.formData.title.trim()) {
        this.error = 'Заголовок обязателен для заполнения';
        return;
      }

      this.loading = true;

      try {
        await this.onSave({
          ...this.formData,
          id: this.note?.id,
        });
        this.close();
      } catch (err) {
        this.error = err.message || 'Произошла ошибка при сохранении';
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.note-form-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  padding: 20px;
}

.note-form {
  background: white;
  border-radius: 16px;
  padding: 30px;
  max-width: 600px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}

.form-header h2 {
  margin: 0;
  color: #333;
  font-size: 1.5rem;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #999;
  padding: 0;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  transition: background-color 0.2s;
}

.btn-close:hover {
  background-color: #f5f5f5;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  color: #555;
  font-weight: 500;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 1rem;
  font-family: inherit;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
}

.form-group textarea {
  resize: vertical;
  min-height: 120px;
}

.char-counter {
  text-align: right;
  font-size: 0.85rem;
  color: #999;
  margin-top: 4px;
}

.error-message {
  background-color: #ffebee;
  color: #c62828;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 0.9rem;
}

.form-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
}

.btn {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}

.btn-cancel {
  background-color: #f5f5f5;
  color: #666;
}

.btn-cancel:hover {
  background-color: #e0e0e0;
}

.btn-submit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 600px) {
  .note-form {
    padding: 20px;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>
