<?php
/*
Plugin Name: WT Portfolio
Plugin URI: http://web-technology.biz/cms-wordpress/plugin-wt-portfolio
Description: Удобное управление элементами портфолио
Version: 0.1
Author: Роман Кусты, АИТ "Web-Techology"
Author URI: http://web-technology.biz
*/

class WtPortfolio
{
	function __construct(){
		add_action('init', array($this, 'register_post_type_portfolio'));
		add_filter('post_updated_messages', array($this, 'post_type_portfolio_messages'));
		add_action('init', array($this, 'register_taxonomy_portfolio_category'), 0);
	}

	public static function basename() {
        return plugin_basename(__FILE__);
    }

    // Регистрация типа постов "Портфолио"
    function register_post_type_portfolio() {
		$labels = array(
			'name' => 'Элемент портфолио',
			'singular_name' => 'Элемент портфолио', // админ панель Добавить->Функцию
			'add_new' => 'Добавить элемент',
			'add_new_item' => 'Добавить новый элемент портфолио', // заголовок тега <title>
			'edit_item' => 'Редактировать элемент',
			'new_item' => 'Новый элемент',
			'all_items' => 'Все элементы портфолио',
			'view_item' => 'Просмотр элемента на сайте',
			'search_items' => 'Искать элемент',
			'not_found' =>  'Элементов не найдено.',
			'not_found_in_trash' => 'В корзине нет элементов портфолио.',
			'menu_name' => 'Портфолио' // ссылка в меню в админке
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'show_ui' => true, // показывать интерфейс в админке
			'has_archive' => true, 
			'menu_icon' => 'dashicons-format-gallery', // иконка в меню
			'menu_position' => 22, // порядок в меню
			'supports' => array( 'title', 'editor', 'revisions', 'page-attributes'),
			'taxonomies' => array('portfolio_category')
		);
		register_post_type('portfolio', $args);
	}

	// Регистрация таксономии "Категория элемента портфолио"
	function register_taxonomy_portfolio_category() {

		register_taxonomy(
			'portfolio_category',
			array('portfolio'),
			array(
				'hierarchical' => true, /* true - по типу рубрик, false - по типу меток, по умолчанию - false */
				'labels' => array(
					'name' => 'Категории',
					'singular_name' => 'Категория элемента',
					'search_items' =>  'Найти категорию',
					'popular_items' => 'Популярные категории',
					'all_items' => 'Все категории',
					'parent_item' => null,
					'parent_item_colon' => null,
					'edit_item' => 'Редактировать категорию',
					'update_item' => 'Обновить категории',
					'add_new_item' => 'Добавить новую категорию',
					'new_item_name' => 'Название новой категории',
					'add_or_remove_items' => 'Добавить или удалить категорию',
					'choose_from_most_used' => 'Выбрать из наиболее часто используемых категорий',
					'not_found' =>  'Категории не найдены.',
					'not_found_in_trash' => 'В корзине нет категорий.',
					'menu_name' => 'Категории'
				),
				'public' => true, 
				/* каждый может использовать таксономию, либо
				только администраторы, по умолчанию - true */
				'show_in_nav_menus' => true,
				/* добавить на страницу создания меню */
				'show_ui' => true,
				/* добавить интерфейс создания и редактирования */
				'show_tagcloud' => true,
				/* нужно ли разрешить облако тегов для этой таксономии */
				'update_count_callback' => '_update_post_term_count',
				/* callback-функция для обновления счетчика $object_type */
				'query_var' => true,
				/* разрешено ли использование query_var, также можно 
				указать строку, которая будет использоваться в качестве 
				него, по умолчанию - имя таксономии */
				'rewrite' => array(
				/* настройки URL пермалинков */
					'slug' => 'portfolio_category', // ярлык
					'hierarchical' => false // разрешить вложенность
	 
				),
			)
		);
	}

	// Тексты уведомлений
	function post_type_portfolio_messages( $messages ) {
		global $post, $post_ID;
	 
		$messages['portfolio'] = array( // service - название созданного нами типа записей
			0 => '', // Данный индекс не используется.
			1 => sprintf( 'Услуга обновлена. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			2 => 'Параметр обновлён.',
			3 => 'Параметр удалён.',
			4 => 'Элемент обновлен',
			5 => isset($_GET['revision']) ? sprintf( 'Элемент восстановлен из редакции: %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( 'Услуга опубликована на сайте. <a href="%s">Просмотр</a>', esc_url( get_permalink($post_ID) ) ),
			7 => 'Элемент сохранен.',
			8 => sprintf( 'Отправлено на проверку. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( 'Запланировано на публикацию: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Просмотр</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( 'Черновик обновлён. <a target="_blank" href="%s">Просмотр</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);
	 
		return $messages;
	}
}

$wt_portfolio = new WtPortfolio();

?>