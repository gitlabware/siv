<?php

class Rutascontroller extends AppController{
    public $layout = 'viva';
    public $uses= array('Ruta');
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow();
    }
    
    public function index (){
        $this->paginate=array('Ruta'=>array('limit'=>100));
        $this->set('rutas', $this->paginate('Ruta'));
    }
    
    public function add (){
        if ($this->request->is('post')) {
            $this->Ruta->create();
            if ($this->Ruta->save($this->request->data)) {
                $this->Session->setFlash('Registrado Correctamente', 'msgbueno');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Error el registrar!', 'msgerror');
            }
        }
    }
    
    public function edit($id = null) {
        $this->Ruta->id = $id;
        if (!$this->Ruta->exists()) {
            throw new NotFoundException(__('Invalido'));
        }
        if ($this->request->is(array('post', 'put'))) {

            if ($this->Ruta->save($this->request->data)) {
                $this->Session->setFlash('Registro exitoso', 'msgbueno');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Error al registrar', 'msgerror');
            }
        } else {
            $this->request->data = $this->Ruta->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->Ruta->id = $id;
        if (!$this->Ruta->exists()) {
            throw new NotFoundException(__('Categoria invalido'));
        }
        if ($this->Ruta->delete()) {
            $this->Session->setFlash(__('Eliminado!!'), 'msgbueno');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('No se elemino'), 'msgalert');
        $this->redirect(array('action' => 'index'));
    }
}
