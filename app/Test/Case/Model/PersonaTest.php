<?php
App::uses('Persona', 'Model');

/**
 * Persona Test Case
 *
 */
class PersonaTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.persona',
		'app.sucursal',
		'app.almacene',
		'app.user',
		'app.group',
		'app.movimiento',
		'app.producto',
		'app.tiposproducto',
		'app.productosprecio',
		'app.tipousuario',
		'app.recarga',
		'app.cliente',
		'app.ventasdistribuidore'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Persona = ClassRegistry::init('Persona');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Persona);

		parent::tearDown();
	}

}
