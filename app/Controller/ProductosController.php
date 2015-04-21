<?php

class ProductosController extends AppController {

  public $layout = 'viva';
  public $name = 'Productos';
  public $uses = array('Producto', 'Preciosventa', 'Tiposproducto');
  public $helpers = array('Html', 'Form');
  public $components = array('Session');

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow();
  }

  //public
  function index() {
    //$conv_excel = new AexcelComponent();
    $sql = '(SELECT COUNT(id) FROM productosprecios WHERE (productosprecios.producto_id = Producto.id))';
    $this->Producto->virtualFields = array(
      'precios' => "CONCAT($sql)"
    );
    /*if ($this->RequestHandler->responseType() == 'json') {
      $editar = '<button class="button orange-gradient compact icon-pencil" type="button" onclick="editarc(' . "',Cliente.id,'" . ')">Editar</button>';
      $elimina = '<button class="button red-gradient compact icon-cross-round" type="button" onclick="eliminarc(' . "',Cliente.id,'" . ')">Eliminar</button>';
      $acciones = "$editar $elimina";
      $this->Producto->virtualFields = array(
        'acciones' => "CONCAT('$acciones')"
      );
      $this->paginate = array(
        'fields' => array('Producto.acciones'),
        'recursive' => -1,
        'order' => 'Cliente.id DESC'
      );
      $this->DataTable->fields = array('Cliente.num_registro', 'Cliente.nombre', 'Cliente.direccion', 'Cliente.celular', 'Cliente.zona', 'Cliente.acciones');
      $this->DataTable->emptyEleget_usuarios_adminments = 1;
      $this->set('productos', $this->DataTable->getResponse());
      $this->set('_serialize', 'productos');
    }*/
    
    $productos = $this->Producto->find('all',array('recursive' => -1));
    //debug($productos);exit;
    $this->set(compact('productos'));
  }

  function insertar() {

    if (!empty($this->data)) {

      //debug($this->data);
      //exit;

      $this->request->data['Producto']['estado'] = 1;
      $producto = $this->Tiposproducto->find('first', array('conditions' => array('Tiposproducto.id' => $this->request->data['Producto']['tiposproducto_id'])));
      $this->request->data['Producto']['tipo_producto'] = $producto['Tiposproducto']['nombre'];
      $this->request->data['Producto']['fecha_ingreso'] = date('Y-m-d');
      $this->Producto->create();
      //debug($this->request->data);die;
      if ($this->Producto->save($this->request->data['Producto'])) {

        $this->Session->setFlash('Producto Registrado');
        $this->redirect(array('action' => 'index'), null, true);
      } else {
        $this->Session->setFlash('No se pudo registrar!!!');
      }
    } //debug($this->data);
    $tiposproductos = $this->Producto->Tiposproducto->find('all', array('recursive' => -1));
    $this->set(compact('tiposproductos'));
  }

  function ajaxproductos($n = Null) {

    $this->layout = 'ajax';
    $codu = $this->Session->read('tipo_id');
    $produsu = $this->Producto->find('list', array(
      'fields' => array('Producto.id', 'Producto.nombre'),
      'conditions' => array('Producto.tipousuario_id' => $codu),
      'recursive' => 0
    ));

    $this->set(compact('produsu', 'n', 'codu'));
    //debug($produsu);
  }

  function pedidos() {

    $codu = $this->Session->read('tipo_id');
    // $precios = $this->Preciosventa->find('all');exit;
    $produsu = $this->Producto->find('list', array(
      'fields' => array('Producto.id', 'Producto.nombre'),
      'conditions' => array('Producto.tipousuario_id' => $codu),
      'recursive' => 0
    ));

    //debug($produsu);
  }

  function editar($id = null) {
    $this->Producto->id = $id;
    if (!$id) {
      $this->Session->setFlash('No existe el producto');
      $this->redirect(array('action' => 'index'), null, true);
    }
    if (empty($this->data)) {
      $this->data = $this->Producto->read(); //find(array('id' => $id));
    } else {
      if ($this->Producto->save($this->data)) {
        $this->Session->setFlash('Los datos fueron modificados');
        $this->redirect(array('action' => 'index'), null, true);
      } else {
        $this->Session->setFlash('no se pudo modificar!!');
      }
    }
    $tiposproductos = $this->Producto->Tiposproducto->find('all', array('recursive' => -1));
    $this->set(compact('tiposproductos'));
  }

  function buscar() {
    $data = $this->data['dato'];
    $options = array('OR' => array('Producto.ap_paterno LIKE' => '%' . $data .
        '%', 'Administrativo.ap_materno LIKE' => '%' . $data . '%',
        'Administrativo.nombre LIKE' => '%' . $data . '%', 'Administrativo.ci LIKE' =>
        '%' . $data . '%'));
    $result = $this->Administrativo->find('all', array('conditions' => array($options)));
    $this->set('administrativos', $result);
  }

  function delete($id = Null) {
    if (!$id) {
      $this->Session->setFlash('Codigo invalido');
      $this->redirect(array('action' => 'index'), null, true);
    }
    if ($this->Producto->delete($id)) {
      $this->Session->setFlash('El producto  ' . $id . ' fue borrado');
      $this->redirect(array('action' => 'index'), null, true);
    }
  }

}

?>
