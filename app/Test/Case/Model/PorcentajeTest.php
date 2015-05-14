<?php
App::uses('Porcentaje', 'Model');

/**
 * Porcentaje Test Case
 *
 */
class PorcentajeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.porcentaje'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Porcentaje = ClassRegistry::init('Porcentaje');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Porcentaje);

		parent::tearDown();
	}

}
