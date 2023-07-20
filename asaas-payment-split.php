<?php
/**
 * Plugin Name: Asaas Payment Split
 * Description: Assas payment split for woocommerce checkout
 * Version: 1.0.0
 * Author: Bruno Fullstack
 * Author URI: https://brunofullstack.github.io/
 */

// Bloquear acesso direto ao arquivo do plugin
if (!defined('ABSPATH')) {
    exit;
}

/* CONFIG PAGE */
// Adiciona uma página de configuração para o plugin
add_action('admin_menu', 'wp_g_h_w_plugin_menu');
function wp_g_h_w_plugin_menu()
{
    add_menu_page(
        'Configurações de Split de pagamentos',
        // título da página
        'Split de pagamentos Asaas',
        // título do menu
        'manage_options',
        // permissão de acesso
        'wp-change-hw-plugin',
        // slug da página
        'wp_g_h_w_plugin_settings_page' // função que renderiza a página
    );
}
// Cria o conteúdo da página de configuração
function wp_g_h_w_plugin_settings_page()
{

    if (!current_user_can('manage_options')) {
        return;
    }

    $altura = get_option('wp_g_h_w_plugin_altura');
    $largura = get_option('wp_g_h_w_plugin_largura');
    $preco_base_0_05 = get_option('wp_g_h_w_plugin_preco_base_0_05');
    $preco_base_05_1 = get_option('wp_g_h_w_plugin_preco_base_05_1');
    $preco_base_1_3 = get_option('wp_g_h_w_plugin_preco_base_1_3');
    $preco_base_3_5 = get_option('wp_g_h_w_plugin_preco_base_3_5');

    if (isset($_POST['submit'])) {
        $altura = $_POST['wp_g_h_w_plugin_altura'];
        $largura = $_POST['wp_g_h_w_plugin_largura'];
        $preco_base_0_05 = $_POST['wp_g_h_w_plugin_preco_base_0_05'];
        $preco_base_05_1 = $_POST['wp_g_h_w_plugin_preco_base_05_1'];
        $preco_base_1_3 = $_POST['wp_g_h_w_plugin_preco_base_1_3'];
        $preco_base_3_5 = $_POST['wp_g_h_w_plugin_preco_base_3_5'];

        update_option('wp_g_h_w_plugin_altura', $altura);
        update_option('wp_g_h_w_plugin_largura', $largura);
        update_option('wp_g_h_w_plugin_preco_base', $preco_base_0_05);
        update_option('wp_g_h_w_plugin_preco_base', $preco_base_05_1);
        update_option('wp_g_h_w_plugin_preco_base', $preco_base_1_3);
        update_option('wp_g_h_w_plugin_preco_base', $preco_base_3_5);
    }

    ?>
    <div class="wrap">
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        

        

        <form method="post">
            <input type="hidden" name="action" value="salvar_informacoes">
            <input type="hidden" name="my_form" value="1">


            <?php include 'form.html'; ?>


            <?php //submit_button(); ?>
        </form>

    </div>
    <?php
}

function get_setted_values()
{
    global $wpdb;

    // Nome da tabela
    $nome_tabela = $wpdb->prefix . 'wp_global_height_width_plugin';

    // Consulta SQL para obter o primeiro registro
    $sql = "SELECT * FROM wp_global_height_width_plugin LIMIT 1";

    // Obter o primeiro registro da tabela
    $registro = $wpdb->get_row($sql);
    // var_dump($resultados[0]);

    // Verificar se há resultados
    if ($registro) {
        $campo1 = $registro->altura_maxima;
        $campo2 = $registro->largura_maxima;
        $campo3 = $registro->preco_0_05;
        $campo4 = $registro->preco_05_1;
        $campo5 = $registro->preco_1_3;
        $campo6 = $registro->preco_3_5;
        // Montar um formulário com os campos preenchidos pelos valores do registro
        echo '<h2>Tamanho</h2>';
        echo '<table class="form-table"><tbody><tr><th scope="row"><label for="wp_g_h_w_plugin_altura">Altura máxima (cm):</label></th>';
        echo '<td>';
        echo '<input required name="wp_g_h_w_plugin_altura" type="number" step="0.01" id="wp_g_h_w_plugin_altura" value="' . $campo1 . '" class="regular-text">';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="wp_g_h_w_plugin_largura">Largura máxima (cm):</label></th>';
        echo '<td>';
        echo '<input required name="wp_g_h_w_plugin_largura" type="number" step="0.01" id="wp_g_h_w_plugin_largura" value="' . $campo2 . '" class="regular-text">';
        echo '</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';


        echo '<h2>Preços</h2>';
        echo '<table class="form-table"><tbody><tr><th scope="row"><label for="wp_g_h_w_plugin_preco_base_0_05">0 a 0,5M² em R$:</label></th>';
        echo '<td>';
        echo '<input required name="wp_g_h_w_plugin_preco_base_0_05" type="number" step="0.01" id="wp_g_h_w_plugin_preco_base_0_05" value="' . $campo3 . '" class="regular-text">';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="wp_g_h_w_plugin_preco_base_05_1">0,5 a 1M² em R$:</label></th>';
        echo '<td>';
        echo '<input required name="wp_g_h_w_plugin_preco_base_05_1" type="number" step="0.01" id="wp_g_h_w_plugin_preco_base_05_1" value="' . $campo4 . '" class="regular-text">';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="wp_g_h_w_plugin_preco_base_1_3">1 a 3M² em R$:</label></th>';
        echo '<td>';
        echo '<input required name="wp_g_h_w_plugin_preco_base_1_3" type="number" step="0.01" id="wp_g_h_w_plugin_preco_base_1_3" value="' . $campo5 . '" class="regular-text">';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th scope="row"><label for="wp_g_h_w_plugin_preco_base_3_5">3 a 5M² em R$:</label></th>';
        echo '<td>';
        echo '<input required name="wp_g_h_w_plugin_preco_base_3_5" type="number" step="0.01" id="wp_g_h_w_plugin_preco_base_3_5" value="' . $campo6 . '" class="regular-text">';
        echo '</td>';
        echo '</tr>';
        echo '</tbody>';
        echo '</table>';
        echo '<p>Todos os campos são obrigarórios<span style="color: #F03764"> *</span></p>';

    } else {
        include 'form.html';
    }
}

// Registro de ativação do plugin
function meu_plugin_ativacao()
{
    // Executar ações durante a ativação do plugin
}
register_activation_hook(__FILE__, 'meu_plugin_ativacao');

// Registro de desativação do plugin
function meu_plugin_desativacao()
{
    // Executar ações durante a desativação do plugin
}
register_deactivation_hook(__FILE__, 'meu_plugin_desativacao');

// Registro de desinstalação do plugin
function meu_plugin_desinstalacao()
{
    // Executar ações durante a desinstalação do plugin
}
register_uninstall_hook(__FILE__, 'meu_plugin_desinstalacao');

// Adicionar funcionalidade ao WordPress ou WooCommerce
function meu_plugin_funcionalidade()
{
    // Código da funcionalidade aqui
}
add_action('woocommerce_checkout_order_processed', 'meu_plugin_funcionalidade');
