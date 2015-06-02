<?php

class ProductosController extends AppController {

  public $layout = 'viva';
  public $name = 'Productos';
  public $uses = array('Producto', 'Preciosventa', 'Tiposproducto', 'Marca', 'Productosprecio', 'Almacene', 'Movimiento');
  public $helpers = array('Html', 'Form');
  public $components = array('Session', 'RequestHandler', 'DataTable');

  public function beforeFilter() {
    parent::beforeFilter();
    //$this->Auth->allow();
  }

  //public
  function index() {
    if ($this->RequestHandler->responseType() == 'json') {
      $sql = '(SELECT COUNT(id) FROM productosprecios WHERE (productosprecios.producto_id = Producto.id))';
      $editar = '<a href="javascript:" class="button orange-gradient compact icon-pencil" onclick="editar_p(' . "',Producto.id,'" . ')">Editar</a>';
      $precios = '<a href="javascript:" class="button anthracite-gradient compact icon-page-list" onclick="precios_productos(' . "',Producto.id,'" . ')">Precios</a>';
      $elimina = '<button class="button red-gradient compact icon-cross-round" type="button" onclick="elimina_p(' . "',Producto.id,'" . ')">Eliminar</button>';
      $acciones = "$editar $precios $elimina";
      $small_r = '<small class="tag red-bg" id="idproducto-' . "',Producto.id,'" . '"> ' . "',$sql,'" . ' </small></td>';
      $small_n = '<small class="tag " id="idproducto-' . "',Producto.id,'" . '"> ' . "',$sql,'" . ' </small></td>';
      $this->Producto->virtualFields = array(
        'imagen' => "CONCAT(IF(ISNULL(Producto.url_imagen),'',CONCAT('" . '<img src="' . "',Producto.url_imagen,'" . '" height="51" width="51">' . "')))",
        'precios' => "CONCAT((IF($sql = 0,CONCAT('$small_r'),CONCAT('$small_n'))))",
        'acciones' => "CONCAT('$acciones')"
      );
      $this->paginate = array(
        'fields' => array('Producto.precios', 'Producto.imagen', 'Producto.nombre', 'Producto.precio_compra', 'Producto.proveedor', 'Producto.fecha_ingreso', 'Producto.acciones'),
        'recursive' => -1,
        'order' => 'Producto.id DESC'
      );
      $this->DataTable->fields = array('Producto.precios', 'Producto.imagen', 'Producto.nombre', 'Producto.precio_compra', 'Producto.proveedor', 'Producto.fecha_ingreso', 'Producto.acciones');
      $this->DataTable->emptyEleget_usuarios_adminments = 1;
      $this->set('productos', $this->DataTable->getResponse());
      $this->set('_serialize', 'productos');
    }
    $this->set(compact('productos'));
  }

  function insertar() {

    if (!empty($this->data)) {
      $this->request->data['Producto']['estado'] = 1;
      $producto = $this->Tiposproducto->find('first', array('conditions' => array('Tiposproducto.id' => $this->request->data['Producto']['tiposproducto_id'])));
      $this->request->data['Producto']['tipo_producto'] = $producto['Tiposproducto']['nombre'];
      $this->request->data['Producto']['fecha_ingreso'] = date('Y-m-d');
      $this->Producto->create();
      /* debug($this->request->data);
        die; */
      if (!empty($this->request->data['Producto']['imagen']['name'])) {
        $url_img = $this->guarda_imagen();
        if (!empty($url_img)) {
          $this->request->data['Producto']['url_imagen'] = $url_img;
        } else {
          $this->Session->setFlash('No se pudo registrar, problemas al cargar la imagen', 'msgerror');
          $this->redirect(array('action' => 'index'), null, true);
        }
      }
      if ($this->Producto->save($this->request->data['Producto'])) {
        $idProducto = $this->Producto->getLastInsertID();
        $this->registra_precio_cantidad($idProducto);
        $this->Session->setFlash('Producto Registrado', 'msgbueno');
      } else {
        $this->Session->setFlash('No se pudo registrar!!!', 'msgerror');
      }
      $this->redirect(array('action' => 'index'), null, true);
      //debug($valida); exit;
    } //debug($this->data);
    //exit;
    $tiposproductos = $this->Producto->Tiposproducto->find('all', array('recursive' => -1));
    $marcas = $this->Marca->find('list', array('fields' => 'Marca.nombre'));
    //debug($marcas);exit;
    $this->set(compact('tiposproductos', 'marcas'));
  }
  public function registra_precio_cantidad($idProducto = null) {
    if (!empty($this->request->data['Producto']['precio_venta'])) {
      $this->request->data['Productosprecio']['producto_id'] = $idProducto;
      if($this->request->data['Productosprecio']['escala'] == 'TIENDA'){
        $this->request->data['Productosprecio']['tipousuario_id'] = 2;
        $this->request->data['Productosprecio']['escala_id'] = 3;
      }elseif ($this->request->data['Productosprecio']['escala'] == 'DISTRIBUIDOR') {
        $this->request->data['Productosprecio']['tipousuario_id'] = 3;
        $this->request->data['Productosprecio']['escala_id'] = 1;
        $this->request->data['Productosprecio']['escala'] = 'MAYOR';
      }
      $this->request->data['Productosprecio']['precio'] = $this->request->data['Producto']['precio_venta'];
      $this->request->data['Productosprecio']['fecha'] = date('Y-m-d');
      $this->Productosprecio->create();
      $this->Productosprecio->save($this->request->data['Productosprecio']);
    }
    if (!empty($this->request->data['Producto']['cantidad_central'])) {
      $almacen = $this->Almacene->find('first', array('conditions' => array('Almacene.central' => 1), 'fields' => array('Almacene.id', 'Almacene.sucursal_id')));
      $ultimo = $this->Movimiento->find('first', array(
        'order' => 'Movimiento.id DESC',
        'conditions' => array('Movimiento.producto_id' => $idProducto, 'Movimiento.almacene_id' => $almacen['Almacene']['id'])
      ));
      if (!empty($ultimo)) {
        $total = $ultimo['Movimiento']['total'] + $this->request->data['Producto']['cantidad_central'];
      } else {
        $total = $this->request->data['Producto']['cantidad_central'];
      }
      $this->request->data['Movimiento']['user_id'] = $this->Session->read('Auth.User.id');
      $this->request->data['Movimiento']['producto_id'] = $idProducto;
      $this->request->data['Movimiento']['ingreso'] = $this->request->data['Producto']['cantidad_central'];
      $this->request->data['Movimiento']['total'] = $total;
      $this->request->data['Movimiento']['almacene_id'] = $almacen['Almacene']['id'];
      $this->request->data['Movimiento']['sucursal_id'] = $almacen['Almacene']['sucursal_id'];
      $this->Movimiento->create();
      $this->Movimiento->save($this->request->data['Movimiento']);
    }
  }

  public function guarda_imagen() {
    $archivoImagen = $this->request->data['Producto']['imagen'];
    //$nombreOriginal = $this->request->data['Producto']['imagen']['name'];
    //debug($archivoImagen);exit;
    if ($archivoImagen['error'] === UPLOAD_ERR_OK) {
      $nombre = string::uuid();
      if (move_uploaded_file($archivoImagen['tmp_name'], WWW_ROOT . 'img_producto' . DS . $nombre . '.jpg')) {
        return 'img_producto' . DS . $nombre . '.jpg';
      } else {
        return NULL;
      }
    } else {
      return NULL;
    }
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
      //debug($this->request->data);exit;
      if (!empty($this->request->data['Producto']['imagen']['name'])) {
        $url_img = $this->guarda_imagen();
        if (!empty($url_img)) {
          $this->request->data['Producto']['url_imagen'] = $url_img;
        } else {
          $this->Session->setFlash('No se pudo registrar, problemas al cargar la imagen', 'msgerror');
          $this->redirect(array('action' => 'index'), null, true);
        }
      }
      if ($this->Producto->save($this->request->data['Producto'])) {
        $this->Session->setFlash('Producto Registrado', 'msgbueno');
      } else {
        $this->Session->setFlash('No se pudo registrar!!!', 'msgerror');
      }
      $this->redirect(array('action' => 'index'), null, true);
    }
    $marcas = $this->Marca->find('list', array('fields' => 'Marca.nombre'));
    $tiposproductos = $this->Producto->Tiposproducto->find('all', array('recursive' => -1));
    $this->set(compact('tiposproductos', 'marcas'));
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
