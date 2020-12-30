<?php
namespace Mihdan\Kadence_Child;

use Auryn\Injector;

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

	public function init() {
		( $this->injector->make( CPT::class ) )->setup_hooks();
		( $this->injector->make( Taxonomy::class ) )->setup_hooks();
		( $this->injector->make( ACF::class ) )->setup_hooks();
		( $this->injector->make( Comments::class ) )->setup_hooks();
		( $this->injector->make( Related_Posts::class ) )->setup_hooks();
		( $this->injector->make( Pageviews::class ) )->setup_hooks();
		( $this->injector->make( Performance::class ) )->setup_hooks();
		( $this->injector->make( Lazy_Load::class ) )->setup_hooks();
		( $this->injector->make( CF7::class ) )->setup_hooks();
	}
}
