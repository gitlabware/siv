<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Group $Group
 * @property Persona $Persona
 * @property Sucursal $Sucursal
 */
class User extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

    public function beforeSave($options = array())
    {
        $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        return true;
    }
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Persona' => array(
			'className' => 'Persona',
			'foreignKey' => 'persona_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Sucursal' => array(
			'className' => 'Sucursal',
			'foreignKey' => 'sucursal_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
