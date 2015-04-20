<?php

App::uses('AppController', 'Controller');

class ClientesController extends AppController {

  public $uses = array('Cliente', 'Recarga');
  var $components = array('RequestHandler', 'DataTable');
  public $layout = 'viva';

  public function beforeFilter() {
    parent::beforeFilter();
    if ($this->RequestHandler->responseType() == 'json') {
      $this->RequestHandler->setContent('json', 'application/json');
    }
    $this->Auth->allow();
  }

  public function index() {
    //$this->Cliente->recursive = 0;
    //$this->set('clientes', $this->paginate());
    //debug($clientes);exit;
    if ($this->RequestHandler->responseType() == 'json') {
      /*$editar = '<button class="btn btn-info btn-xs" type="button" onclick="editart(' . "',Trabajo.id,'" . ')"><i class="icon ico-pencil"></i>Editar</button>';
      $imprimir = '<button class="btn btn-inverse btn-xs" type="button" onclick="imprimirt(' . "',Trabajo.id,'" . ')"><i class="icon ico-print"></i>Imprimir</button>';
      $produccion = '<button class="btn btn-success btn-xs" type="button" onclick="produccion(' . "',Trabajo.id,'" . ')"><i class="icon ico-cogs"></i>Produccion</button>';
      $elimina = '<button class="btn btn-danger btn-xs" type="button" onclick="eliminart(' . "',Trabajo.id,'" . ')"><i class="icon ico-remove3"></i>Eliminar</button>';*/
      $acciones = '';
      $this->Cliente->virtualFields = array(
        'acciones' => "CONCAT('$acciones')"
      );
      $this->paginate = array(
        'fields' => array('Cliente.num_registro', 'Cliente.nombre', 'Cliente.direccion', 'Cliente.celular', 'Cliente.zona', 'Cliente.acciones'),
        'recursive' => -1,
        'order' => 'Cliente.id DESC'
      );
      $this->DataTable->fields = array('Cliente.num_registro', 'Cliente.nombre', 'Cliente.direccion', 'Cliente.celular', 'Cliente.zona', 'Cliente.acciones');
      $this->DataTable->emptyEleget_usuarios_adminments = 1;
      $this->set('clientes', $this->DataTable->getResponse());
      $this->set('_serialize', 'clientes');
    }
  }

  public function clientes() {
    $clientes = $this->Cliente->find('all', array('recursive' => -1));
    $this->set(compact('clientes'));
  }

  public function edit($id = null) {
    $this->Cliente->id = $id;
    if (!$this->Cliente->exists()) {
      throw new NotFoundException(__('Invalid cliente'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Cliente->save($this->request->data)) {
        $this->Session->setFlash(__('The user has been saved'));
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
      }
    } else {
      $this->request->data = $this->Cliente->read(null, $id);
    }
    // $groups = $this->User->Group->find('all');
    //$this->set(compact('groups'));
  }

  public function insertar() {
    if ($this->request->is('post')) {

      $this->Cliente->create();
      //debug($this->request->data); 
      //debug($this->request->data['User']); exit;
      $this->request->data['Cliente']['estado'] = 1;
      if ($this->Cliente->save($this->request->data['Cliente'])) {

        //debug($iduser);exit;

        $this->Session->setFlash(__('The user has been saved'));

        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
      }
    }

    //$groups = $this->User->Group->find('all', array ('recursive'=>-1));
    //$this->set(compact('groups'));
  }

  public function delete($id = null) {
    $this->Cliente->id = $id;
    if (!$this->Cliente->exists()) {
      throw new NotFoundException(__('Usuario invalido'));
    }
    if ($this->Cliente->delete()) {
      $this->Session->setFlash(__('Cliente eliminado'), 'msgbueno');
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('El usuario no se elemino'), 'msgalert');
    $this->redirect(array('action' => 'index'));
  }

  public function nuevarecarga() {
    if ($this->request->is('post')) {

      $this->Recarga->create();
      //debug($this->request->data); 

      if ($this->Recarga->save($this->request->data['Recarga'])) {

        //debug($iduser);exit;

        $this->Session->setFlash(__('The user has been saved'));

        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
      }
    }

    //$groups = $this->User->Group->find('all', array ('recursive'=>-1));
    //  $this->set(compact('groups'));
  }

}

?>