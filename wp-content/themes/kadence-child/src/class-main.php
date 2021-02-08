<?php
namespace Mihdan\Kadence_Child;

use Auryn\Injector;
use Auryn\ConfigException;
use Auryn\InjectionException;

class Main {
	/**
	 * Injector instance.
	 *
	 * @var Injector
	 */
	private $injector;

	/**
	 * Main constructor.
	 *
	 * @param Injector $injector Injector instance.
	 */
	public function __construct( Injector $injector ) {
		$this->injector = $injector;
	}

	/**
	 * Init theme.
	 *
	 * @throws InjectionException If a cyclic gets detected when provisioning.
	 * @throws ConfigException If $nameOrInstance is not a string or an object.
	 */
	public function init() {
		foreach ( [
			CPT::class,
			Taxonomy::class,
			ACF::class,
			Comments::class,
			Related_Posts::class,
			Pageviews::class,
			Performance::class,
			Lazy_Load::class,
			CF7::class,
			SEO::class,
			//Subscription::class,
			Syntax_Highlighter::class,
			Feed::class,
		] as $class_name ) {
			( $this->make( $class_name ) )->setup_hooks();
		}
		
		( $this->make( WPScan::class, [ WPSCAN_TOKEN ] ) )->setup_hooks();
	}
	
	/**
	 * Make a class from DIC.
	 *
	 * @param string $class_name Full class name.
	 * @param array  $args	     List of arguments.
	 *
	 * @return mixed
	 *
	 * @throws InjectionException If a cyclic gets detected when provisioning.
	 * @throws ConfigException If $nameOrInstance is not a string or an object.
	 */
	public function make( string $class_name, array $args = [] ) {

		$this->injector->share( $class_name );

		return $this->injector->make( $class_name, $args );
	}
}
