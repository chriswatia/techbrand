<?php
/**
 * Setup Elementor widgets.
 * @package The7
 */

namespace The7\Adapters\Elementor;

use Elementor\Core\DynamicTags\Dynamic_CSS;
use Elementor\Plugin;
use ElementorPro\Modules\GlobalWidget\Widgets\Global_Widget;

defined( 'ABSPATH' ) || exit;

/**
 * Class The7_Elementor_Widgets
 */
class The7_Elementor_Widgets {

	protected $widgets_collection = [];

	/**
	 * Bootstrap widgets.
	 */
	public function bootstrap() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
		add_action( 'elementor/init', [ $this, 'elementor_add_custom_category' ] );
		add_action( 'elementor/init', [ $this, 'load_dependencies' ] );
		add_action( 'elementor/preview/init', [ $this, 'turn_off_lazy_loading' ] );
		add_action( 'elementor/editor/init', [ $this, 'turn_off_lazy_loading' ] );

		presscore_template_manager()->add_path( 'elementor', array( 'template-parts/elementor' ) );
		add_action( 'elementor/element/parse_css', [ $this, 'add_widget_css' ], 10, 2 );
	}

	public function add_widget_css( $post_css, $element ) {
		if ( $post_css instanceof Dynamic_CSS ) {
			return;
		}
		$css = '';
		if ( $element instanceof Global_Widget ) {
			if ( $element->get_original_element_instance() instanceof The7_Elementor_Widget_Base ) {
				$css = $element->get_original_element_instance()->generate_inline_css();
			}
		} else if ( $element instanceof The7_Elementor_Widget_Base  ) {
			$css = $element->generate_inline_css();
		}

		if ( empty( $css ) ) {
			return;
		}

		$css = str_replace(array("\n", "\r"), '', $css);
		$post_css->get_stylesheet()->add_raw_css( $css );
	}


	/**
	 * Disable lazy loading with filter.
	 */
	public function turn_off_lazy_loading() {
		add_filter( 'dt_of_get_option-general-images_lazy_loading', '__return_false' );
	}

	/**
	 * Load dependencies and populate widgets collection.
	 * @throws Exception
	 */
	public function load_dependencies() {
		require_once __DIR__ . '/class-the7-elementor-widget-terms-selector-mutator.php';
		require_once __DIR__ . '/trait-with-pagination.php';
		require_once __DIR__ . '/class-the7-elementor-widget-base.php';
		require_once __DIR__ . '/the7-elementor-less-vars-decorator-interface.php';
		require_once __DIR__ . '/class-the7-elementor-less-vars-decorator.php';
		require_once __DIR__ . '/widgets/class-the7-elementor-elements-widget.php';
		require_once __DIR__ . '/widgets/class-the7-elementor-elements-carousel-widget.php';
		require_once __DIR__ . '/widgets/class-the7-elementor-elements-breadcrumbs-widget.php';

		$terms_selector_mutator = new The7_Elementor_Widget_Terms_Selector_Mutator();
		$terms_selector_mutator->bootstrap();

		$this->collection_add_widget( new \The7\Adapters\Elementor\Widgets\The7_Elementor_Elements_Widget() );
		$this->collection_add_widget( new \The7\Adapters\Elementor\Widgets\The7_Elementor_Elements_Carousel_Widget() );
		$this->collection_add_widget( new \The7\Adapters\Elementor\Widgets\The7_Elementor_Elements_Breadcrumbs_Widget() );
	}

	/**
	 * Register widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		foreach ( $this->widgets_collection as $widget ) {
			$widgets_manager->register_widget_type( $widget );
		}
	}

	/**
	 * Add 'The7 elements' category.
	 */
	public function elementor_add_custom_category() {
		Plugin::$instance->elements_manager->add_category( 'the7-elements', [
				'title' => esc_html__( 'The7 elements', 'the7mk2' ),
				'icon'  => 'fa fa-header',
			] );
	}

	protected function collection_add_widget( $widget ) {
		$this->widgets_collection[ $widget->get_name() ] = $widget;
	}
}
