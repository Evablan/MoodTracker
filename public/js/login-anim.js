/**
 * Login Page - Parallax en blobs respirando
 * Solo aplica parallax a los blobs con animación de respiración
 */

(function () {
    'use strict';

    // Verificar si el usuario prefiere movimiento reducido
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
        // Si prefiere movimiento reducido, no aplicamos parallax
        return;
    }

    // Obtener el contenedor de blobs
    const blobsContainer = document.getElementById('blobs-container');
    if (!blobsContainer) return;

    // Obtener todos los blobs que tienen animación de respiración
    const breathingBlobs = blobsContainer.querySelectorAll('[class*="blob-breathing"]');
    if (breathingBlobs.length === 0) return;

    let rafId = null;
    let mouseX = window.innerWidth / 2;
    let mouseY = window.innerHeight / 2;

    /**
     * Actualiza la posición de los blobs con parallax
     * El parallax es sutil (máximo ±16px) y se suma a la animación de respiración
     */
    function updateParallax() {
        breathingBlobs.forEach((blob, index) => {
            // Intensidad diferente para cada blob (más sutil)
            const intensity = (index + 1) * 0.3;
            const maxOffset = 16; // Máximo ±16px según specs

            // Calcular offset basado en posición del ratón
            const offsetX = (mouseX / window.innerWidth - 0.5) * maxOffset * intensity;
            const offsetY = (mouseY / window.innerHeight - 0.5) * maxOffset * intensity;

            // Aplicar transformación (se suma a la animación CSS de respiración)
            // Usamos translate3d para mejor performance
            blob.style.transform = `translate3d(${offsetX}px, ${offsetY}px, 0)`;
        });

        rafId = null;
    }

    /**
     * Escuchar movimiento del ratón
     * Usamos requestAnimationFrame para optimizar (60fps)
     */
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;

        // Solo actualizar si no hay una animación frame pendiente
        if (!rafId) {
            rafId = requestAnimationFrame(updateParallax);
        }
    }, { passive: true });

    // Inicializar posición inicial
    updateParallax();
})();

