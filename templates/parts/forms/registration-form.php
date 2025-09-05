<?php
if (!defined('ABSPATH')) {
    exit; // Salir si se accede directamente
}

// Iniciar sesión si no está iniciada
if (!session_id()) {
    session_start();
}

// Obtener errores y datos del formulario de la sesión
$registration_errors = isset($_SESSION['registration_errors']) ? $_SESSION['registration_errors'] : array();
$form_data = isset($_SESSION['registration_form_data']) ? $_SESSION['registration_form_data'] : array();

// Valores por defecto para los campos
$first_name = isset($form_data['first_name']) ? $form_data['first_name'] : '';
$last_name = isset($form_data['last_name']) ? $form_data['last_name'] : '';
$email = isset($form_data['email']) ? $form_data['email'] : '';
$tipo_usuario = isset($form_data['tipo_usuario']) ? $form_data['tipo_usuario'] : 'voluntario';
$accept_terms = isset($form_data['accept_terms']) ? 'checked' : '';
?>

<div class="registration-form-container">
    <!-- Encabezado del formulario -->
    <div class="registration-header">
        <a href="<?php echo home_url(); ?>">
            <img decoding="async" src="https://humanitarios.do/wp-content/uploads/2025/01/Logotipo-horizontal-e1736620178908.png" alt="Registration">
        </a>
        <h5><?php esc_html_e('¡Estamos encantados de darle la bienvenida como miembro de nuestra comunidad!', 'workreap'); ?></h5>
    </div>

    <!-- Formulario de registro -->
    <form id="registration-form" method="post" action="">
        <?php wp_nonce_field('humanitarios_registration_nonce', 'registration_nonce'); ?>

        <!-- Fila: Nombre y Apellido -->
        <div class="bloque-form">
            <div class="form-group">
                <label for="first_name"><?php esc_html_e('Nombre', 'workreap'); ?></label>
                <input id="first_name" type="text" name="user_registration[first_name]" placeholder="Nombre*" value="<?php echo esc_attr($first_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name"><?php esc_html_e('Apellido', 'workreap'); ?></label>
                <input id="last_name" type="text" name="user_registration[last_name]" placeholder="Apellido*" value="<?php echo esc_attr($last_name); ?>" required>
            </div>
        </div>

        <!-- Fila: Correo y Contraseña -->
        <div class="bloque-form">
            <div class="form-group">
                <label for="email"><?php esc_html_e('Correo', 'workreap'); ?></label>
                <input id="email" type="email" name="user_registration[email]" placeholder="Tu correo*" value="<?php echo esc_attr($email); ?>" required>
            </div>
            <div class="form-group">
                <label for="password"><?php esc_html_e('Contraseña', 'workreap'); ?></label>
                <input id="password" type="password" name="user_registration[password]" placeholder="Contraseña*" required>
            </div>
        </div>

        <!-- Fila: Tipo de usuario -->
        <div class="form-group full-width user-type">
            <label><?php esc_html_e('Tipo de usuario', 'workreap'); ?></label>
            <div class="user-type-options">
                <div class="option">
                    <input id="tipo_usuario_voluntario" type="radio" value="voluntario" name="user_registration[tipo_usuario]" <?php echo ($tipo_usuario === 'voluntario') ? 'checked' : ''; ?> required>
                    <label for="tipo_usuario_voluntario"><?php esc_html_e('Voluntario', 'workreap'); ?></label>
                </div>
                <div class="option">
                    <input id="tipo_usuario_fundacion" type="radio" value="fundacion" name="user_registration[tipo_usuario]" <?php echo ($tipo_usuario === 'fundacion') ? 'checked' : ''; ?> required>
                    <label for="tipo_usuario_fundacion"><?php esc_html_e('Fundación', 'workreap'); ?></label>
                </div>
            </div>
        </div>

        <!-- Fila: Aceptar términos -->
        <div class="form-group full-width tems-group">
            <input id="accept_terms" type="checkbox" name="user_registration[accept_terms]" <?php echo $accept_terms; ?> required>
            <label for="accept_terms"><?php esc_html_e('Acepto los términos y condiciones', 'workreap'); ?></label>
        </div>

        <!-- Mostrar errores si existen -->
        <?php if (!empty($registration_errors)) : ?>
            <div class="registration-errors">
                <?php foreach ($registration_errors as $error) : ?>
                    <p class="error-message"><?php echo esc_html($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Botón de registro -->
        <div class="form-group full-width">
            <button type="submit" name="humanitarios_register" class="btn btn-primary">
                <?php esc_html_e('Registrar ahora', 'workreap'); ?>
            </button>
        </div>
    </form>

    <!-- Enlace para iniciar sesión -->
    <div class="login-link">
        <?php esc_html_e('¿Ya tienes una cuenta?', 'workreap'); ?>
        <a href="../login/"><?php esc_html_e('Iniciar sesión', 'workreap'); ?></a>
    </div>
</div>

<?php
// Limpiar errores después de mostrarlos
unset($_SESSION['registration_errors']);
unset($_SESSION['registration_form_data']);
?>