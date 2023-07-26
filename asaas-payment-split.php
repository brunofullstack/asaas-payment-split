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



// ASAAS WEBHOOK
// Função de callback para a rota personalizada POST

add_action( 'rest_api_init', 'wp_asaas_split_registrar_rotas' );
// Registrando a rota personalizada para tratar as requisições POST do Asaas Webhook
function wp_asaas_split_registrar_rotas() {
    register_rest_route(
        'asaas-payment-split/v1', // Prefixo da rota
        '/asaas-webhook', // Caminho da rota
        array(
            'methods'  => 'POST', // Método permitido (POST)
            'callback' => 'wp_plugin_asaas_split_callback', // Função de callback
            'permission_callback' => '__return_true',
        )
    );
}
function wp_plugin_asaas_split_callback( WP_REST_Request $request ) {
    // Recupere os dados enviados na requisição POST
    $data = $request->get_params();
    $payment_status = $data["event"];

    switch ( $payment_status ) {
        case 'PAYMENT_CREATED':
            // Geração de nova cobrança.
            echo 'Geração de nova cobrança.';
            break;

        case 'PAYMENT_AWAITING_RISK_ANALYSIS':
            // Pagamento em cartão aguardando aprovação pela análise manual de risco.
            echo 'Pagamento em cartão aguardando aprovação pela análise manual de risco.';
            break;

        case 'PAYMENT_APPROVED_BY_RISK_ANALYSIS':
            // Pagamento em cartão aprovado pela análise manual de risco.
            echo 'Pagamento em cartão aprovado pela análise manual de risco.';
            break;

        case 'PAYMENT_REPROVED_BY_RISK_ANALYSIS':
            // Pagamento em cartão reprovado pela análise manual de risco.
            echo 'Pagamento em cartão reprovado pela análise manual de risco.';
            break;

        case 'PAYMENT_UPDATED':
            // Alteração no vencimento ou valor de cobrança existente.
            echo 'Alteração no vencimento ou valor de cobrança existente.';
            break;

        case 'PAYMENT_CONFIRMED':
            // Cobrança confirmada (pagamento efetuado, porém o saldo ainda não foi disponibilizado).
            echo 'Cobrança confirmada (pagamento efetuado, porém o saldo ainda não foi disponibilizado).';
            break;

        case 'PAYMENT_RECEIVED':
            // Cobrança recebida.
            echo 'Cobrança recebida.';
            break;

        case 'PAYMENT_ANTICIPATED':
            // Cobrança antecipada.
            echo 'Cobrança antecipada.';
            break;

        case 'PAYMENT_OVERDUE':
            // Cobrança vencida.
            echo 'Cobrança vencida.';
            break;

        case 'PAYMENT_DELETED':
            // Cobrança removida.
            echo 'Cobrança removida.';
            break;

        case 'PAYMENT_RESTORED':
            // Cobrança restaurada.
            echo 'Cobrança restaurada.';
            break;

        case 'PAYMENT_REFUNDED':
            // Cobrança estornada.
            echo 'Cobrança estornada.';
            break;

        case 'PAYMENT_REFUND_IN_PROGRESS':
            // Estorno em processamento (liquidação já está agendada, cobrança será estornada após executar a liquidação).
            echo 'Estorno em processamento (liquidação já está agendada, cobrança será estornada após executar a liquidação).';
            break;

        case 'PAYMENT_RECEIVED_IN_CASH_UNDONE':
            // Recebimento em dinheiro desfeito.
            echo 'Recebimento em dinheiro desfeito.';
            break;

        case 'PAYMENT_CHARGEBACK_REQUESTED':
            // Recebido chargeback.
            echo 'Recebido chargeback.';
            break;

        case 'PAYMENT_CHARGEBACK_DISPUTE':
            // Em disputa de chargeback (caso sejam apresentados documentos para contestação).
            echo 'Em disputa de chargeback (caso sejam apresentados documentos para contestação).';
            break;

        case 'PAYMENT_AWAITING_CHARGEBACK_REVERSAL':
            // Disputa vencida, aguardando repasse da adquirente.
            echo 'Disputa vencida, aguardando repasse da adquirente.';
            break;

        case 'PAYMENT_DUNNING_RECEIVED':
            // Recebimento de negativação.
            echo 'Recebimento de negativação.';
            break;

        case 'PAYMENT_DUNNING_REQUESTED':
            // Requisição de negativação.
            echo 'Requisição de negativação.';
            break;

        case 'PAYMENT_BANK_SLIP_VIEWED':
            // Boleto da cobrança visualizado pelo cliente.
            echo 'Boleto da cobrança visualizado pelo cliente.';
            break;

        case 'PAYMENT_CHECKOUT_VIEWED':
            // Fatura da cobrança visualizada pelo cliente.
            echo 'Fatura da cobrança visualizada pelo cliente.';
            break;

        default:
            // Resposta não reconhecida.
            echo 'Resposta não reconhecida.';
            break;
    }

    // Responda ao Asaas Webhook com um status de sucesso
    return rest_ensure_response( array( 'status' => 'sucesso' ) );
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
