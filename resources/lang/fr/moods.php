<?php

return [
    'title' => 'Bienvenue dans le formulaire de Mood Tracker',
    'question' => 'Comment te sens-tu aujourd\'hui ?',
    'greeting' => [
        'morning' => 'Bonjour',
        'afternoon' => 'Bon après-midi',
        'evening' => 'Bonsoir',
        'night' => 'Bonne nuit',
    ],

    //Barra de progreso
    'steps' => [
        'emotion' => 'Barre d\'émotions',
        'questions' => 'Barre de questions',
    ],

    //Pregunta 2)
    'emotion_today' => 'Quelle émotion ressens-tu aujourd\'hui ?',

    //Para emociones
    'emotions' => [
        'happy' => '😊|Heureaux',
        'neutral' => '😐|Neutre',
        'frustrated' => '😤|Frustré',
        'tense' => '😰|Tensionné',
        'calm' => '😌|Calmé',
    ],

    //Preguntas por emoción
    'repondre_questions' => 'Répondre aux questions suivantes',
    // Nuevas etiquetas del formulario cuantitativo
    'quality_question' => ' Comment estimes-tu la qualité de ton travail par rapport à la semaine passée ?',
    'quality_hint' => '1 = très bas · 10 = très haut',

    'intensity_label' => '2) Intensité de l\'émotion (1–5)',
    'intensity_hint' => '1 = très bas · 5 = très haut',
    'cause_question' => 'Pourquoi ressens-tu cette émotion',
    'questions' => [
        'happy' => [
            'q1' => 'Quel niveau d\'énergie et de motivation avez-vous eu aujourd\'hui pour effectuer votre travail par rapport à votre niveau habituel? ',
            'q2' => 'À quel point votre travail a-t-il été fluide et sans interruption aujourd\'hui? ',
            'q3' => 'À quel point vous êtes-vous senti soutenu et valorisé aujourd\'hui par votre équipe et vos responsables? ',
            'q4' => 'À quel point trouvez-vous durable votre niveau de motivation et d\'énergie pour les prochains jours? ',
        ],
        'neutral' => [
            'q1' => 'Quel niveau d\'énergie et de motivation avez-vous eu aujourd\'hui pour effectuer votre travail par rapport à votre niveau habituel? ',
            'q2' => 'À quel point votre travail a-t-il été fluide et sans interruption aujourd\'hui? ',
            'q3' => 'À quel point vous êtes-vous senti soutenu et valorisé aujourd\'hui par votre équipe et vos responsables?',
            'q4' => 'À quel point trouvez-vous durable votre niveau de motivation et d\'énergie pour les prochains jours? ',
        ],
        'frustrated' => [
            'q1' => 'Quel niveau d\'énergie et de motivation avez-vous eu aujourd\'hui pour effectuer votre travail par rapport à votre niveau habituel?',
            'q2' => 'À quel point votre travail a-t-il été fluide et sans interruption aujourd\'hui? ',
            'q3' => 'À quel point vous êtes-vous senti soutenu et valorisé aujourd\'hui par votre équipe et vos responsables? ',
            'q4' => 'À quel point trouvez-vous durable votre niveau de motivation et d\'énergie pour les prochains jours? ',
        ],
        'tense' => [
            'q1' => 'Quel niveau d\'énergie et de motivation avez-vous eu aujourd\'hui pour effectuer votre travail par rapport à votre niveau habituel? ',
            'q2' => 'À quel point votre travail a-t-il été fluide et sans interruption aujourd\'hui? ',
            'q3' => 'À quel point vous êtes-vous senti soutenu et valorisé aujourd\'hui par votre équipe et vos responsables? ',
            'q4' => 'À quel point trouvez-vous durable votre niveau de motivation et d\'énergie pour les prochains jours? ',
        ],
        'calm' => [
            'q1' => 'Quel niveau d\'énergie et de motivation avez-vous eu aujourd\'hui pour effectuer votre travail par rapport à votre niveau habituel? ',
            'q2' => 'À quel point votre travail a-t-il été fluide et sans interruption aujourd\'hui? ',
            'q3' => 'À quel point vous êtes-vous senti soutenu et valorisé aujourd\'hui par votre équipe et vos responsables? ',
            'q4' => 'À quel point trouvez-vous durable votre niveau de motivation et d\'énergie pour les prochains jours? ',
        ],
    ],

    // Textos explicativos para las escalas
    'scale_explanations' => [
        'q1' => '(1=très bas, 5=très haut)',
        'q2' => '(1=très interrumpé, 5=très fluide)',
        'q3' => '(1=très peu soutenu, 5=très soutenu)',
        'q4' => '(1=très insoutenable, 5=très soutenable)',
    ],

    //Causas de la emoción
    'cause' => [
        'question' => 'Pourquoi ressens-tu cette émotion',
        'work' => 'Pour des raisons professionnelles',
        'personal' => 'Pour des raisons personnelles',
        'both' => 'Pour des raisons professionnelles et personnelles',

    ],
    //Botón de envío
    'submit' => 'Envoyer',
    'complete_fields_hint' => 'Veuillez compléter tous les champs avant de soumettre',
    'validated_success' => 'Formulaire validé avec succès ! (Persistance en attente)',

];
