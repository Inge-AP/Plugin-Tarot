<?php
/*
Plugin Name: Tarot Interactivo
Description: Un plugin para mostrar un juego interactivo de tarot.
Version: 1.0
Author: Alejandro Agudelo Toro y Alejandro Grajales Caldas
*/

if (!defined('ABSPATH')) {
    exit; // Evitar acceso directo.
}

// Registrar los scripts y estilos.
function tarot_enqueue_assets() {
    wp_enqueue_style('tarot-estilos', plugins_url('assets/Estilos.css', __FILE__));
    wp_enqueue_script('particles-js', 'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js', [], null, true);
    wp_enqueue_script('tarot-funcionalidad', plugins_url('assets/Funcionalidad.js',__FILE__), ['jquery'], null, true);
    wp_localize_script(
        'tarot-funcionalidad',
        'tarotPluginData',
        [
            'pluginUrl' => plugin_dir_url(__FILE__)
        ]
    );
}

add_action('wp_enqueue_scripts', 'tarot_enqueue_assets');

// Crear el shortcode para mostrar el juego.
function tarot_render_shortcode() {
    ob_start(); ?>
    <div id="particles-js"></div>
    <style>
        .card {
            background-image: url(<?php echo plugins_url('assets/images/card-back.webp',__FILE__); ?>);
        }
        
        #particles-js {
            background-image: url(<?php echo plugins_url('assets/images/IMAGEN-ORIGEN-scaled-1.webp',__FILE__); ?>);
        }

        body {
            background-image: url(<?php echo plugins_url('assets/images/IMAGEN-ORIGEN-scaled-1.webp',__FILE__); ?>);
        }   
    </style>

    <!-- Pantalla 1 con el título y botón -->
    <div class="start-container" id="startContainer">
      <h1>¿Sobre qué tema deseas consultar?</h1>
      <button class="start-button" onclick="startTarot('amor')">Amor</button>
      <button class="start-button" onclick="startTarot('dinero')">
        Dinero
      </button>
      <button class="start-button" onclick="startTarot('futuro')">
        Familia
      </button>
    </div>

    <!-- Pantalla 2: Carrusel de cartas -->
    <div class="carousel-container" id="carouselContainer">
      <div id="cardContainer">
        <h2>Selecciona 3 cartas</h2>
        <!-- Las cartas se generarán aquí dinámicamente -->
      </div>
    </div>
    <!-- Pantalla 3: Resultados -->
    <div class="result-container" id="resultContainer">
      <div class="result-panel">
        <div class="selected-cards" id="selectedCardsContainer"></div>

        <h3 class="title">Elegiste las siguientes cartas</h3>
        <div>
          <div class="card-names" id="selectedCardNames"></div>
          <!-- Contenedor para los nombres de las cartas -->
        </div>
        <div style="flex: auto; display: flex; justify-content: space-between">
          <div class="blinking-arrow">↓</div>
          <div class="blinking-arrow">↓</div>
        </div>
        <h3 class="subtitle">Esto dice tu tirada</h3>
        <div class="description" id="descriptions"></div>
      </div>
      <button class="boton-habla" onclick="nuevaPantalla()">
        Habla con el maestro
      </button>
    </div>

    <!-- Pantalla 4 con el título y el campo de entrada -->
    <div class="form-container" id="formContainer">
      <h4>¿Cuál es tu nombre?</h4>
      <input
        type="text"
        id="nameInput"
        class="name-input"
        placeholder="Escribe tu nombre"
      />
      <button class="start-mensajeria" onclick="startMensajeria()">
        Continuar
      </button>
      <div id="nameErrorMessage" class="name-ErrorMessage none">
        El nombre debe tener al menos 3 caracteres.
      </div>
      <!-- Mensaje de error del nombre -->
    </div>

    <!-- Pantalla 5 con el número de teléfono (oculta inicialmente) -->
    <div class="form-container" id="phoneContainer">
      <h4>¿Cuál es tu número de teléfono?</h4>

      <!-- Desplegable de país -->
      <select id="countryCode" class="country-select">
        <option value="">Seleccione su país</option>
        <option value="+57">Colombia (+57)</option>
        <option value="+56">Chile (+56)</option>
        <option value="+593">Ecuador (+593)</option>
        <option value="+51">Perú (+51)</option>
        <option value="+52">México (+52)</option>
        <option value="+591">Bolivia (+591)</option>
        <option value="+507">Panamá (+507)</option>
      </select>

      <input
        type="text"
        id="phoneInput"
        class="phone-input"
        placeholder="Escribe tu teléfono"
      />
      <button class="start-mensajeria" onclick="submitPhone()">Enviar</button>

      <!-- Mensaje de error (oculto inicialmente) -->
      <div class="error-message" id="numErrorMessage">
        Por favor, escribe un número de telefono valido.
      </div>
      <div class="error-message" id="errorMessage">
        Por favor, selecciona un indicativo.
      </div>
    </div>

    <!-- Mensaje de agradecimiento pantalla 6 (oculto inicialmente) -->
    <div class="thank-you-message" id="thankYouMessage">
      <h1>¡Gracias!</h1>
      <p>En unos momentos el maestro se contactará contigo.</p>
      <button class="start-mensajeria" onclick="goBack()">
        Volver a tirar
      </button>
    </div>
    
    <?php return ob_get_clean();
}
add_shortcode('tarot_interactivo', 'tarot_render_shortcode');