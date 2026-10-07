/*
 * PC Resolve — interações do site e do painel (Alpine.js).
 * Carregado antes do Alpine (ambos com "defer"), para registrar stores e componentes.
 */
(function () {
  'use strict';

  /** Equivalente ao showToast() da versão React. */
  window.toast = function (text, type) {
    if (window.Alpine && window.Alpine.store('toasts')) {
      window.Alpine.store('toasts').push(text, type || 'success');
    } else {
      (window.__TOASTS__ = window.__TOASTS__ || []).push({ text: text, type: type || 'success' });
    }
  };

  /** Banner de cookies (LGPD). */
  window.acceptCookies = function () {
    var secure = location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = 'pcresolve_cookie_consent=true; Max-Age=31536000; Path=/; SameSite=Lax' + secure;
    window.toast('Preferências de cookies salvas.', 'info');
  };

  document.addEventListener('alpine:init', function () {
    var Alpine = window.Alpine;

    Alpine.store('toasts', {
      items: [],
      push: function (text, type) {
        var id = Date.now().toString(36) + Math.random().toString(36).slice(2, 7);
        this.items.push({ id: id, text: text, type: type || 'success' });
        var self = this;
        setTimeout(function () { self.remove(id); }, 4000);
      },
      remove: function (id) {
        this.items = this.items.filter(function (t) { return t.id !== id; });
      },
    });

    (window.__TOASTS__ || []).forEach(function (t) {
      Alpine.store('toasts').push(t.text, t.type);
    });
    window.__TOASTS__ = [];

    /** Formulários públicos de solicitação (validação igual à versão React). */
    Alpine.data('leadForm', function (cfg) {
      cfg = cfg || {};
      return {
        done: !!cfg.done,
        consent: !!cfg.consent,
        sending: false,
        submit: function (event) {
          var form = event.target;
          var name = (form.elements.customer_name && form.elements.customer_name.value || '').trim();
          var phone = (form.elements.phone && form.elements.phone.value || '').trim();

          if (!name || !phone) {
            event.preventDefault();
            window.toast(cfg.missingMessage || 'Preencha nome e telefone.', 'warning');
            return;
          }
          if (cfg.requireConsent && !this.consent) {
            event.preventDefault();
            window.toast(cfg.consentMessage || 'É necessário concordar com o tratamento dos dados.', 'warning');
            return;
          }
          if (this.sending) {
            event.preventDefault();
            return;
          }
          this.sending = true;
        },
      };
    });

    /** Filtro do catálogo público de serviços (categoria + busca). */
    Alpine.data('serviceFilter', function (index) {
      var params = new URLSearchParams(location.search);
      return {
        index: index || [],
        category: params.get('categoria') || 'todos',
        query: params.get('busca') || '',
        isVisible: function (i) {
          var item = this.index[i];
          if (!item) return false;
          var matchesCategory = this.category === 'todos' || item.category === this.category;
          var q = this.query.toLowerCase();
          return matchesCategory && (q === '' || item.text.indexOf(q) !== -1);
        },
        get visibleCount() {
          var count = 0;
          for (var i = 0; i < this.index.length; i++) {
            if (this.isVisible(i)) count++;
          }
          return count;
        },
      };
    });

    /** Foto de perfil: arrastar e soltar, pré-visualização e envio automático. */
    Alpine.data('avatarUpload', function (cfg) {
      cfg = cfg || {};
      return {
        dragging: false,
        sending: false,
        preview: null,
        drop: function (event) {
          this.dragging = false;
          var file = event.dataTransfer && event.dataTransfer.files[0];
          if (!file) return;
          // Repassa o arquivo solto para o input, para o formulário enviá-lo.
          var dt = new DataTransfer();
          dt.items.add(file);
          this.$refs.input.files = dt.files;
          this.pick(file);
        },
        pick: function (file) {
          if (!file) return;

          var ext = (file.name.split('.').pop() || '').toLowerCase();
          if (ext === 'jpeg') ext = 'jpg';
          if ((cfg.accept || []).indexOf(ext) === -1) {
            window.toast('Formato não permitido. Envie ' + (cfg.formatsLabel || 'JPG') + '.', 'warning');
            this.reset();
            return;
          }

          var maxBytes = (cfg.maxUploadKb || 8192) * 1024;
          if (file.size > maxBytes) {
            window.toast('Arquivo muito grande. O limite é de ' + Math.round(maxBytes / 1024) + ' KB.', 'warning');
            this.reset();
            return;
          }

          this.preview = URL.createObjectURL(file);
          this.sending = true;
          this.$refs.form.submit();
        },
        reset: function () {
          this.$refs.input.value = '';
          this.preview = null;
          this.sending = false;
        },
      };
    });

    /**
     * Telas de cadastro do painel (serviços, cidades, depoimentos, FAQ):
     * busca instantânea + modal de edição + modal de criação.
     */
    Alpine.data('crudPage', function (cfg) {
      cfg = cfg || {};
      return {
        search: '',
        rows: cfg.rows || [],
        editing: null,
        newOpen: !!cfg.newOpen,
        edit: function (item) {
          this.editing = JSON.parse(JSON.stringify(item));
        },
        isVisible: function (i) {
          var q = this.search.toLowerCase().trim();
          return q === '' || String(this.rows[i] || '').indexOf(q) !== -1;
        },
        get visibleCount() {
          var count = 0;
          for (var i = 0; i < this.rows.length; i++) {
            if (this.isVisible(i)) count++;
          }
          return count;
        },
      };
    });

    /**
     * Biblioteca de mídia: alterna miniaturas/lista, filtra, busca, abre detalhes
     * e prepara o envio (clique ou arrastar e soltar).
     */
    Alpine.data('mediaPage', function (cfg) {
      cfg = cfg || {};
      return {
        view: cfg.view || 'miniaturas',
        filter: cfg.filter || 'todas',
        search: '',
        rows: cfg.rows || [],
        orphans: cfg.orphans || [],
        items: cfg.items || [],
        detail: null,
        uploadOpen: false,
        dragging: false,
        sending: false,
        chosen: [],
        isVisible: function (i) {
          var q = this.search.toLowerCase().trim();
          if (q !== '' && String(this.rows[i] || '').indexOf(q) === -1) return false;
          if (this.filter === 'orfas') return !!this.orphans[i];
          if (this.filter === 'em-uso') return !this.orphans[i];
          return true;
        },
        get visibleCount() {
          var count = 0;
          for (var i = 0; i < this.rows.length; i++) {
            if (this.isVisible(i)) count++;
          }
          return count;
        },
        open: function (i) {
          this.detail = this.items[i] || null;
        },
        pickFiles: function (event) {
          this.chosen = Array.prototype.map.call(event.target.files || [], function (f) { return f.name; });
        },
        dropFiles: function (event) {
          this.dragging = false;
          var files = event.dataTransfer && event.dataTransfer.files;
          if (!files || !files.length) return;
          // Repassa os arquivos soltos para o input, para o formulário enviá-los.
          this.$refs.uploadInput.files = files;
          this.chosen = Array.prototype.map.call(files, function (f) { return f.name; });
        },
      };
    });

    /**
     * Catálogo de serviços: busca, modais de criar/editar e escolha da imagem —
     * do computador (envio junto com o formulário) ou da biblioteca de mídia
     * (reaproveita um arquivo que já está no servidor).
     */
    Alpine.data('servicesPage', function (cfg) {
      cfg = cfg || {};
      return {
        search: '',
        rows: cfg.rows || [],
        editing: null,
        newOpen: !!cfg.newOpen,
        media: cfg.media || [],
        placeholder: cfg.placeholder || '',
        // Seletor da biblioteca
        pickerOpen: false,
        pickerFor: 'new',
        pickerQuery: '',
        // Imagem do formulário de criação
        newImage: '',
        newPreview: null,
        newFileName: '',
        // Pré-visualização do arquivo escolhido no formulário de edição
        editPreview: null,
        editFileName: '',

        edit: function (item) {
          this.editing = JSON.parse(JSON.stringify(item));
          this.editPreview = null;
          this.editFileName = '';
        },
        isVisible: function (i) {
          var q = this.search.toLowerCase().trim();
          return q === '' || String(this.rows[i] || '').indexOf(q) !== -1;
        },
        get visibleCount() {
          var count = 0;
          for (var i = 0; i < this.rows.length; i++) {
            if (this.isVisible(i)) count++;
          }
          return count;
        },

        /** Caminho/URL da imagem que o formulário está exibindo agora. */
        previewUrl: function (target) {
          if (target === 'edit') {
            if (this.editPreview) return this.editPreview;
            return this.editing && this.editing.image ? '/' + this.editing.image : this.placeholder;
          }
          if (this.newPreview) return this.newPreview;
          return this.newImage ? '/' + this.newImage : this.placeholder;
        },

        openPicker: function (target) {
          this.pickerFor = target;
          this.pickerQuery = '';
          this.pickerOpen = true;
        },
        /** Escolha da biblioteca: cancela um arquivo do computador que estivesse selecionado. */
        choose: function (item) {
          if (this.pickerFor === 'edit' && this.editing) {
            this.editing.image = item.path;
            this.editPreview = null;
            this.editFileName = '';
            if (this.$refs.editFile) this.$refs.editFile.value = '';
          } else {
            this.newImage = item.path;
            this.newPreview = null;
            this.newFileName = '';
            if (this.$refs.newFile) this.$refs.newFile.value = '';
          }
          this.pickerOpen = false;
        },
        get pickerItems() {
          var q = this.pickerQuery.toLowerCase().trim();
          return this.media.filter(function (m) {
            return q === '' || m.filename.toLowerCase().indexOf(q) !== -1;
          });
        },

        /** Arquivo do computador: mostra a pré-visualização local antes de salvar. */
        pickFile: function (event, target) {
          var file = event.target.files && event.target.files[0];
          if (!file) return;
          var url = URL.createObjectURL(file);
          if (target === 'edit') {
            this.editPreview = url;
            this.editFileName = file.name;
          } else {
            this.newPreview = url;
            this.newFileName = file.name;
          }
        },
        clearFile: function (target) {
          if (target === 'edit') {
            this.editPreview = null;
            this.editFileName = '';
            if (this.$refs.editFile) this.$refs.editFile.value = '';
          } else {
            this.newPreview = null;
            this.newFileName = '';
            if (this.$refs.newFile) this.$refs.newFile.value = '';
          }
        },
      };
    });

    /** Ordens de Serviço & Leads: busca instantânea, modal "Gerenciar" e nova O.S. */
    Alpine.data('requestsPage', function (cfg) {
      cfg = cfg || {};
      return {
        search: cfg.search || '',
        rows: cfg.rows || [],
        active: null,
        newOpen: !!cfg.newOpen,
        form: { status: 'pendente', budget: 0, notes: '' },
        init: function () {
          if (cfg.openLead) this.open(cfg.openLead);
        },
        open: function (lead) {
          this.form.status = lead.status;
          this.form.budget = lead.budget || 0;
          this.form.notes = lead.internal_notes || '';
          this.active = lead;
        },
        isVisible: function (i) {
          var q = this.search.toLowerCase().trim();
          return q === '' || String(this.rows[i] || '').indexOf(q) !== -1;
        },
        get visibleCount() {
          var count = 0;
          for (var i = 0; i < this.rows.length; i++) {
            if (this.isVisible(i)) count++;
          }
          return count;
        },
        /** Mensagem de atualização igual à da versão React. */
        get notifyUrl() {
          if (!this.active) return '#';
          var budget = Number(this.form.budget || 0).toFixed(2);
          var message = 'Olá ' + this.active.customer_name + '! Atualização da OS ' + this.active.protocol +
            ': Seu equipamento está com status [' + String(this.form.status).toUpperCase() + ']. Valor do serviço: R$ ' + budget + '.';
          return 'https://wa.me/55' + String(this.active.phone).replace(/\D/g, '') + '?text=' + encodeURIComponent(message);
        },
      };
    });
  });
})();
