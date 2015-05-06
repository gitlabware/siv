<?php

class VentasdistribuidorController extends AppController {

  public $helpers = array(
    'Html',
    'Form',
    'Js');
  public $uses = array(
    'Cliente',
    'Producto',
    'Persona',
    'Movimiento',
    'Ruteosupervisore',
    'Recarga',
    'Detalleobservacione',
    'Ventasdistribuidore',
    'Productosprecio',
    'Ruteo',
    'Ruta',
    'Chip',
    'Tiposobservacione',
    'Deposito',
    'Listacliente', 'User');
  public $layout = 'vivadistribuidor';
  public $components = array('RequestHandler', 'Session', 'Acl', 'Auth', 'DataTable');

  function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow();
  }

  public function formularioventapormayor() {
    if (!empty($this->request->data)) {
      
    } else {
      
    }
  }

  function index() {
    $id = $this->Session->read('usuario_id');
    $fecha = date("Y-m-d");
    $dia = $this->getday();

    $ruta = $this->Ruteo->find('first', array('conditions' => array('Ruteo.distribuidor_id' =>
        $id, 'Ruteo.dia ' => $dia),));
    //debug($ruta);exit;
    $id_ruta = $ruta['Ruteo']['ruta_id'];
    $idruteo = $ruta['Ruteo']['id'];

    $lista = $this->Cliente->find('all', array('conditions' => array('Cliente.ruta_id' => $id_ruta)));

    //debug($lista);exit;
    //$ventas = $this->Ventaa->find('all', array("conditions" => array("Ventaa.fecha" => $fecha, "Ventaa.usuario_id" => $id)));
    //debug($ventas);exit;
    //$estado = 0;
    $i = 0;
    $cmp = array();


    $this->set(compact('lista', 'cmp', 'ruta'));
  }

  function visitassupervisor() {
    $id = $this->Session->read('usuario_id');
    //debug($id);exit;
    $fecha = date("Y-m-d");
    $dia = $this->getday();
    //debug($dia);exit;
    $ruta = $this->Ruteo->find('first', array('conditions' => array('Ruteo.distribuidor_id' =>
        $id, 'Ruteo.dia ' => $dia), 'fields' => array('Ruteo.id', 'Ruteo.ruta_id')));
    //  debug($ruta);exit;
    $id_ruta = $ruta['Ruteo']['ruta_id'];
    $idruteo = $ruta['Ruteo']['id'];

    $lista = $this->Ruteosupervisore->find('all', array('conditions' => array(
        'Ruteosupervisore.ruta_id' => $id_ruta,
        'Ruteosupervisore.distribuidor_id' => $id,
        'Ruteosupervisore.ruteo_id' => $idruteo), 'order' => array('Ruteosupervisore.orden ASC')));

    $this->set(compact('lista', 'cmp'));
  }

  function pidecodigo() {
    if (!empty($this->request->data)) {
      $codigo = $this->request->data['Cliente']['codigo'];
      $cliente = $this->Cliente->find('first', array(
        'conditions' => array(
          'Cliente.num_registro' => $codigo
      )));
      if (!empty($cliente)) {
        $idCliente = $cliente['Cliente']['id'];
        $this->redirect(array('action' => 'formulario', $idCliente));
      } else {
        $this->Session->setFlash('No existe el cliente con codigo de registro: ' . $codigo .
          ', consulte al administrador del sistema');
        $this->redirect(array('action' => 'pidecodigo'), null, true);
      }
    }
  }

  public function registrafecha() {
    if (!empty($this->request->data)) {
      $codigo = $this->request->data['Cliente']['codigo'];
      $fecha = $this->request->data['Cliente']['fecha'];
      $cliente = $this->Cliente->find('first', array(
        'conditions' => array(
          'Cliente.num_registro' => $codigo
      )));
      if (!empty($cliente)) {
        $idCliente = $cliente['Cliente']['id'];
        $fecha = $this->request->data['Cliente']['fecha'];
        $this->redirect(array('action' => 'formulario2', $idCliente, $fecha));
      } else {
        $this->Session->setFlash('No existe el cliente con codigo de registro: ' . $codigo .
          ', consulte al administrador del sistema');
        $this->redirect(array('action' => 'pidecodigo'), null, true);
      }
    }
  }

  public function formulario2($id_cli = null, $fecha = null) {
    //debug($fecha);exit;
    //$this->layout= 'ajax';
    $tipo = $this->Session->read('Auth.User.group_id');
    $usu = $this->Session->read('Auth.User.id');
    $usuario = $this->Session->read('Auth.User.persona_id');
    $datoscli = $this->Cliente->findById($id_cli);
    $cod149 = $datoscli['Cliente']['num_registro'];

    if (!empty($this->request->data)) {
      //debug($this->request->data);exit;
      $estado = 0;
      $clienteId = $this->request->data['Ventasdistribuidore']['1']['cliente_id'];
      //debug($clienteId);exit;
      $ventas = $this->request->data['Ventasdistribuidore'];
      foreach ($ventas as $d) {
        if ($d['cantidad'] != 0) {
          //debug($d);exit;
          $productoid = $d['producto_id'];
          $usuario = $d['persona_id'];
          $estado = 1;
          $cantidad = $d['cantidad'];

          $producto = $this->Producto->find('first', array('conditions' => array('Producto.id' =>
              $productoid)));
          $prodnomb = $producto['Producto']['nombre'];
          //**************************************************************

          $movs = $this->Movimiento->find('first', array(
            'conditions' => array(
              'Movimiento.persona_id' => $usuario,
              'Movimiento.producto_id' => $productoid),
            'order' => array('Movimiento.id DESC'),
            'recursive' => -1
          ));

          if (empty($movs)) {
            $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror');
            $this->redirect(array('action' => 'formulario', $clienteId), null, true);
          } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
            $total_ante = $movs['Movimiento']['total'];
            /*             * *verificar esta parte 15 abril 2013** */
            $fechamov = $movs['Movimiento']['created'];
            if ($fechamov == $fecha) {
              $saldo = $movs['Movimiento']['saldo'];
              $total = $movs['Movimiento']['total'] - $cantidad;
              $ingreso = $movs['Movimiento']['ingreso'];
              $venta = $movs['Movimiento']['salida'] + $cantidad;
            } else {
              $saldo = $movs['Movimiento']['total'];
              $total = $movs['Movimiento']['total'] - $cantidad;
              $ingreso = 0;
              $venta = $cantidad;
            }

            // debug($total_ante);exit;
            if ($total_ante >= $cantidad) {

              //**************************************************************
              //guarda la venta
              //**************************************************************
              $this->Ventasdistribuidore->create();
              //$d['Ventasdistribuidore']['created'] = $fecha;
              if (!($this->Ventasdistribuidore->save($d))) {
                $this->Session->setFlash('No se pudo guardar la venta del producto: ' . $prodnomb .
                  ', consulte al administrador del sistema', 'msgerror');
                $this->redirect(array('action' => 'formulario', $this->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
              } else {
                $idventa = $this->Ventasdistribuidore->getLastInsertID();
              }
            } else {
              $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
              $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id' => $idventa));
              $this->redirect(array('action' => 'formulario', $clienteId), null, true);
            }
            //debug($idventa);exit;
            //**************************************************************
            //guarda el movimiento de la venta
            //**************************************************************

            $this->Movimiento->create();

            $this->request->data['Movimiento']['producto_id'] = $productoid;
            $this->request->data['Movimiento']['user_id'] = $usu;
            $this->request->data['Movimiento']['persona_id'] = $usuario;
            $this->request->data['Movimiento']['ingreso'] = $ingreso;
            $this->request->data['Movimiento']['saldo'] = $saldo;
            $this->request->data['Movimiento']['salida'] = $venta;
            $this->request->data['Movimiento']['total'] = $total;
            $this->request->data['Movimiento']['ventasdistribuidore_id'] = $idventa;
            $this->request->data['Movimiento']['created'] = $fecha;
            //debug($fecha);
            //debug($this->request->data['Movimiento']);exit;
            if (!($this->Movimiento->save($this->request->data['Movimiento']))) {
              $this->Session->setFlash('No se pudo guardar el movimiento del producto:' . $prodnomb .
                ', consulte al administrador del sistema', 'msgerror');
              $this->redirect(array('action' => 'formulario', $this->request->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
            }

            //**************************************************************
          } elseif ($movs['Movimiento']['total'] == 0) {
            //debug($movs);exit;
            $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
              '. Ingrese los datos de venta nuevamente!!', 'msgerror');
            //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
            $this->redirect(array('action' => 'formulario', $clienteId), null, true);
          } else {
            $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
              '. Ingrese los datos de venta nuevamente!!', 'msgerror');
            //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
            $this->redirect(array('action' => 'formulario', $clienteId), null, true);
          }

          //**************************************************************
        } //fin del if decantidad != 0
        else {
          $this->Ventasdistribuidore->create();
          $this->Ventasdistribuidore->save($d);
        }
      } //end del recorrido de datos del thisdata


      $recargas = $this->request->data['Recarga'];

      if (!empty($recargas)) {
        foreach ($recargas as $data) {
          //debug($r);exit;

          if (!empty($data['monto'])) {
            //debug($data);
            $this->Recarga->create();
            if (!($this->Recarga->save($data))) {
              $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror');
              $this->redirect(array('action' => 'index'), null, true);
            } else {
              $this->requestAction(array('controller' => 'Recargas', 'action' => 'notifica'));
            }
          }
        }
      }

      $this->Session->setFlash('Venta registrada!!!!!', 'msgbueno');
      $this->redirect(array('action' => 'pidecodigo'), null, true);
    } else {

      $precios = $this->Productosprecio->find('all', array(
        'conditions' => array(
          'Productosprecio.tipousuario_id' => 3,
          'Producto.proveedor like' => 'VIVA',
          'Producto.estado' => '1'),
        'order' => array('Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
          'Productosprecio.escala')));

      $rows = $this->Productosprecio->find('all', array(
        'conditions' => array(
          'Productosprecio.tipousuario_id' => 3,
          'Producto.proveedor like' => 'VIVA',
          'Producto.estado' => '1'),
        'fields' => array(
          'Count(Productosprecio.id) as cantidad',
          'Producto.nombre',
          'Producto.id'),
        'group' => array('Productosprecio.producto_id')));


      //$this->set(compact('precios', 'rows', 'usu', 'datoscli', 'recargas'));
      $this->set(compact('precios', 'rows', 'usuario', 'usu', 'datoscli', 'recargas', 'fecha', 'id_cli', 'fecha'));
    }
  }

  function formulario($id_cli = null) {
    $usu = $this->Session->read('Auth.User.id');
    $usuario = $this->Session->read('Auth.User.persona_id');
    $datoscli = $this->Cliente->findById($id_cli);
    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'order' => array('Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));

    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'fields' => array(
        'Count(Productosprecio.id) as cantidad',
        'Producto.nombre',
        'Producto.id'),
      'group' => array('Productosprecio.producto_id')));

    if ($this->Session->check('form_venta_mayor')) {
      $this->request->data = $this->Session->read('form_venta_mayor');
      $this->Session->delete('form_venta_mayor');
    }
    $this->set(compact('precios', 'rows', 'usuario', 'usu', 'datoscli', 'recargas'));
  }

  public function registra_venta_mayor() {
    /* debug($this->request->data);
      exit; */
    foreach ($this->request->data['Movimiento'] as $dat) {
      $total = $this->get_total_dis($dat['producto_id']);
      if ($dat['salida'] > 0) {
        if ($dat['salida'] > $total) {
          $this->Session->write('form_venta_mayor', $this->request->data);
          $this->Session->setFlash('Solo hay ' . $total . ' unidades de ' . $dat['nombre_prod'] . '!!!', 'msgerror');
          $this->redirect(array('action' => 'formulario', $this->request->data['Ventastienda']['cliente_id']));
        }
      }
    }
    foreach ($this->request->data['Movimiento'] as $dat) {
      $total = $this->get_total_dis($dat['producto_id']);
      $this->Movimiento->create();
      $dat['total'] = $total - $dat['salida'];
      $this->Movimiento->save($dat);
    }
    $this->registra_recarga();
    $this->Session->setFlash('Se registro correctamente!!!', 'msgbueno');
    $this->redirect(array('action' => 'clientes'));
  }

  public function get_total_dis($idProducto = null) {
    $movimiento = $this->Movimiento->find('first', array(
      'order' => 'Movimiento.id DESC',
      'conditions' => array('Movimiento.persona_id' => $this->Session->read('Auth.User.persona_id'), 'Movimiento.producto_id' => $idProducto)
    ));
    /* debug($movimiento);
      exit; */
    if (!empty($movimiento)) {
      return $movimiento['Movimiento']['total'];
    } else {
      return 0;
    }
  }
  
  public function registra_recarga() {
    $recargas = $this->request->data['Recarga'];
    if (!empty($recargas)) {
      foreach ($recargas as $data) {
        if (!empty($data['monto'])) {
          //debug($data);
          $this->Recarga->create();
          $this->Recarga->save($data);
        }
      }
    }
  }

  function vermapa($id = null) {
    $cliente = $this->Cliente->findById($id);
    //           debug($cliente);
    $this->set(compact('cliente'));
  }

  function ajaxmapa($id = null) {
    $this->layout = "ajax";
    //debug($idmapa);
    // $img = $this->Ruta->findById($idmapa);
    //debug($img);
    // $direccion = $img['Ruta']['dir_imagen'];
    // $imagen = $img['Ruta']['imagen'];
    // $this->set(compact('direccion', 'imagen'));
    $cliente = $this->Cliente->findById($id);
    //           debug($cliente);
    $this->set(compact('cliente'));
  }

  function ajaxobs($id_cli = null) {
    $this->layout = "ajax";

    if (!empty($this->data)) {
      //debug($this->data);exit;
      $this->Detalleobservacione->create();

      if ($this->Detalleobservacione->save($this->data)) {
        $this->Session->setFlash('Los datos fueron modificados');
        $this->redirect(array('action' => 'index'), null, true);
      } else {
        $this->Session->setFlash('no se pudo modificar!!');
      }
    } else {
      $idusu = $this->Session->read('usuario_id');
      $obse = $this->Tiposobservacione->find('list', array('fields' => array('Tiposobservacione.id',
          'Tiposobservacione.nombre')));
      $this->set(compact('obse', 'id_cli', 'idusu'));
    }
  }

  public function reporte1492() {
    $this->layout = "imprimetabla";
    $usuario_id = $this->Session->read('Auth.User.id');

    $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
    $persona = $this->Session->read('Auth.User.Persona.id');
    $hoy = date('Y-m-d');
    $dia = $hoy;
    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));

    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'fields' => array(
        'Count(Productosprecio.id) as cantidad',
        'Producto.nombre',
        'Producto.id'),
      'group' => array('Productosprecio.producto_id')));
    //debug($rows);
    // debug($precios);
//debug($hoy);exit;
    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
    //debug($ventas);
    $clientes = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'group' => array('Ventasdistribuidore.cliente_id'),
      'order' => array('Ventasdistribuidore.cliente_id ASC')
    ));
    //debug($clientes);exit;
    $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
    //debug($sql);exit;

    $recargas = $this->Recarga->find('all', array(
      'conditions' => array(
        'Recarga.created' => $hoy,
        'Recarga.user_id' => $usuario_id)));
    //debug($recargas);exit; 
    //if (empty($ventas)) {
    /* $ruta = $this->Ruteo->find('first', array('conditions' => array('Ruteo.dia' => $dia, 'Ruteo.distribuidor_id' => $usuario_id),
      'fields' => array('Ruteo.id')
      )); */
    //debug($ruta);exit;
    /* $clientes = $this->Listacliente->find('all', array('conditions' => array('Listacliente.ruteo_id' =>
      $ruta['Ruteo']['id'], 'Listacliente.distribuidor_id' => $usuario_id),
      'group' => array('Listacliente.cliente_id')
      )); */
    //debug($clientes);exit;
    // $ides = array();
    //$i = 0;
    /* foreach ($clientes as $id) {
      $ides[$i] = $id['Listacliente']['cliente_id'];
      $i++;
      } */
    //debug($ides);exit;
    /* $obs = $this->Detalleobservacione->find('all', array('conditions' => array('Detalleobservacione.fecha_registro' =>
      $hoy, 'Detalleobservacione.cliente_id' => $ides))); */

    //debug($obs);exit;
    //}
    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $hoy, 'Deposito.persona_id' => $persona)
    ));
    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
  }

  public function reporte1492nuevo($hoy = null) {
    //debug($hoy);exit;
    $this->layout = "imprimetabla";
    $usuario_id = $this->Session->read('Auth.User.id');

    $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
    $persona = $this->Session->read('Auth.User.Persona.id');
    //$hoy = date('Y-m-d');
    $dia = $hoy;
    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));

    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'fields' => array(
        'Count(Productosprecio.id) as cantidad',
        'Producto.nombre',
        'Producto.id'),
      'group' => array('Productosprecio.producto_id')));
    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
    //debug($ventas);
    $clientes = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'group' => array('Ventasdistribuidore.cliente_id'),
      'order' => array('Ventasdistribuidore.cliente_id ASC')
    ));
    //debug($clientes);exit;
    $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
    //debug($sql);exit;

    $recargas = $this->Recarga->find('all', array(
      'conditions' => array(
        'Recarga.created' => $hoy,
        'Recarga.user_id' => $usuario_id)));
    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $hoy, 'Deposito.persona_id' => $persona)
    ));
    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
  }

  public function reporte1492fecha() {
    //debug($this->request->data['Ventasdistribuidor']['fecha']);exit;
    if ($this->request->data['Ventasdistribuidor']['fecha'] != null) {
      $this->redirect(array('controller' => 'Ventasdistribuidor', 'action' => 'reporte1492nuevo', $this->request->data['Ventasdistribuidor']['fecha']), null, true);
    }
  }

  //reporte por clientes para distirbuidor
  function reporte149() {
    $this->layout = 'default';
    $usuario_id = $this->Session->read('Auth.User.id');

    $distribuidor = $this->Session->read('Auth.User.Persona.nombre');

    $hoy = date('Y-m-d');
    $dia = $hoy;
    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'order' => array('Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));

    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'fields' => array(
        'Count(Productosprecio.id) as cantidad',
        'Producto.nombre',
        'Producto.id'),
      'group' => array('Productosprecio.producto_id')));
    //debug($rows);exit;
//debug($hoy);exit;
    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
    // debug($ventas);
    $clientes = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'group' => array('Ventasdistribuidore.cliente_id'),
      'order' => array('Ventasdistribuidore.cliente_id ASC')
    ));
    // debug($clientes);exit;
    $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
    //debug($sql);exit;

    $recargas = $this->Recarga->find('all', array('conditions' => array('Recarga.created' => $hoy, 'Recarga.user_id' => $usuario_id)));
    //debug($recargas);exit; 
    //if (empty($ventas)) {
    /* $ruta = $this->Ruteo->find('first', array('conditions' => array('Ruteo.dia' => $dia, 'Ruteo.distribuidor_id' => $usuario_id),
      'fields' => array('Ruteo.id')
      )); */
    //debug($ruta);exit;
    /* $clientes = $this->Listacliente->find('all', array('conditions' => array('Listacliente.ruteo_id' =>
      $ruta['Ruteo']['id'], 'Listacliente.distribuidor_id' => $usuario_id),
      'group' => array('Listacliente.cliente_id')
      )); */
    //debug($clientes);exit;
    // $ides = array();
    //$i = 0;
    /* foreach ($clientes as $id) {
      $ides[$i] = $id['Listacliente']['cliente_id'];
      $i++;
      } */
    //debug($ides);exit;
    /* $obs = $this->Detalleobservacione->find('all', array('conditions' => array('Detalleobservacione.fecha_registro' =>
      $hoy, 'Detalleobservacione.cliente_id' => $ides))); */

    //debug($obs);exit;
    //}
    $this->set(compact('recargas', 'obs', 'ventas', 'hoy', 'distribuidor'));
  }

  public function deposito() {
    debug($this->request->data);
    exit;
  }

  //fin reportes por cliente distribuidor
  public function cambiopass($id = null) {
    $this->User->id = $id;
    if (!$id) {
      $this->Session->setFlash('No existe tal registro');
      $this->redirect(array('action' => 'index'), null, true);
    }
    if (empty($this->request->data)) {
      $this->request->data = $this->User->read();
    } else {
      if ($this->User->save($this->data)) {
        $this->Session->setFlash('Su Password fue modificado Exitosamente');
        $this->redirect(array('action' => 'index'), null, true);
      } else {
        $this->Session->setFlash('no se pudo modificar!!');
      }
    }
  }

  function pidecodigo_mobile() {
    $this->layout = 'vivadistribuidor_mobile';
    if (!empty($this->request->data)) {
      $codigo = $this->request->data['Cliente']['codigo'];
      $cliente = $this->Cliente->find('first', array(
        'conditions' => array(
          'Cliente.num_registro' => $codigo
      )));
      if (!empty($cliente)) {
        $idCliente = $cliente['Cliente']['id'];
        $this->redirect(array('action' => 'formulario_mobile', $idCliente));
        //$this->render('formulario_mobile');
      } else {
        $this->Session->setFlash('No existe el cliente con codigo de registro: ' . $codigo .
          ', consulte al administrador del sistema', 'msgerror_mobil');
        $this->redirect(array('action' => 'pidecodigo_mobile'));
      }
    }
  }

  function formulario_mobile($id_cli = null) {
    //$this->layout= 'ajax';
    $this->layout = 'vivadistribuidor_mobile';
    $tipo = $this->Session->read('Auth.User.group_id');
    $usu = $this->Session->read('Auth.User.id');
    $usuario = $this->Session->read('Auth.User.persona_id');
    $datoscli = $this->Cliente->findById($id_cli);
    $cod149 = $datoscli['Cliente']['num_registro'];

    if (!empty($this->request->data)) {
      //debug($this->request->data);exit;
      $estado = 0;
      $fecha = date('Y-m-d');
      $clienteId = $this->request->data['Ventasdistribuidore']['1']['cliente_id'];
      //debug($clienteId);exit;
      $ventas = $this->request->data['Ventasdistribuidore'];
      foreach ($ventas as $d) {
        if ($d['cantidad'] != 0) {
          //debug($d);exit;
          $productoid = $d['producto_id'];
          $usuario = $d['persona_id'];
          $estado = 1;
          $cantidad = $d['cantidad'];

          $producto = $this->Producto->find('first', array('conditions' => array('Producto.id' =>
              $productoid)));
          $prodnomb = $producto['Producto']['nombre'];
          //**************************************************************

          $movs = $this->Movimiento->find('first', array(
            'conditions' => array(
              'Movimiento.persona_id' => $usuario,
              'Movimiento.producto_id' => $productoid),
            'order' => array('Movimiento.id DESC'),
            'recursive' => -1
          ));

          if (empty($movs)) {
            $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror_mobil');
            $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
          } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
            $total_ante = $movs['Movimiento']['total'];
            /*             * *verificar esta parte 15 abril 2013** */
            $fechamov = $movs['Movimiento']['created'];
            if ($fechamov == date('Y-m-d')) {
              $saldo = $movs['Movimiento']['saldo'];
              $total = $movs['Movimiento']['total'] - $cantidad;
              $ingreso = $movs['Movimiento']['ingreso'];
              $venta = $movs['Movimiento']['salida'] + $cantidad;
            } else {
              $saldo = $movs['Movimiento']['total'];
              $total = $movs['Movimiento']['total'] - $cantidad;
              $ingreso = 0;
              $venta = $cantidad;
            }
            // debug($total_ante);exit;
            if ($total_ante >= $cantidad) {

              //**************************************************************
              //guarda la venta
              //**************************************************************
              $this->Ventasdistribuidore->create();
              if (!($this->Ventasdistribuidore->save($d))) {
                $this->Session->setFlash('No se pudo guardar la venta del producto: ' . $prodnomb .
                  ', consulte al administrador del sistema', 'msgerror_mobil');
                $this->redirect(array('action' => 'formulario_mobile', $this->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
              } else {
                $idventa = $this->Ventasdistribuidore->getLastInsertID();
              }
            } else {
              $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
              $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id' => $idventa));
              $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
            }
            //debug($idventa);exit;
            //**************************************************************
            //guarda el movimiento de la venta
            //**************************************************************

            $this->Movimiento->create();

            $this->request->data['Movimiento']['producto_id'] = $productoid;
            $this->request->data['Movimiento']['user_id'] = $usu;
            $this->request->data['Movimiento']['persona_id'] = $usuario;
            $this->request->data['Movimiento']['ingreso'] = $ingreso;
            $this->request->data['Movimiento']['saldo'] = $saldo;
            $this->request->data['Movimiento']['salida'] = $venta;
            $this->request->data['Movimiento']['total'] = $total;
            $this->request->data['Movimiento']['ventasdistribuidore_id'] = $idventa;

            //debug($this->request->data['Movimiento']);exit;
            if (!($this->Movimiento->save($this->request->data['Movimiento']))) {
              $this->Session->setFlash('No se pudo guardar el movimiento del producto:' . $prodnomb .
                ', consulte al administrador del sistema', 'msgerror_mobil');
              $this->redirect(array('action' => 'formulario_mobile', $this->request->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
            }

            //**************************************************************
          } elseif ($movs['Movimiento']['total'] == 0) {
            //debug($movs);exit;
            $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
              '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
            //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
            $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
          } else {
            $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
              '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
            //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
            $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
          }

          //**************************************************************
        } //fin del if decantidad != 0
        else {
          $this->Ventasdistribuidore->create();
          $this->Ventasdistribuidore->save($d);
        }
      } //end del recorrido de datos del thisdata


      $recargas = $this->request->data['Recarga'];

      if (!empty($recargas)) {
        foreach ($recargas as $data) {
          //debug($r);exit;
//                        $id = $r['Recarga']['id'];
//                        $this->Recarga->id = $id;
//                        $this->data = $this->Recarga->read();
//                        $this->request->data['Recarga']['estado'] = 1;
          if (!empty($data['monto'])) {
            //debug($data);
            $this->Recarga->create();
            if (!($this->Recarga->save($data))) {
              $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror_mobil');
              $this->redirect(array('action' => 'index'), null, true);
            } else {
              $this->requestAction(array('controller' => 'Recargas', 'action' => 'notifica'));
            }
          }
        }
      }

      $this->Session->setFlash('Venta registrada!!!!!', 'msgbueno_mobil');
      $this->redirect(array('action' => 'pidecodigo_mobile'), null, true);
    } else {


      $fecha = date('Y-m-d');
      /* $recargas = $this->Recarga->find('all', array('conditions' => array(
        'Recarga.cod149' => $cod149,
        'Recarga.distribuidor_id' => $usu,
        'Recarga.fecha' => $fecha))); */
      // debug($recargas);exit;
      $precios = $this->Productosprecio->find('all', array(
        'conditions' => array(
          'Productosprecio.tipousuario_id' => 3,
          'Producto.proveedor like' => 'VIVA',
          'Producto.estado' => '1'),
        'order' => array('Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
          'Productosprecio.escala')));

      $rows = $this->Productosprecio->find('all', array(
        'conditions' => array(
          'Productosprecio.tipousuario_id' => 3,
          'Producto.proveedor like' => 'VIVA',
          'Producto.estado' => '1'),
        'fields' => array(
          'Count(Productosprecio.id) as cantidad',
          'Producto.nombre',
          'Producto.id'),
        'group' => array('Productosprecio.producto_id')));


      //$this->set(compact('precios', 'rows', 'usu', 'datoscli', 'recargas'));
      $this->set(compact('precios', 'rows', 'usuario', 'usu', 'datoscli', 'recargas'));
    }
  }

  public function registrafecha_mobile() {
    $this->layout = 'vivadistribuidor_mobile';
    if (!empty($this->request->data)) {
      $codigo = $this->request->data['Cliente']['codigo'];
      $fecha = $this->request->data['Cliente']['fecha'];
      $cliente = $this->Cliente->find('first', array(
        'conditions' => array(
          'Cliente.num_registro' => $codigo
      )));
      if (!empty($cliente)) {
        $idCliente = $cliente['Cliente']['id'];
        $fecha = $this->request->data['Cliente']['fecha'];
        list($mes, $d�a, $a�o) = explode('/', $fecha);
        /* debug($mes);
          debug($d�a);
          debug($a�o);exit; */
        //debug($this->request->data);exit;
        $this->redirect(array('action' => 'formulario2_mobile', $idCliente, $a�o.'-'.$mes.'-'.$d�a));
      } else {
        $this->Session->setFlash('No existe el cliente con codigo de registro: ' . $codigo .
          ', consulte al administrador del sistema', 'msgerror_mobil');
        $this->redirect(array('action' => 'pidecodigo_mobile'), null, true);
      }
    }
  }

  public function login() {
    $this->layout = 'login_mobile';
    if ($this->request->is('post')) {
      if ($this->Auth->login()) {

        $this->redirect(array('action' => 'pidecodigo_mobile'));
      } else {
        $this->Session->setFlash('Usuario o password incorrectos', 'msgerror_mobil');
      }
    }
  }

  public function formulario2_mobile($id_cli = null, $fecha = null) {
    //debug($fecha);exit;
    $this->layout = 'vivadistribuidor_mobile';
    $tipo = $this->Session->read('Auth.User.group_id');
    $usu = $this->Session->read('Auth.User.id');
    $usuario = $this->Session->read('Auth.User.persona_id');
    $datoscli = $this->Cliente->findById($id_cli);
    $cod149 = $datoscli['Cliente']['num_registro'];

    if (!empty($this->request->data)) {
      //debug($this->request->data);exit;
      $estado = 0;
      $clienteId = $this->request->data['Ventasdistribuidore']['1']['cliente_id'];
      //debug($clienteId);exit;
      $ventas = $this->request->data['Ventasdistribuidore'];
      foreach ($ventas as $d) {
        if ($d['cantidad'] != 0) {
          //debug($d);exit;
          $productoid = $d['producto_id'];
          $usuario = $d['persona_id'];
          $estado = 1;
          $cantidad = $d['cantidad'];

          $producto = $this->Producto->find('first', array('conditions' => array('Producto.id' =>
              $productoid)));
          $prodnomb = $producto['Producto']['nombre'];
          //**************************************************************

          $movs = $this->Movimiento->find('first', array(
            'conditions' => array(
              'Movimiento.persona_id' => $usuario,
              'Movimiento.producto_id' => $productoid),
            'order' => array('Movimiento.id DESC'),
            'recursive' => -1
          ));

          if (empty($movs)) {
            $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror_mobil');
            $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
          } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
            $total_ante = $movs['Movimiento']['total'];
            /*             * *verificar esta parte 15 abril 2013** */
            $fechamov = $movs['Movimiento']['created'];
            if ($fechamov == $fecha) {
              $saldo = $movs['Movimiento']['saldo'];
              $total = $movs['Movimiento']['total'] - $cantidad;
              $ingreso = $movs['Movimiento']['ingreso'];
              $venta = $movs['Movimiento']['salida'] + $cantidad;
            } else {
              $saldo = $movs['Movimiento']['total'];
              $total = $movs['Movimiento']['total'] - $cantidad;
              $ingreso = 0;
              $venta = $cantidad;
            }

            // debug($total_ante);exit;
            if ($total_ante >= $cantidad) {

              //**************************************************************
              //guarda la venta
              //**************************************************************
              $this->Ventasdistribuidore->create();
              //$d['Ventasdistribuidore']['created'] = $fecha;
              if (!($this->Ventasdistribuidore->save($d))) {
                $this->Session->setFlash('No se pudo guardar la venta del producto: ' . $prodnomb .
                  ', consulte al administrador del sistema', 'msgerror_mobil');
                $this->redirect(array('action' => 'formulario2_mobile', $this->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
              } else {
                $idventa = $this->Ventasdistribuidore->getLastInsertID();
              }
            } else {
              $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
              $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id' => $idventa));
              $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
            }
            //debug($idventa);exit;
            //**************************************************************
            //guarda el movimiento de la venta
            //**************************************************************

            $this->Movimiento->create();

            $this->request->data['Movimiento']['producto_id'] = $productoid;
            $this->request->data['Movimiento']['user_id'] = $usu;
            $this->request->data['Movimiento']['persona_id'] = $usuario;
            $this->request->data['Movimiento']['ingreso'] = $ingreso;
            $this->request->data['Movimiento']['saldo'] = $saldo;
            $this->request->data['Movimiento']['salida'] = $venta;
            $this->request->data['Movimiento']['total'] = $total;
            $this->request->data['Movimiento']['ventasdistribuidore_id'] = $idventa;
            $this->request->data['Movimiento']['created'] = $fecha;
            //debug($fecha);
            //debug($this->request->data['Movimiento']);exit;
            if (!($this->Movimiento->save($this->request->data['Movimiento']))) {
              $this->Session->setFlash('No se pudo guardar el movimiento del producto:' . $prodnomb .
                ', consulte al administrador del sistema', 'msgerror_mobil');
              $this->redirect(array('action' => 'formulario2_mobile', $this->request->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
            }

            //**************************************************************
          } elseif ($movs['Movimiento']['total'] == 0) {
            //debug($movs);exit;
            $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
              '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
            //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
            $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
          } else {
            $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
              '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
            //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
            $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
          }

          //**************************************************************
        } //fin del if decantidad != 0
        else {
          $this->Ventasdistribuidore->create();
          $this->Ventasdistribuidore->save($d);
        }
      } //end del recorrido de datos del thisdata


      $recargas = $this->request->data['Recarga'];

      if (!empty($recargas)) {
        foreach ($recargas as $data) {
          //debug($r);exit;

          if (!empty($data['monto'])) {
            //debug($data);
            $this->Recarga->create();
            if (!($this->Recarga->save($data))) {
              $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror_mobil');
              $this->redirect(array('action' => 'registrafecha_mobile'), null, true);
            } else {
              $this->requestAction(array('controller' => 'Recargas', 'action' => 'notifica'));
            }
          }
        }
      }

      $this->Session->setFlash('Venta registrada!!!!!', 'msgbueno_mobil');
      $this->redirect(array('action' => 'registrafecha_mobile'), null, true);
    } else {

      $precios = $this->Productosprecio->find('all', array(
        'conditions' => array(
          'Productosprecio.tipousuario_id' => 3,
          'Producto.proveedor like' => 'VIVA',
          'Producto.estado' => '1'),
        'order' => array('Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
          'Productosprecio.escala')));

      $rows = $this->Productosprecio->find('all', array(
        'conditions' => array(
          'Productosprecio.tipousuario_id' => 3,
          'Producto.proveedor like' => 'VIVA',
          'Producto.estado' => '1'),
        'fields' => array(
          'Count(Productosprecio.id) as cantidad',
          'Producto.nombre',
          'Producto.id'),
        'group' => array('Productosprecio.producto_id')));


      //$this->set(compact('precios', 'rows', 'usu', 'datoscli', 'recargas'));
      $this->set(compact('precios', 'rows', 'usuario', 'usu', 'datoscli', 'recargas', 'fecha', 'id_cli', 'fecha'));
    }
  }

  public function salir() {
    //$this->Session->setFlash('Good-Bye');
    $this->Auth->logout();
    $this->redirect(array('controller' => 'Ventasdistribuidor', 'action' => 'login'));
  }

  public function reporte1492_mobile() {
    $this->layout = "vivadistribuidor_mobile";
    $usuario_id = $this->Session->read('Auth.User.id');

    $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
    $persona = $this->Session->read('Auth.User.Persona.id');
    $hoy = date('Y-m-d');
    $dia = $hoy;
    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));

    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'fields' => array(
        'Count(Productosprecio.id) as cantidad',
        'Producto.nombre',
        'Producto.id'),
      'group' => array('Productosprecio.producto_id')));
    //debug($rows);exit;
    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
    //debug($ventas);
    $clientes = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'group' => array('Ventasdistribuidore.cliente_id'),
      'order' => array('Ventasdistribuidore.cliente_id ASC')
    ));
    //debug($clientes);exit;
    $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
    //debug($sql);exit;

    $recargas = $this->Recarga->find('all', array(
      'conditions' => array(
        'Recarga.created' => $hoy,
        'Recarga.user_id' => $usuario_id)));

    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $hoy, 'Deposito.persona_id' => $persona)
    ));
    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
  }

  public function reporte1492fecha_mobile() {
    $this->layout = 'vivadistribuidor_mobile';
    //debug($this->request->data['Ventasdistribuidor']['fecha']);exit;
    $fecha = $this->request->data['Ventasdistribuidor']['fecha'];
    list($mes, $d�a, $a�o) = explode('/', $fecha);
    if ($this->request->data['Ventasdistribuidor']['fecha'] != null) {
      $this->redirect(array('controller' => 'Ventasdistribuidor', 'action' => 'reporte1492nuevo_mobile', $a�o.'-'.$mes.'-'.$d�a), null, true);
    }
  }

  public function reporte1492nuevo_mobile($hoy = null) {
    //debug($hoy);exit;
    $this->layout = "vivadistribuidor_mobile";
    $usuario_id = $this->Session->read('Auth.User.id');

    $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
    $persona = $this->Session->read('Auth.User.Persona.id');
    //$hoy = date('Y-m-d');
    $dia = $hoy;
    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));

    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Producto.estado' => '1'),
      'fields' => array(
        'Count(Productosprecio.id) as cantidad',
        'Producto.nombre',
        'Producto.id'),
      'group' => array('Productosprecio.producto_id')));
    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
    //debug($ventas);
    $clientes = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'group' => array('Ventasdistribuidore.cliente_id'),
      'order' => array('Ventasdistribuidore.cliente_id ASC')
    ));
    //debug($clientes);exit;
    $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
    //debug($sql);exit;

    $recargas = $this->Recarga->find('all', array(
      'conditions' => array(
        'Recarga.created' => $hoy,
        'Recarga.user_id' => $usuario_id)));
    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $hoy, 'Deposito.persona_id' => $persona)
    ));
    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
  }

  public function cambiopass_mobile() {
    $this->layout = 'vivadistribuidor_mobile';
    $id = $this->Session->read('Auth.User.id');
    $this->User->id = $id;
    if (!$id) {
      $this->Session->setFlash('No existe tal registro');
      $this->redirect(array('action' => 'index'), null, true);
    }
    if (empty($this->request->data)) {
      $this->request->data = $this->User->read();
    } else {
      if ($this->User->save($this->data)) {
        $this->Session->setFlash('Su Password fue modificado Exitosamente', 'msgbueno_mobil');
        $this->redirect(array('action' => 'pidecodigo_mobile'), null, true);
      } else {
        $this->Session->setFlash('no se pudo modificar!!', 'msgerror_mobil');
      }
    }
  }

  public function clientes() {
    if ($this->RequestHandler->responseType() == 'json') {
      $asignar = '<button class="button blue-gradient compact icon-list" type="button" onclick="asignar(' . "',Cliente.id,'" . ')">Asignar</button>';
      $venta = '<button class="button green-gradient compact icon-list" type="button" onclick="venta(' . "',Cliente.id,'" . ')">Venta</button>';
      $acciones = "$asignar $venta";
      $this->Cliente->virtualFields = array(
        'acciones' => "CONCAT('$acciones')"
      );
      $this->paginate = array(
        'fields' => array('Cliente.num_registro', 'Cliente.nombre', 'Cliente.direccion', 'Cliente.celular', 'Cliente.zona', 'Cliente.acciones'),
        'recursive' => -1,
        'order' => 'Cliente.id DESC',
        'conditions' => array('Cliente.ruta_id' => $this->Session->read('Auth.User.ruta_id'))
      );
      $this->DataTable->fields = array('Cliente.num_registro', 'Cliente.nombre', 'Cliente.direccion', 'Cliente.celular', 'Cliente.zona', 'Cliente.acciones');
      //$this->DataTable->emptyEleget_usuarios_adminments = 1;
      $this->set('clientes', $this->DataTable->getResponse('Ventasdistribuidor', 'Cliente'));
      $this->set('_serialize', 'clientes');
    }
  }

  public function chips($idCliente = null) {
    $cliente = $this->Cliente->find('first', array('recursive' => -1, 'fields' => array('Cliente.nombre'), 'conditions' => array('Cliente.id' => $idCliente)));

    if ($this->RequestHandler->responseType() == 'json') {
      $this->paginate = array(
        'fields' => array('Chip.id', 'Chip.cantidad', 'Chip.cantidad', 'Chip.sim', 'Chip.imsi', 'Chip.telefono', 'Chip.fecha'),
        'recursive' => 0,
        'order' => 'Chip.created'
        , 'conditions' => array('Chip.distribuidor_id' => $this->Session->read('Auth.User.id'), 'Chip.cliente_id' => NULL)
      );
      $this->DataTable->fields = array('Chip.id', 'Chip.cantidad', 'Chip.cantidad', 'Chip.sim', 'Chip.imsi', 'Chip.telefono', 'Chip.fecha');
      $this->DataTable->emptyEleget_usuarios_adminments = 1;
      $this->set('chips', $this->DataTable->getResponse('Ventasdistribuidor', 'Chip'));
      $this->set('_serialize', 'chips');
    }
    $this->set(compact('cliente', 'idCliente'));
  }

  public function registra_asignado() {
    $datos = $this->request->data['Dato'];
    if (!empty($datos['rango_ini']) && !empty($datos['cantidad'])) {
      $chips = $this->Chip->find('all', array(
        'recursive' => -1,
        'order' => 'Chip.id', 'limit' => $datos['cantidad'], 'fields' => array('Chip.id'),
        'conditions' => array('Chip.id >=' => $datos['rango_ini'], 'Chip.distribuidor_id' => $this->Session->read('Auth.User.id'))
      ));
      foreach ($chips as $ch) {
        $this->Chip->id = $ch['Chip']['id'];
        $dato['Chip']['cliente_id'] = $datos['cliente_id'];
        $this->Chip->save($dato['Chip']);
      }
      $this->Session->setFlash('Se asigno correctamente', 'msgbueno');
    } else {
      $this->Session->setFlash('No se pudo registrar', 'msgerror');
    }
    $this->redirect(array('action' => 'chips', $datos['cliente_id']));
  }
  
  public function reporte_detallado_precio() {
    $fecha_ini = $this->request->data['Dato']['fecha_ini'];
    $fecha_fin = $this->request->data['Dato']['fecha_fin'];
    $persona = $this->Session->read('Auth.User.persona_id');
    $datos = array();
    if (!empty($this->request->data['Dato'])) {
      $sql1 = "(SELECT IF(ISNULL(mo.total),0,mo.total) FROM movimientos mo WHERE mo.persona_id = $persona AND mo.created >= '$fecha_ini' AND mo.created <= '$fecha_fin' AND Producto.id = mo.producto_id ORDER BY mo.id DESC LIMIT 1)";
      $this->Movimiento->virtualFields = array(
        'total_s' => "CONCAT($sql1)"
      );
      $datos = $this->Movimiento->find('all', array(
        'recursive' => 0, 'order' => 'Movimiento.producto_id',
        'conditions' => array('Movimiento.persona_id' => $persona, 'Movimiento.created >=' => $fecha_ini, 'Movimiento.created <=' => $fecha_fin,'Movimiento.salida !=' => NULL),
        'group' => array('Movimiento.producto_id'),
        'fields' => array('Producto.nombre', 'SUM(Movimiento.ingreso) entregado', 'Producto.id', 'Movimiento.total_s')
      ));
      foreach ($datos as $key => $da) {
        $datos_aux = $this->Movimiento->find('all', array(
          'recursive' => -1, 'order' => 'Movimiento.producto_id',
          'conditions' => array('Movimiento.persona_id' => $persona, 'Movimiento.created >=' => $fecha_ini, 'Movimiento.created <=' => $fecha_fin, 'Movimiento.precio_uni !=' => NULL, 'Movimiento.producto_id' => $da['Producto']['id'],'Movimiento.salida !=' => NULL),
          'group' => array('Movimiento.precio_uni'),
          'fields' => array('SUM(Movimiento.salida) vendidos', 'Movimiento.precio_uni', '(Movimiento.precio_uni*SUM(Movimiento.salida)) precio_total', 'Movimiento.producto_id')
        ));
        $datos[$key]['precios'] = $datos_aux;
        //debug($datos);exit;
      }
    }
    $this->set(compact('datos'));
  }

  public function reporte_cliente() {
    $fecha_ini = $this->request->data['Dato']['fecha_ini'];
    $fecha_fin = $this->request->data['Dato']['fecha_fin'];
    $persona = $this->Session->read('Auth.User.persona_id');
    $datos = array();
    if (!empty($this->request->data['Dato'])) {
      $datos = $this->Movimiento->find('all', array(
        'recursive' => 0, 'order' => 'Movimiento.producto_id',
        'conditions' => array('Movimiento.persona_id' => $persona, 'Movimiento.created >=' => $fecha_ini, 'Movimiento.created <=' => $fecha_fin,'Movimiento.salida !=' => NULL,'Movimiento.cliente_id !=' => NULL),
        'group' => array('Movimiento.cliente_id'),
        'fields' => array('Cliente.nombre','Cliente.num_registro','Movimiento.cliente_id', 'SUM(Movimiento.salida) ventas', 'SUM(Movimiento.precio_uni*Movimiento.salida)')
      ));
      foreach ($datos as $key => $da){
        $datos_aux = $this->Movimiento->find('all', array(
          'recursive' => 0, 'order' => 'Movimiento.producto_id',
          'conditions' => array('Movimiento.persona_id' => $persona, 'Movimiento.created >=' => $fecha_ini, 'Movimiento.created <=' => $fecha_fin, 'Movimiento.precio_uni !=' => NULL, 'Movimiento.cliente_id' => $da['Movimiento']['cliente_id'],'Movimiento.salida !=' => 'null'),
          'group' => array('Movimiento.producto_id','Movimiento.precio_uni'),
          'fields' => array('Producto.nombre','SUM(Movimiento.salida) vendidos', 'Movimiento.precio_uni', '(Movimiento.precio_uni*SUM(Movimiento.salida)) precio_total')
        ));
        $datos[$key]['productos'] = $datos_aux;
      }
      //debug($datos);exit;
    }
    $this->set(compact('datos'));
  }

}

?>