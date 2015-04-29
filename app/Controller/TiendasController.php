<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 */

class TiendasController extends AppController {

  public $name = 'Tiendas';
  public $layout = 'tienda';
  public $uses = array(
    'Productosprecio',
    'Sucursal',
    'User',
    'Movimiento',
    'Producto',
    'Ventastienda',
    'Cliente',
    'Tiposproducto',
    'Almacene',
    'Recarga',
    'Deposito', 'Recargascabina', 'Cabina', 'Movimientoscabina'
  );

  public function beforeFilter() {
    parent::beforeFilter();
    $this->Auth->allow();
  }

  public function ajaxpidetienda($id = null, $idProducto = null, $precio = null) {
    
  }

  public function get_id_almacen() {
    $almacen = $this->Almacene->find('first', array(
      'conditions' => array('Almacene.sucursal_id' => $this->Session->read('Auth.User.sucursal_id'))
    ));
    return $almacen['Almacene']['id'];
  }

  public function get_total_almacen($idProducto = null) {
    $movimiento = $this->Movimiento->find('first', array(
      'order' => 'Movimiento.id DESC',
      'conditions' => array('Movimiento.almacene_id' => $this->get_id_almacen(), 'Movimiento.producto_id' => $idProducto)
    ));
    /*debug($movimiento);
    exit;*/
    if (!empty($movimiento)) {
      return $movimiento['Movimiento']['total'];
    } else {
      return 0;
    }
  }

  public function ajax_cantidad_prod($idProducto = null) {
    $this->layout = 'ajax';
    $producto = $this->Producto->findByid($idProducto, null, null, -1);
    $cantidad = $this->get_total_almacen($idProducto);
    //debug($cantidad);exit;
    $this->set(compact('producto', 'cantidad'));
  }

  public function registra_venta_t() {
    $this->Ventastienda->create();
    $this->request->data['Ventastienda']['sucursal_id'] = $this->Session->read('Auth.User.sucursal_id');
    $this->request->data['Ventastienda']['user_id'] = $this->Session->read('Auth.User.id');
    $this->request->data['Ventastienda']['escala'] = 'TIENDA';
    $this->Ventastienda->save($this->request->data['Ventastienda']);
    $idVenta = $this->Ventastienda->getLastInsertID();
    foreach ($this->request->data['productos'] as $key => $ped) {
      $total = $this->get_total_almacen($key);
      if ($ped['cantidad'] <= $total) {
        $this->Movimiento->create();
        $this->request->data['Movimiento']['producto_id'] = $key;
        $this->request->data['Movimiento']['user_id'] = $this->Session->read('Auth.User.id');
        $this->request->data['Movimiento']['almacene_id'] = $this->get_id_almacen();
        $this->request->data['Movimiento']['salida'] = $ped['cantidad'];
        $this->request->data['Movimiento']['ingreso'] = 0;
        $this->request->data['Movimiento']['total'] = $total - $ped['cantidad'];
        $this->request->data['Movimiento']['ventastienda_id'] = $idVenta;
        $this->Movimiento->save($this->request->data['Movimiento']);
      } else {
        $this->Movimiento->deleteAll(array('Movimiento.ventastienda_id' => $idVenta));
        $this->Ventastienda->delete($idVenta);
        $this->Session->setFlash('No se pudo registrar verifique las cantidades antes!!!', 'msgerror');
        $this->redirect(array('action' => 'index'));
      }
    }
    $this->Session->setFlash('Venta registrada', 'msgbueno');
    $this->redirect(array('action' => 'index'));
  }

  public function ajaxquita($id = null, $canti = null, $precio = null) {

    $this->layout = 'ajax';
    $cantidad = $canti - 1;
    $movimiento = $this->Movimiento->find('first', array('order' => 'Movimiento.id DESC', 'conditions' => array('ventastienda_id' => $id)));
    $idmovimiento = $movimiento['Movimiento']['id'];
    $nuevoprecio = $precio * $cantidad;
    if ($cantidad == 0) {

      $this->Ventastienda->delete($id);
      $this->Movimiento->delete($idmovimiento);
    } else {
      $data = array('id' => $id, 'cantidad' => $cantidad, 'total' => $nuevoprecio);

      $this->Ventastienda->save($data);
      $this->Movimiento->delete($idmovimiento);
    }

    $fecha = date('Y-m-d');

    $ventas = $this->Ventastienda->find('all', array(
      'recursive' => 0,
      'conditions' => array(
        'Ventastienda.created' => $fecha,
        'Ventastienda.pedidotemporal' => 1
      ),
      'group' => array('Ventastienda.producto_id')
    ));
    $cantidades = $this->Ventastienda->find('all', array(
      'conditions' => array('Ventastienda.created' => $fecha,
        'Ventastienda.pedidotemporal' => 1),
      'fields' => array(
        'sum(Ventastienda.cantidad) as cantidad',
        'sum(Ventastienda.total) as total'
    )));

    $this->set(compact('ventas', 'cantidades'));
  }

  public function index() {
    $productos = $this->Productosprecio->find('all', array(
      'recursive' => 0,
      'conditions' => array(
        'Productosprecio.escala' => 'TIENDA',
        'Productosprecio.tipousuario_id' => 2
      )
    ));
    /*$categorias = $this->Producto->find('all', array(
      'recursive' => 0,
      'conditions' => array('Producto.tiposproducto_id !=' => 0),
      'group' => 'Producto.tiposproducto_id'
    ));*/
    $categorias = $this->Tiposproducto->find('all',array('recursive' => -1));
    //debug($categorias); exit;       
    $this->set(compact('productos', 'categorias'));
  }

  public function cerrarventa() {
    $fecha = date('Y-m-d');

    $ventas = $this->Ventastienda->find('all', array(
      'recursive' => 0,
      'conditions' => array(
        'Ventastienda.created' => $fecha,
        'Ventastienda.pedidotemporal' => 1),
      'group' => array('Ventastienda.producto_id')
    ));

    foreach ($ventas as $v) {
      $id = $v['Ventastienda']['id'];
      $data = array('id' => $id, 'pedidotemporal' => 2);


      $this->Ventastienda->save($data);
    }
    $this->Session->setFlash('Venta registrada', 'msgbueno');
    $dato = $ventas;
    $this->redirect(array('action' => 'index'));
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
          ', consulte al administrador del sistema', 'msgerror');
        $this->redirect(array('action' => 'pidecodigo'), null, true);
      }
    }
  }

  function formulario($id_cli = null) {

    $usu = $this->Session->read('Auth.User.id');
    $sucursal = $this->Session->read('Auth.User.sucursal_id');

    $almacen = $this->Almacene->find('first', array('conditions' => array('Almacene.sucursal_id' => $sucursal)));

    $idAlmacen = $almacen['Almacene']['id'];

    $datoscli = $this->Cliente->findById($id_cli);

    $precios = $this->Productosprecio->find('all', array(
      'conditions' => array(
        'Productosprecio.tipousuario_id' => 3,
        'Producto.proveedor like' => 'VIVA',
        'Productosprecio.escala' => 'MAYOR',
        'Producto.estado' => '1'),
      'order' => array('Producto.tipo_producto DESC', 'Productosprecio.precio DESC',
        'Productosprecio.escala')));
    //debug($precios);exit;
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
    $this->set(compact('precios', 'rows', 'idAlmacen', 'usu', 'datoscli', 'sucursal', 'recargas'));
  }

  public function registra_venta_mayor() {
    /* debug($this->request->data);
      exit; */
    $this->Ventastienda->create();
    $this->Ventastienda->save($this->request->data['Ventastienda']);
    $idVenta = $this->Ventastienda->getLastInsertID();
    foreach ($this->request->data['Movimiento'] as $dat) {
      $total = $this->get_total_almacen($dat['producto_id']);
      if ($dat['salida'] > 0) {
        if ($dat['salida'] <= $total) {
          $dat['ventastienda_id'] = $idVenta;
          $this->Movimiento->create();
          $dat['total'] = $total - $dat['salida'];
          $this->Movimiento->save($dat);
        } else {
          $this->Session->write('form_venta_mayor', $this->request->data);
          $this->Movimiento->deleteAll(array('Movimiento.ventastienda_id' => $idVenta));
          $this->Ventastienda->delete($idVenta);
          $this->Session->setFlash('Solo hay ' . $total . ' unidades de ' . $dat['nombre_prod'] . '!!!', 'msgerror');
          $this->redirect(array('action' => 'formulario', $this->request->data['Ventastienda']['cliente_id']));
        }
      }
    }
    $this->registra_recarga();
    $this->Session->setFlash('Se registro correctamente!!!', 'msgbueno');
    $this->redirect(array('action' => 'pidecodigo'));
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

//fin formulario

  public function reporteventasxmayorproductos() {
    $this->layout = "imprimetabla";
    $fecha = date('Y-m-d');
    $sucursal = $this->Session->read('Auth.User.sucursal_id');
    //debug($sucursal);
    $almacen = $this->Almacene->find('first', array('conditions' => array('Almacene.sucursal_id' => $sucursal)));
    $idAlmacen = $almacen['Almacene']['id'];
    //debug($idAlmacen);
    //debug($almacen);exit;
    $nombre = $this->Session->read('Auth.User.Sucursal.nombre');

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

    $fech = $fecha . '%';
    $recargas = $this->Recarga->find('all', array(
      'conditions' => array('Recarga.sucursal_id' => $sucursal, 'Recarga.created like' => $fech)
    ));

    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $fecha, 'Deposito.sucursal_id' => $sucursal)
    ));
    $this->set(compact('nombre', 'hora', 'fecha', 'rows', 'precios', 'idAlmacen', 'recargas', 'deposito', 'sucursal'));
  }

  public function reporte149($sucursal = null, $hoy = null) {
    $this->layout = "imprimetabla";
    $usuario_id = $this->Session->read('Auth.User.id');

    $datossucursal = $this->Sucursal->find(
      'first', array(
      'conditions' => array('Sucursal.id' => $sucursal),
      'recursive' => -1));

    $nombre = $datossucursal['Sucursal']['nombre'];
    if (empty($hoy)) {
      $hoy = date('Y-m-d');
    }

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

    $ventas = $this->Ventastienda->find('all', array(
      'conditions' => array(
        'Ventastienda.created' => $hoy,
        'Ventastienda.cliente_id !=' => NULL
      ),
      'order' => array('Ventastienda.cliente_id ASC', 'Ventastienda.producto_id ASC', 'Ventastienda.precio DESC')));
    //debug($ventas);exit;
    $clientes = $this->Ventastienda->find('all', array(
      'conditions' => array(
        'Ventastienda.created' => $hoy,
        'Ventastienda.cliente_id !=' => NULL
      ),
      'group' => array('Ventastienda.cliente_id'),
      'order' => array('Ventastienda.cliente_id ASC')
    ));

    $hoy = $hoy . ' %';

    $recargas = $this->Recarga->find('all', array(
      'conditions' => array(
        'Recarga.created LIKE' => $hoy,
        'Recarga.sucursal_id' => $sucursal)));

    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $hoy, 'Deposito.sucursal_id' => $sucursal)
    ));

    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'nombre', 'deposito', 'sucursal'));
  }

  public function registrardeposito() {
    if (!empty($this->request->data)) {
      $this->Deposito->create();
      if ($this->Deposito->save($this->request->data)) {
        $this->Session->setFlash('deposito registrado', 'msgbueno');
        $this->redirect(array('action' => 'index'));
      } else {
        $this->Session->setFlash('deposito registrado', 'msgbueno');
        $this->redirect(array('action' => 'registrardeposito'));
      }
    }
  }

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

  public function registrocabinas() {
    if (!empty($this->request->data)) {

      $sucursal = $this->request->data['Movimientoscabina']['sucursal_id'];
      $tipo = $this->request->data['Movimientoscabina']['tipo'];
      if ($tipo == 1) {
        $cantidad = $this->request->data['Movimientoscabina']['cantidad'];
        $producto = $this->request->data['Movimientoscabina']['producto_id'];
        $datoscarga = $this->Recargascabina->find('first', array('Recargascabina.producto_id' => $producto));

        $datos = $this->Producto->find('first', array('conditions' => array('Producto.id' => $producto)));
        $nombreproducto = $datos['Producto']['nombre'];
        $almacen = $this->Almacene->find('first', array('conditions' => array('Almacene.sucursal_id' => $sucursal)));
        $idAlmacen = $almacen['Almacene']['id'];
        $usu = $this->Session->read('Auth.User.id');
        $movs = $this->Movimiento->find('all', array(
          'conditions' => array(
            'Movimiento.almacene_id' => $idAlmacen,
            'Movimiento.producto_id' => $producto),
          'fields' => array('MAX(Movimiento.id) as id')
        ));

        if (!empty($movs)) {
          $id = $movs['0']['0']['id'];
          $movimiento = $this->Movimiento->find('first', array(
            'conditions' => array('Movimiento.id' => $id),
            'recursive' => -1));

          if ($movimiento['Movimiento']['total'] != 0 && $movimiento['Movimiento']['total'] >= $cantidad) {
            $saldo = $movimiento['Movimiento']['total'] - $cantidad;
            $fecha = date('Y-m-d');
            if ($fecha == $movimiento['Movimiento']['created']) {
              $ingreso = $movimiento['Movimiento']['ingreso'];
              $cantidadsalida = $movimiento['Movimiento']['salida'] + $cantidad;
            } else {
              $ingreso = 0;
              $cantidadsalida = $cantidad;
            }
            $total = $movimiento['Movimiento']['total'] - $cantidad;

            $this->Movimiento->create();

            $this->request->data['Movimiento']['producto_id'] = $producto;
            $this->request->data['Movimiento']['user_id'] = $usu;
            $this->request->data['Movimiento']['almacene_id'] = $idAlmacen;
            $this->request->data['Movimiento']['ingreso'] = $ingreso;
            $this->request->data['Movimiento']['saldo'] = $saldo;
            $this->request->data['Movimiento']['salida'] = $cantidadsalida;
            $this->request->data['Movimiento']['total'] = $total;


            //debug($this->request->data['Movimiento']);exit;
            if (!($this->Movimiento->save($this->request->data['Movimiento']))) {
              $this->Session->setFlash('Error en el registro (movimiento)', 'msgerror');
              $this->redirect(array('action' => 'registrocabinas'));
            } else {
              $this->Movimientoscabina->create();

              $cabina = $this->request->data['Movimientoscabina']['cabina_id'];
              $cargas = $this->Movimientoscabina->find('first', array(
                'conditions' => array('Movimientoscabina.cabina_id' => $cabina, 'Movimientoscabina.created' => $fecha)
              ));
              $montocarga = $datoscarga['Recargascabina']['monto'];
              $montorecarga = $datoscarga['Recargascabina']['monto_recarga'];

              if (!empty($cargas)) {
                $total = $cargas['Movimientoscabina']['total'] + $montorecarga;
                $saldo = $cargas['Movimientoscabina']['saldo'];
              } else {
                $total = $montorecarga + $cargas['Movimientoscabina']['saldo'];

                $cargas = $this->Movimientoscabina->find('first', array(
                  'conditions' => array('Movimientoscabina.cabina_id' => $cabina),
                  'order' => array('Movimientoscabina.id DESC')
                ));

                $saldo = $cargas['Movimientoscabina']['saldo'];
              }
              $this->request->data['Movimientoscabina']['montocarga'] = $montorecarga;
              $this->request->data['Movimientoscabina']['saldo'] = $saldo;
              $this->request->data['Movimientoscabina']['total'] = $total;

              if ($this->Movimientoscabina->save($this->request->data['Movimientoscabina'])) {
                $this->Session->setFlash('Recaraga registrada', 'msgbueno');
                $this->redirect(array('action' => 'index'));
              } else {
                $this->Session->setFlash('Error en registro', 'msgerror');
                $this->redirect(array('action' => 'index'));
              }
            }
          } else {
            $this->Session->setFlash('No le quedan o no tiene suficientes ' . $nombreproducto, 'msgerror');
            $this->redirect(array('action' => 'index'));
          }
        } else {

          $this->Session->setFlash('Error en registro no tiene ' . $nombreproducto, 'msgerror');
          $this->redirect(array('action' => 'index'));
        }
      } else {

        $cabina = $this->request->data['Movimientoscabina']['cabina_id'];
        $cargas = $this->Movimientoscabina->find('first', array(
          'conditions' => array('Movimientoscabina.cabina_id' => $cabina),
          'order' => array('Movimientoscabina.id DESC')
        ));
        $data = array('id' => $cargas['Movimientoscabina']['id'], 'saldo' => $this->request->data['Movimientoscabina']['saldo']);

        if ($this->Movimientoscabina->save($data)) {
          $this->Session->setFlash('Registro correcto', 'msgbueno');
          $this->redirect(array('action' => 'index'));
        } else {
          $this->Session->setFlash('Error en registro', 'msgerror');
          $this->redirect(array('action' => 'index'));
        }
      }
    }

    $cabinas = $this->Cabina->find('all', array(
      'conditions' => array('Cabina.sucursal_id' => $this->Session->read('Auth.User.sucursal_id')),
      'recursive' => -1));
    $tarjetas = $this->Recargascabina->find('all', array('recursive' => 1));
    //debug($tarjetas);exit;
    $this->set(compact('cabinas', 'tarjetas'));
  }

  public function reporteventashoy($idsucursal = null) {
    $this->layout = "imprimetabla";
    $fecha = date('Y-m-d');

    $cabinas = $this->Movimientoscabina->find('all', array('conditions' => array('Movimientoscabina.created' => $fecha)));

    $sucursal = $this->Sucursal->find('first', array('conditions' => array('Sucursal.id' => $idsucursal)));
    $nombre = $sucursal['Sucursal']['nombre'];
    $almacen = $this->Almacene->find('first', array('conditions' => array('Almacene.sucursal_id' => $idsucursal)));

    $idAlmacen = $almacen['Almacene']['id'];

    $rows = $this->Ventastienda->find('all', array(
      'conditions' => array(
        'Ventastienda.created' => $fecha,
        'Ventastienda.pedidotemporal' => 2),
      'group' => array('Ventastienda.producto_id'),
    ));
    //debug($idsucursal);
    $this->set(compact('rows', 'cabinas', 'nombre', 'fecha', 'idAlmacen', 'idsucursal'));
  }

  public function reporteventastienda($idsucursal = null, $fecha = null) {
    $this->layout = "imprimetabla";

    if (empty($fecha)) {
      $fecha = date('Y-m-d');
    }
    $cabinas = $this->Movimientoscabina->find('all', array('conditions' => array('Movimientoscabina.created' => $fecha)));
    $sucursal = $this->Sucursal->find('first', array('conditions' => array('Sucursal.id' => $idsucursal)));
    $nombre = $sucursal['Sucursal']['nombre'];
    $almacen = $this->Almacene->find('first', array('conditions' => array('Almacene.sucursal_id' => $idsucursal)));
    $idAlmacen = $almacen['Almacene']['id'];
    $rows = $this->Productosprecio->find('all', array(
      'conditions' => array('Productosprecio.escala' => 'TIENDA'),
      'group' => array('Productosprecio.producto_id')
    ));
    $this->set(compact('rows', 'cabinas', 'nombre', 'fecha', 'idAlmacen', 'idsucursal'));
  }
  public function report_may_xproduct(){
    
  }

}

?>
