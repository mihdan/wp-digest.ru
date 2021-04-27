<?php

namespace Mihdan\Kadence_Child;

use WP_Query;

class Source_Filter {

	private $post_type = 'post';
	private $meta_key = 'post_source_url';

	public function setup_hooks(): void {
		add_action( 'restrict_manage_posts', [ $this, 'add_table_filters' ] );
		add_action( 'pre_get_posts', [ $this, 'add_table_filters_handler' ] );
		add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widgets' ] );
	}

	/**
	 * Выводит на экран фильтр в виде выпадающего списока.
	 *
	 * @param string $post_type
	 *
	 * @return void
	 */
	public function add_table_filters( string $post_type ): void {
		if ( $post_type !== $this->post_type ) {
			return;
		}

		$html = '';

		foreach ( $this->get_urls() as $url => $qnt ) {
			$html .= sprintf(
				'<option value="%s" %s>%s (%d)</option>',
				esc_attr( $url ),
				selected( $this->get_request_value(), $url, false ),
				esc_html( $this->get_domain_witout_www( $url ) ),
				$qnt
			);
		}

		printf( '<select name="%s"><option>--- Все источники ---</option>%s</select>', $this->meta_key, $html );
	}

	/**
	 * Добавляем поиск по источнику при его выборе в выпадающем списке в основной запрос.
	 *
	 * @param WP_Query $query
	 *
	 * @return void
	 */
	public function add_table_filters_handler( WP_Query $query ): void {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! ( $screen && $screen->id === "edit-$this->post_type" && $query->is_main_query() ) ) {
			return;
		}

		if ( $this->get_request_value() ) {
			$query->set( 'meta_query', [
				[
					'key'     => $this->meta_key,
					'value'   => $this->get_request_value(),
					'compare' => 'LIKE',
				],
			] );
		}
	}

	/**
	 * Возвращает массив ссылок на источники с количеством их вхождений.
	 *
	 * @return array
	 */
	private function get_urls(): array {
		global $wpdb;

		$cache_key = "cache_$this->meta_key";
		$cache     = wp_cache_get( $cache_key );

		if ( false !== $cache ) {
			return $cache;
		}

		$result = $wpdb->get_col(
			$wpdb->prepare( "
				SELECT meta_value 
				FROM $wpdb->postmeta 
				WHERE meta_key = %s AND meta_value != ''
			", $this->meta_key
			)
		);

		// Подсчёт вхождений
		$result = array_count_values(
			array_filter(
				array_map( static function ( $url ) {
					preg_match( '/https?:\/\/([^\/]+)\/?/', $url, $matches );

					return $matches[1] ?? null;
				}, $result )
			)
		);

		wp_cache_set( $cache_key, $result, HOUR_IN_SECONDS );

		return $result;
	}

	/**
	 * Возвращает домен без www (www.php.net -> php.net).
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	private function get_domain_witout_www( string $url ): string {
		return preg_replace( '/^www\./', '', $url );
	}

	/**
	 * Возвращает значение GET параметра.
	 *
	 * @return string
	 */
	private function get_request_value(): string {
		return $_GET[ $this->meta_key ] ?? '';
	}

	/**
	 * Регистрирует виджет с графиком для раздела "Консоль".
	 *
	 * @return void
	 */
	public function add_dashboard_widgets(): void {
		wp_add_dashboard_widget(
			'dashboard_widget_post_source_viewer',
			'Распределение новостей по источникам',
			[ $this, 'dashboard_widget_content' ]
		);
	}

	/**
	 * Возвращает подготовленный json для построения графика.
	 *
	 * @return string|false
	 */
	private function get_urls_prepare_for_js() {
		$urls = $this->get_urls();
		$rows = [];

		if ( ! $urls ) {
			return '';
		}

		foreach ( $urls as $url => $qnt ) {
			$rows[] = [
				"c" => [
					[ "v" => $this->get_domain_witout_www( $url ) ],
					[ "v" => $qnt ],
				],
			];
		}

		return wp_json_encode( [
			'cols' => [
				[
					'id'    => 'source',
					'label' => 'Источник',
					'type'  => 'string',
				],
				[
					'id'    => 'quantity',
					'label' => 'Количество',
					'type'  => 'number',
				],
			],
			'rows' => $rows,
		] );
	}

	/**
	 * Выводит на экран контент виджета.
	 *
	 * @return void
	 */
	public function dashboard_widget_content(): void {
		$json = $this->get_urls_prepare_for_js();

		if ( $json ): ?>

            <div id="dashboard_widget_post_source_viewer_pie" style="width: 100%; height: 250px;"></div>

            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(function () {
                    const data = new google.visualization.DataTable(<?php echo $json ?>);

                    const box = document.getElementById('dashboard_widget_post_source_viewer_pie');
                    const chart = new google.visualization.PieChart(box);

                    chart.draw(data);
                });
            </script>

		<?php else: ?>

            <p>К сожалению, источники новостей не найдены :-(</p>

		<?php endif;
	}

}
