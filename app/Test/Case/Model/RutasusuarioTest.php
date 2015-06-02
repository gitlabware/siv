<?php
App::uses('Rutasusuario', 'Model');

/**
 * Rutasusuario Test Case
 *
 */
class RutasusuarioTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.rutasusuario',
		'app.user',
		'app.group',
		'app.persona',
		'app.sucursal',
		'app.almacene',
		'app.deposito',
		'app.banco',
		'app.detalle',
		'app.producto',
		'app.tiposproducto',
		'app.marca',
		'app.productosprecio',
		'app.tipousuario',
		'app.movimiento',
		'app.cliente',
		'app.lugare',
		'app.recarga',
		'app.porcentaje',
		'app.recargado',
		'app.ventasdistribuidore',
		'app.ventascelulare',
		'app.movimientoscabina',
		'app.movimientosrecarga',
		'app.ruta'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Rutasusuario = ClassRegistry::init('Rutasusuario');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Rutasusuario);

		parent::tearDown();
	}

}
