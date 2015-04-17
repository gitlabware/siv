<?php

App::uses('AppModel', 'Model');
/**
 * Producto Model
 *
 * @property Tiposproducto $Tiposproducto
 * @property Productosprecio $Productosprecio
 */
class Producto extends AppModel
{


    //The Associations below have been created with all possible keys, those that are not needed can be removed

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array('Tiposproducto' => array(
            'className' => 'Tiposproducto',
            'foreignKey' => 'tiposproducto_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''));

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array('Productosprecio' => array(
            'className' => 'Productosprecio',
            'foreignKey' => 'producto_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''));
    var $name = 'Producto';
    var $validate = array(
        'nombre' => array('isUnique' => array(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'el producto ya existe')),
        'precio_compra' => array('rule' => array('numeric'), 'message' => 'debe ser un valor numerico'),


        'estado' => array('rule' => array('boolean')));

}

?>
