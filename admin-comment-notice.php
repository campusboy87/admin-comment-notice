<?php
/*
Plugin Name: Admin Comment Notice
Plugin URI: https://wp-kama.ru/id_9759/heartbeat-api.html
Description: Уведомляет авторизованного пользователя о новых комментариях, если у него есть права на их модерацию.
Author: campusboy
Author URI: https://wp-plus.ru
Version: 1.0
*/

add_action( 'wp_enqueue_scripts', 'acn_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'acn_enqueue_scripts' );
add_filter( 'heartbeat_send', 'acn_heartbeat_send' );

/**
 * Добавляет данные в heartbeat ответ.
 *
 * @param array $response
 *
 * @return array
 */
function acn_heartbeat_send( $response ) {
	if ( ! current_user_can( 'moderate_comments' ) ) {
		return $response;
	}

	$count = wp_count_comments();
	$count = absint( $count->moderated );
	$i18n  = number_format_i18n( $count );

	// Админ-сайдбар
	$menu = '<span class="awaiting-mod count-' . $count . '"><span class="pending-count">' . $i18n . '</span></span>';
	$menu = sprintf( __( 'Comments %s' ), $menu );

	// Админ-бар
	$text = sprintf( _n( '%s comment awaiting moderation', '%s comments awaiting moderation', $count ), $i18n );
	$bar  = '<span class="ab-icon"></span>';
	$bar  .= '<span class="ab-label awaiting-mod pending-count count-' . $count . '" aria-hidden="true">' . $i18n . '</span>';
	$bar  .= '<span class="screen-reader-text">' . $text . '</span>';

	// Данные
	$response['acn'] = array(
		'menu'  => $menu,
		'bar'   => $bar,
		'count' => $i18n,
	);

	return $response;
}

/**
 * Подключает скрипт плагина.
 */
function acn_enqueue_scripts() {
	if ( is_admin_bar_showing() && current_user_can( 'moderate_comments' ) ) {
		$script_url = plugins_url( 'scripts.js', __FILE__ );
		wp_enqueue_script( 'acn-script', $script_url, array( 'heartbeat' ) );
	}
}