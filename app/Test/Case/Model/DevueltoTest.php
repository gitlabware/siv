<?php
App::uses('Devuelto', 'Model');

/**
 * Devuelto Test Case
 *
 */
class DevueltoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.devuelto',
		'app.persona',
		'app.sucursal',
		'app.almacene',
		'app.user',
		'app.group',
		'app.lugare',
		'app.cliente',
		'app.ruta',
		'app.recarga',
		'app.porcentaje',
		'app.recargado',
		'app.ventasdistribuidore',
		'app.producto',
		'app.tiposproducto',
		'app.marca',
		'app.productosprecio',
		'app.tipousuario',
		'app.deposito',
		'app.banco',
		'app.movimiento',
		'app.movimientoscabina',
		'app.movimientosrecarga',
		'app.ventascelulare',
		'app.detalle'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Devuelto = ClassRegistry::init('Devuelto');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Devuelto);

		parent::tearDown();
	}

}
