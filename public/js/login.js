/**
 * Login Page Interactions
 * - Toggle mostrar/ocultar password
 * - Estados de loading en el formulario
 * - Estados de loading en el botón de Google
 */

(function () {
    'use strict';

    // ============================================
    // 1. TOGGLE PASSWORD VISIBILITY
    // ============================================
    const passwordToggle = document.getElementById('password-toggle');
    const passwordInput = document.getElementById('password');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function (e) {
            e.preventDefault();
            const isPassword = passwordInput.type === 'password';

            // Cambiar tipo de input
            passwordInput.type = isPassword ? 'text' : 'password';

            // Actualizar texto y aria-pressed
            passwordToggle.textContent = isPassword ? 'HIDE' : 'SHOW';
            passwordToggle.setAttribute('aria-pressed', isPassword ? 'true' : 'false');

            // Mantener foco en el input
            passwordInput.focus();
        });
    }

    // ============================================
    // 2. FORM SUBMISSION & LOADING STATES
    // ============================================
    const loginForm = document.getElementById('login-form');
    const submitButton = document.getElementById('submit-button');
    const emailInput = document.getElementById('email');
    const passwordInputForm = document.getElementById('password');

    if (loginForm && submitButton) {
        loginForm.addEventListener('submit', function (e) {
            // Validación básica (Laravel también valida en backend)
            if (!emailInput.value || !passwordInputForm.value) {
                return; // Dejar que el navegador muestre el error nativo
            }

            // Desactivar inputs
            emailInput.disabled = true;
            passwordInputForm.disabled = true;
            if (passwordToggle) passwordToggle.disabled = true;

            // Estado de loading en el botón
            submitButton.disabled = true;
            submitButton.classList.add('loading');

            // Cambiar texto del botón
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = `
                <span class="spinner" aria-hidden="true"></span>
                <span>Processing...</span>
            `;

            // Si hay un error de validación, Laravel redirige de vuelta
            // y los inputs se reactivan automáticamente
            // Si el form se envía correctamente, la página cambia
        });
    }

    // ============================================
    // 3. GOOGLE LOGIN BUTTON LOADING STATE
    // ============================================
    const googleButton = document.getElementById('google-login-button');

    if (googleButton) {
        googleButton.addEventListener('click', function (e) {
            // Agregar estado de loading
            this.classList.add('loading');
            this.style.pointerEvents = 'none';

            // Cambiar contenido temporalmente (se redirige inmediatamente)
            const originalContent = this.innerHTML;
            this.innerHTML = `
                <span class="spinner" aria-hidden="true"></span>
                <span>Redirecting to Google...</span>
            `;

            // Si por alguna razón no se redirige, restaurar después de 3 segundos
            setTimeout(() => {
                if (this.classList.contains('loading')) {
                    this.classList.remove('loading');
                    this.style.pointerEvents = '';
                    this.innerHTML = originalContent;
                }
            }, 3000);
        });
    }

    // ============================================
    // 4. FOCUS MANAGEMENT PARA ACCESIBILIDAD
    // ============================================
    // Asegurar que los errores de validación sean anunciados
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => {
        if (error.textContent.trim()) {
            error.setAttribute('role', 'alert');
            error.setAttribute('aria-live', 'polite');
        }
    });

    // Focus visible mejorado
    const focusableElements = document.querySelectorAll('input, button, a');
    focusableElements.forEach(el => {
        el.addEventListener('focus', function () {
            this.classList.add('focus-visible');
        });
        el.addEventListener('blur', function () {
            this.classList.remove('focus-visible');
        });
    });

})();

