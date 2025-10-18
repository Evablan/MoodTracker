//Obtener elementos del DOM

document.addEventListener('DOMContentLoaded', function () {

    //Obtener el elemento del radio button
    const emotionRadio = document.querySelectorAll('input[name="emotion"]'); //Obtener el elemento del radio button de la emoción
    const workQuality = document.querySelector('input[name="work_quality"]'); //Obtener el elemento del radio button de la calidad del trabajo
    const questionsContainer = document.getElementById('questions'); //Obtener el contenedor de las preguntas

    //Elementos de la barra de progreso

    const step1 = document.getElementById('step-1'); //Obtener el elemento del paso 1
    const step2 = document.getElementById('step-2'); //Obtener el elemento del paso 2

    //Obtenemos las preguntas

    const q1 = document.getElementById('q1'); //Obtener el elemento de la pregunta 1    
    const q2 = document.getElementById('q2'); //Obtener el elemento de la pregunta 2
    const q3 = document.getElementById('q3'); //Obtener el elemento de la pregunta 3
    const q4 = document.getElementById('q4'); //Obtener el elemento de la pregunta 4

    //EventListener para emociones (escuchar cambios en la selección de la emoción)

    emotionRadio.forEach(radio => {
        radio.addEventListener('change', function () {
            const selectedEmotion = radio.value;
            const emotionLabel = this.dataset.label;

            //Mostrar sección de preguntas
            showQuestions();

            //Actualizar barra de progreso (comentado para mantener diseño fijo)
            updateProgressBar(2)

            //Cargar preguntas específicas por emoción
            loadEmotionQuestions(selectedEmotion);

            //Actualizar la emoción seleccionada en la pregunta 3
            updateSelectedEmotion(emotionLabel);
        });
    });
    function showQuestions() {
        questionsContainer.classList.remove('hidden');
        questionsContainer.classList.add('space-y-6');
    }

    function updateProgressBar(currentStep) {
        if (currentStep === 2) {
            // Activar paso 2 en azul
            step2.classList.remove('bg-gray-300', 'text-gray-500');
            step2.classList.add('bg-blue-500', 'text-white');

            // Actualizar texto del paso 2
            step2.nextElementSibling.classList.remove('text-gray-400');
            step2.nextElementSibling.classList.add('text-gray-800');
        }
    }

    //Función para cargar las preguntas específicas por emoción
    function loadEmotionQuestions(emotion) {
        if (window.emotionQuestions && window.emotionQuestions[emotion]) { //Verificar que existen preguntas para esta emoción
            const questions = window.emotionQuestions[emotion]; //Obtener las preguntas para esta emoción

            // Obtener todas las claves que empiecen con 'q' y un número
            const questionKeys = Object.keys(questions).filter(key => key.match(/^q\d+$/)); //Obtener todas las claves que empiecen con 'q' y un número

            questionKeys.forEach((key, index) => { //Actualizar cada pregunta
                const questionElement = document.getElementById(`q${index + 1}`); //Obtener el elemento de la pregunta
                if (questionElement) {
                    questionElement.textContent = `${index + 1}. ${questions[key]}`; //Actualizar el texto de la pregunta
                }
            });

            console.log(`✅ Preguntas actualizadas para ${emotion}`); //Mostrar un mensaje de éxito
        } else {
            console.warn(`❌ No hay preguntas para: ${emotion}`); //Mostrar un mensaje de error 
        }
    }

    //Función para actualizar la emoción seleccionada en la pregunta 3
    function updateSelectedEmotion(emotionLabel) {
        const selectedEmotionSpan = document.getElementById('selectedEmotion');
        if (selectedEmotionSpan && emotionLabel) {
            selectedEmotionSpan.textContent = `(${emotionLabel})`;
            selectedEmotionSpan.style.display = 'inline-block';
        }
    }

    //Función para validar el formulario completo

    function validateCompleteForm() {
        const workQuality = document.querySelector('input[name="work_quality"]:checked');
        const emotion = document.querySelector('input[name="emotion"]:checked');
        const cause = document.querySelector('input[name="cause"]:checked');

        // Validar preguntas dinámicas
        const answers = [];
        for (let i = 1; i <= 4; i++) {
            const answer = document.querySelector(`input[name="answer_${i}"]:checked`);
            answers.push(answer);
        }

        const allAnswered = answers.every(answer => answer !== null);

        return {
            isValid: workQuality && emotion && cause && allAnswered,
            missing: {
                workQuality: !workQuality,
                emotion: !emotion,
                cause: !cause,
                questions: !allAnswered
            }
        };
    }
    //Función para actualizar el botón de envío
    function updateSubmitButton() {
        const validation = validateCompleteForm();
        const submitBtn = document.getElementById('submitBtn');

        if (validation.isValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.add('opacity-100', 'cursor-pointer');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.classList.remove('opacity-100', 'cursor-pointer');
        }


    }




    // ✅ EVENT LISTENERS para validación en tiempo real
    const workQualityRadios = document.querySelectorAll('input[name="work_quality"]');
    const causeRadios = document.querySelectorAll('input[name="cause"]');

    // Escuchar cambios en work_quality
    workQualityRadios.forEach(radio => {
        radio.addEventListener('change', updateSubmitButton);
    });

    // Escuchar cambios en cause
    causeRadios.forEach(radio => {
        radio.addEventListener('change', updateSubmitButton);
    });

    // Escuchar cambios en respuestas dinámicas
    for (let i = 1; i <= 4; i++) {
        const answerRadios = document.querySelectorAll(`input[name="answer_${i}"]`);
        answerRadios.forEach(radio => {
            radio.addEventListener('change', updateSubmitButton);
        });
    }

    // Validar al cargar la página
    updateSubmitButton();

});

document.addEventListener('DOMContentLoaded', () => {
    const emoHidden = document.getElementById('emotion_key');
    const causeHidden = document.getElementById('cause_key');

    // Inicializa con lo que esté marcado
    const emoChecked = document.querySelector('input[name="emotion"]:checked');
    const causeChecked = document.querySelector('input[name="cause"]:checked');
    if (emoChecked) emoHidden.value = emoChecked.value;
    if (causeChecked) causeHidden.value = causeChecked.value;

    // Actualiza cuando el usuario cambie selección
    document.querySelectorAll('input[name="emotion"]').forEach(r => {
        r.addEventListener('change', e => { emoHidden.value = e.target.value; });
    });
    document.querySelectorAll('input[name="cause"]').forEach(r => {
        r.addEventListener('change', e => { causeHidden.value = e.target.value; });
    });
});
