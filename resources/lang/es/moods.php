<?php

return [
    'title' => 'Bienvenido al formulario de Mood Tracker',
    'question' => '¿Cómo te sientes hoy?',
    'greeting' => [
        'morning' => 'Buenos días',
        'afternoon' => 'Buenas tardes',
        'evening' => 'Buenas noches',
        'night' => 'Buenas noches',
    ],

    //Barra de progreso

    'steps' => [
        'emotion' => 'Barra de emociones',
        'questions' => 'Barra de preguntas',
    ],

    //Pregunta 2)
    'emotion_today' => '¿Qué emoción sientes hoy?',

    //Para emociones
    'emotions' => [
        'happy' => '😊|Feliz',
        'neutral' => '😐|Neutral',
        'frustrated' => '😤|Frustrado',
        'tense' => '😰|Tenso',
        'calm' => '😌|Calmado',
    ],
    //Preguntas por emoción
    'repondre_questions' => 'Responde las siguientes preguntas',
    // Nuevas etiquetas del formulario cuantitativo
    'quality_question' => ' ¿Cómo estimas la calidad de tu trabajo respecto a la semana pasada?',
    'quality_hint' => '1 = muy baja · 10 = muy alta',

    'intensity_label' => '2) Intensidad de la emoción (1–5)',
    'intensity_hint' => '1 = muy baja · 5 = muy alta',
    'cause_question' => '¿Por qué sientes esta emoción',

    'questions' => [
        'happy' => [
            'q1' => '¿Qué nivel de energía y motivación has tenido hoy para realizar tu trabajo en comparación con tu nivel habitual?',
            'q2' => '¿Qué tan fluido y sin interrupciones sentiste tu trabajo hoy?',
            'q3' => '¿Qué tan apoyado y valorado te sentiste hoy por tu equipo y responsables?',
            'q4' => '¿Qué tan sostenible sientes que será tu nivel de motivación y energía en los próximos días?',
        ],
        'neutral' => [
            'q1' => '¿Qué nivel de energía y motivación has tenido hoy para realizar tu trabajo en comparación con tu nivel habitual?',
            'q2' => '¿Qué tan fluido y sin interrupciones sentiste tu trabajo hoy?',
            'q3' => '¿Qué tan apoyado y valorado te sentiste hoy por tu equipo y responsables?',
            'q4' => '¿Qué tan sostenible sientes que será tu nivel de motivación y energía en los próximos días?',
        ],
        'frustrated' => [
            'q1' => '¿Qué nivel de energía y motivación has tenido hoy para realizar tu trabajo en comparación con tu nivel habitual?',
            'q2' => '¿Qué tan fluido y sin interrupciones sentiste tu trabajo hoy?',
            'q3' => '¿Qué tan apoyado y valorado te sentiste hoy por tu equipo y responsables?',
            'q4' => '¿Qué tan sostenible sientes que será tu nivel de motivación y energía en los próximos días?',
        ],
        'tense' => [
            'q1' => '¿Qué nivel de energía y motivación has tenido hoy para realizar tu trabajo en comparación con tu nivel habitual?',
            'q2' => '¿Qué tan fluido y sin interrupciones sentiste tu trabajo hoy?',
            'q3' => '¿Qué tan apoyado y valorado te sentiste hoy por tu equipo y responsables?',
            'q4' => '¿Qué tan sostenible sientes que será tu nivel de motivación y energía en los próximos días?',
        ],
        'calm' => [
            'q1' => '¿Qué nivel de energía y motivación has tenido hoy para realizar tu trabajo en comparación con tu nivel habitual?',
            'q2' => '¿Qué tan fluido y sin interrupciones sentiste tu trabajo hoy?',
            'q3' => '¿Qué tan apoyado y valorado te sentiste hoy por tu equipo y responsables?',
            'q4' => '¿Qué tan sostenible sientes que será tu nivel de motivación y energía en los próximos días?',
        ],
    ],

    // Textos explicativos para las escalas
    'scale_explanations' => [
        'q1' => '(1=muy bajo, 5=muy alto)',
        'q2' => '(1=muy interrumpido, 5=muy fluido)',
        'q3' => '(1=muy poco apoyado, 5=muy apoyado)',
        'q4' => '(1=muy insostenible, 5=muy sostenible)',
    ],

    //Causas de la emoción
    'cause' => [
        'question' => '¿Por qué sientes esta emoción',
        'work' => 'Por motivos del trabajo',
        'personal' => 'Por motivos personales',
        'both' => 'Por ambos motivos',

    ],
    //Botón de envío
    'submit' => 'Enviar',
    'complete_fields_hint' => 'Por favor, complete todos los campos antes de enviar',
    'validated_success' => '¡Formulario validado correctamente! (Persistencia pendiente)',

];
