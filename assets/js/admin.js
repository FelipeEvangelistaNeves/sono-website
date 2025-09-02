// assets/js/admin.js

// Controle de abas
function showTab(tabName) {
  // Remove active de todos os botões e conteúdos
  document
    .querySelectorAll(".tab-button")
    .forEach((btn) => btn.classList.remove("active"));
  document
    .querySelectorAll(".tab-content")
    .forEach((content) => content.classList.remove("active"));

  // Adiciona active no botão clicado
  event.target.classList.add("active");

  // Adiciona active no conteúdo correspondente
  document.getElementById(tabName).classList.add("active");

  // Se for preview, recarrega o iframe
  if (tabName === "preview") {
    const iframe = document.getElementById("previewFrame");
    if (iframe) {
      // Força reload do iframe para mostrar mudanças mais recentes
      iframe.src = iframe.src;
    }
  }
}

// Função para mostrar feedback visual ao salvar
function showSaveSuccess() {
  const button = document.querySelector(".save-button");
  if (button) {
    const originalText = button.textContent;
    const originalBackground = button.style.background;

    button.textContent = "✅ Salvo com Sucesso!";
    button.style.background = "linear-gradient(135deg, #27ae60, #2ecc71)";

    setTimeout(() => {
      button.textContent = originalText;
      button.style.background = originalBackground;
    }, 3000);
  }
}

// Auto-save functionality (opcional)
let autoSaveTimer;

function autoSave() {
  clearTimeout(autoSaveTimer);
  autoSaveTimer = setTimeout(() => {
    const form = document.getElementById("contentForm");
    if (form) {
      // Criar um FormData para envio via AJAX
      const formData = new FormData(form);

      fetch("admin.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.text())
        .then((data) => {
          console.log("Auto-save realizado");
          // Opcional: mostrar indicador discreto de auto-save
        })
        .catch((error) => {
          console.error("Erro no auto-save:", error);
        });
    }
  }, 2000); // Auto-save após 2 segundos de inatividade
}

// Adicionar event listeners para auto-save
document.addEventListener("DOMContentLoaded", function () {
  const inputs = document.querySelectorAll("input, textarea");
  inputs.forEach((input) => {
    input.addEventListener("input", autoSave);
  });

  // Prevenção de perda de dados
  let hasUnsavedChanges = false;

  inputs.forEach((input) => {
    input.addEventListener("input", () => {
      hasUnsavedChanges = true;
    });
  });

  const form = document.getElementById("contentForm");
  if (form) {
    form.addEventListener("submit", () => {
      hasUnsavedChanges = false;
    });
  }

  window.addEventListener("beforeunload", (e) => {
    if (hasUnsavedChanges) {
      e.preventDefault();
      e.returnValue = "Você tem alterações não salvas. Deseja realmente sair?";
    }
  });
});

// Função para validar URLs de imagem
function validateImageURL(input) {
  const url = input.value;
  if (url && !url.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
    input.setCustomValidity(
      "Por favor, insira uma URL válida de imagem (jpg, png, gif, webp)"
    );
  } else {
    input.setCustomValidity("");
  }
}

// Adicionar validação para campos de imagem
document.addEventListener("DOMContentLoaded", function () {
  const imageInputs = document.querySelectorAll('input[type="url"]');
  imageInputs.forEach((input) => {
    input.addEventListener("blur", () => validateImageURL(input));
  });
});

// Função para preview de imagens
function previewImage(input, previewId) {
  const preview = document.getElementById(previewId);
  if (input.value && preview) {
    preview.src = input.value;
    preview.style.display = "block";

    // Verificar se a imagem carrega corretamente
    preview.onerror = function () {
      this.style.display = "none";
      input.setCustomValidity(
        "URL da imagem não é válida ou não está acessível"
      );
    };

    preview.onload = function () {
      input.setCustomValidity("");
    };
  }
}

// Adicionar contador de caracteres para textareas
function addCharacterCounter(textarea) {
  const maxLength = textarea.getAttribute("maxlength") || 500;
  const counter = document.createElement("div");
  counter.className = "char-counter";
  counter.style.cssText =
    "font-size: 0.8rem; color: #666; text-align: right; margin-top: 5px;";

  function updateCounter() {
    const remaining = maxLength - textarea.value.length;
    counter.textContent = `${textarea.value.length}/${maxLength} caracteres`;
    counter.style.color = remaining < 50 ? "#e74c3c" : "#666";
  }

  textarea.addEventListener("input", updateCounter);
  textarea.parentNode.appendChild(counter);
  updateCounter();
}

// Aplicar contador de caracteres
document.addEventListener("DOMContentLoaded", function () {
  const textareas = document.querySelectorAll("textarea");
  textareas.forEach((textarea) => {
    if (!textarea.hasAttribute("maxlength")) {
      textarea.setAttribute("maxlength", "500");
    }
    addCharacterCounter(textarea);
  });
});

// Função para resetar formulário
function resetForm() {
  if (
    confirm(
      "Tem certeza que deseja resetar todos os campos para os valores padrão?"
    )
  ) {
    location.reload();
  }
}

// Atalhos de teclado
document.addEventListener("keydown", function (e) {
  // Ctrl + S para salvar
  if (e.ctrlKey && e.key === "s") {
    e.preventDefault();
    const form = document.getElementById("contentForm");
    if (form) {
      form.submit();
    }
  }

  // Ctrl + P para preview
  if (e.ctrlKey && e.key === "p") {
    e.preventDefault();
    showTab("preview");
  }

  // Ctrl + E para editor
  if (e.ctrlKey && e.key === "e") {
    e.preventDefault();
    showTab("editor");
  }
});

// Mostrar/esconder seções
function toggleSection(sectionId) {
  const section = document.getElementById(sectionId);
  if (section) {
    section.style.display = section.style.display === "none" ? "block" : "none";
  }
}
