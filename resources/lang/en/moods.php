<?php
return [
    'title' => 'Welcome to the Mood Tracker form',
    'question' => 'How do you feel today?',
    'greeting' => [
        'morning' => 'Good morning',
        'afternoon' => 'Good afternoon',
        'evening' => 'Good evening',
        'night' => 'Good night',
    ],
    //Barra de progreso
    'steps' => [
        'emotion' => 'Emotion bar',
        'questions' => 'Questions bar',
    ],

    //Pregunta 2)
    'emotion_today' => 'What emotion do you feel today?',

    //Para emociones
    'emotions' => [
        'happy' => '😊|Happy',
        'neutral' => '😐|Neutral',
        'frustrated' => '😤|Frustrated',
        'tense' => '😰|Tense',
        'calm' => '😌|Calm',
    ],

    //Preguntas por emoción
    'repondre_questions' => 'Answer the following questions',

    // Nuevas etiquetas del formulario cuantitativo
    'quality_question' => '¿How would you rate the quality of your work compared to last week??',
    'quality_hint' => '1 = very low · 10 = very high',

    'intensity_label' => '2) Emotion intensity (1–5)',
    'intensity_hint' => '1 = very low · 5 = very high',
    'cause_question' => 'Why do you feel this emotion',
    'questions' => [
        'happy' => [
            'q1' => 'What level of energy and motivation did you have today to perform your work compared to your usual level? ',
            'q2' => 'How fluid and uninterrupted did you feel your work was today? ',
            'q3' => 'How supported and valued did you feel today by your team and supervisors? ',
            'q4' => 'How sustainable do you feel your level of motivation and energy will be in the coming days? ',
        ],
        'neutral' => [
            'q1' => 'What level of energy and motivation did you have today to perform your work compared to your usual level? ',
            'q2' => 'How fluid and uninterrupted did you feel your work was today? ',
            'q3' => 'How supported and valued did you feel today by your team and supervisors? ',
            'q4' => 'How sustainable do you feel your level of motivation and energy will be in the coming days?',
        ],
        'frustrated' => [
            'q1' => 'What level of energy and motivation did you have today to perform your work compared to your usual level? ',
            'q2' => 'How fluid and uninterrupted did you feel your work was today? ',
            'q3' => 'How supported and valued did you feel today by your team and supervisors? ',
            'q4' => 'How sustainable do you feel your level of motivation and energy will be in the coming days?',
        ],
        'tense' => [
            'q1' => 'What level of energy and motivation did you have today to perform your work compared to your usual level? ',
            'q2' => 'How fluid and uninterrupted did you feel your work was today? ',
            'q3' => 'How supported and valued did you feel today by your team and supervisors? ',
            'q4' => 'How sustainable do you feel your level of motivation and energy will be in the coming days? ',
        ],
        'calm' => [
            'q1' => 'What level of energy and motivation did you have today to perform your work compared to your usual level? ',
            'q2' => 'How fluid and uninterrupted did you feel your work was today? ',
            'q3' => 'How supported and valued did you feel today by your team and supervisors? ',
            'q4' => 'How sustainable do you feel your level of motivation and energy will be in the coming days? ',
        ],
    ],

    // Textos explicativos para las escalas
    'scale_explanations' => [
        'q1' => '(1=very low, 5=very high)',
        'q2' => '(1=very interrupted, 5=very fluid)',
        'q3' => '(1=very little supported, 5=very supported)',
        'q4' => '(1=very unsustainable, 5=very sustainable)',
    ],

    //Causas de la emoción
    'cause' => [
        'question' => 'Why do you feel this emotion',
        'work' => 'For work reasons',
        'personal' => 'For personal reasons',
        'both' => 'For both reasons',
    ],

    //Botón de envío
    'submit' => 'Submit',
    'complete_fields_hint' => 'Please complete all fields before submitting',
    'validated_success' => 'Form successfully validated! (Persistence pending)',
];
