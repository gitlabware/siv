<?php
App::uses('Pedido', 'Model');

/**
 * Pedido Test Case
 *
 */
class PedidoTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.pedido',
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
		'app.ruta',
		'app.recarga',
		'app.porcentaje',
		'app.recargado',
		'app.ventasdistribuidore',
		'app.ventascelulare',
		'app.movimientoscabina',
		'app.movimientosrecarga',
		'app.distribuidor'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Pedido = ClassRegistry::init('Pedido');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Pedido);

		parent::tearDown();
	}

}
