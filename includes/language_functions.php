// includes/language_functions.php
<?php
function getAvailableLanguages() {
    return [
        'fr' => ['name' => 'Français', 'flag' => 'fr.png'],
        'en' => ['name' => 'English', 'flag' => 'gb.png'],
        'ar' => ['name' => 'العربية', 'flag' => 'ma.png', 'dir' => 'rtl']
    ];
}

function setLanguage($lang) {
    if (array_key_exists($lang, getAvailableLanguages())) {
        $_SESSION['lang'] = $lang;
        return true;
    }
    return false;
}

function translate($key, $lang = null) {
    $lang = $lang ?? $_SESSION['lang'] ?? 'fr';
    
    $translations = [
        'home' => [
            'fr' => 'Accueil',
            'en' => 'Home',
            'ar' => 'الرئيسية'
        ],
        'properties' => [
            'fr' => 'Biens immobiliers',
            'en' => 'Properties',
            'ar' => 'العقارات'
        ],
        // Ajoutez toutes vos traductions ici
    ];
    
    return $translations[$key][$lang] ?? $key;
}