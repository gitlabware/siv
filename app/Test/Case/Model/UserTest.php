<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.group',
		'app.persona',
		'app.sucursal',
		'app.almacene',
		'app.detalle',
		'app.producto',
		'app.tiposproducto',
		'app.productosprecio',
		'app.tipousuario',
		'app.movimiento',
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
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

}
