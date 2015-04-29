

<?php

class ReportesController extends Controller {

  public $uses = array(
    'Movimiento',
    'Almacene',
    'Ventasdistribuidore',
    'Persona',
    'Producto',
    'User',
    'Productosprecio',
    'Recarga',
    'Chip',
    'Deposito',
    'Sucursal',
    'Cliente');
  public $layout = 'viva';
  public $components = array('Fechasconvert');

  public function beforeFilter() {
    parent::beforeFilter();
    // $this->Auth->allow('*');
  }

  public function reporte($fecha = null) {
    //$fecha1= $this->Fechasconvert->doFecha($fecha);
    $fecha1 = date('Y-m-d');

    $idAlmacenCentral = $this->Almacene->find('first', array(
      'conditions' => array('Almacene.central' => true)));

    $idAlmacenCentral = $idAlmacenCentral['Almacene']['id'];

    $ingresosAlmacen1 = $this->Movimiento->find('all', array(
      'fields' => array('sum(Movimiento.ingreso) as ingreso', 'Producto.id', 'Producto.nombre'),
      'conditions' => array(
        'Movimiento.almacene_id' => $idAlmacenCentral,
        'Movimiento.created' => $fecha1,
        'Movimiento.salida' => '0'),
      'group' => array('Movimiento.producto_id'),
      'order' => array('Movimiento.producto_id')
    ));

    $stockAlmacen = $this->Movimiento->find('all', array(
      'fields' => array('MAX(Movimiento.id) as id'),
      'conditions' => array(
        'Movimiento.almacene_id' => $idAlmacenCentral,
        'Movimiento.created' => $fecha1,
        'Movimiento.ingreso' => '0'),
      'group' => array('Movimiento.producto_id')
    ));

    $ides1 = array();
    $i = 0;
    foreach ($stockAlmacen as $ide) {
      $ides1[$i++] = $ide[0]['id'];
    }
    $stockAlmacen = $this->Movimiento->find('all', array(
      'fields' => array('Producto.id', 'Movimiento.saldo', 'Producto.nombre'),
      'conditions' => array(
        'Movimiento.id' => $ides1),
      'order' => array('Movimiento.producto_id')
    ));

    $i = 0;

    foreach ($ingresosAlmacen1 as $ingreso) {
      foreach ($stockAlmacen as $stock) {

        if ($stock['Producto']['id'] == $ingreso['Producto']['id']) {
          $ingresosAlmacen[$i]['0']['ingreso'] = $ingreso['0']['ingreso'];
          $ingresosAlmacen[$i]['Producto']['id'] = $ingreso['Producto']['id'];
          $ingresosAlmacen[$i]['Producto']['nombre'] = $ingreso['Producto']['nombre'];
          $ingresosAlmacen[$i]['Movimiento']['saldo'] = $stock['Movimiento']['saldo'];
          $i++;
        } else {
          $ingresosAlmacen[$i]['Movimiento']['saldo'] = $stock['Movimiento']['saldo'];
          $ingresosAlmacen[$i]['0']['ingreso'] = 0;
          $ingresosAlmacen[$i]['Producto']['id'] = $stock['Producto']['id'];
          $ingresosAlmacen[$i]['Producto']['nombre'] = $stock['Producto']['nombre'];
          $i++;
        }
      }
    }
    //debug($ingresosAlmacen);exit;
    $distribuidores = $this->Movimiento->find('all', array(
      'fields' => array('MAX(Movimiento.id) as id'),
      'conditions' => array('Movimiento.salida' => 0),
      'group' => array('Movimiento.persona_id', 'Movimiento.producto_id')));


    $ides = array();
    $i = 0;
    foreach ($distribuidores as $distribuidor) {
      $ides[$i++] = $distribuidor[0]['id'];
    }

    $entregas = $this->Movimiento->find('all', array(
      'conditions' => array('Movimiento.id' => $ides, 'Movimiento.created' => $fecha1),
      'fields' => array(
        'Movimiento.almacene_id', 'Movimiento.persona_id',
        'Movimiento.saldo', 'Movimiento.ingreso', 'Producto.nombre', 'Producto.id',
        'Almacene.nombre', 'Almacene.central', 'Persona.nombre', 'Persona.ap_paterno',
        'Persona.ap_materno', 'Movimiento.id', 'Producto.tiposproducto_id'),
      'order' => array('Movimiento.persona_id', 'Movimiento.almacene_id')
    ));
    //debug($entregas);exit;
    $this->set(compact('ingresosAlmacen', 'entregas', 'idAlmacenCentral'));
  }

  public function ventas() {
    $today = date('Y-m-d');
    //$today = '2013-04-16';
    $ventashoy = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $today),
      'recursive' => 0
      )
    );
    //debug($ventashoy);exit;
    $this->set(compact('ventashoy'));
  }

  public function form() {
    if (!empty($this->request->data)) {
      //debug($this->request->data);exit;   
      $distirbuidor = $this->request->data['Persona']['id'];
      $fecha = $this->Fechasconvert->doFormatdia($this->request->data['Persona']['fecha']);
      $datos = $this->User->find('first', array('conditions' => array('User.id' => $distirbuidor)));
      $user = $datos['User']['id'];
      $this->redirect(array('action' => 'reportedistribuidor2', $distirbuidor, $fecha));
    }
    $distribuidores = $this->User->find('all', array(
      'conditions' => array('User.group_id' => '2')
    ));
    $this->set(compact('distribuidores'));
  }

  public function form2() {
    if (!empty($this->request->data)) {
      //debug($this->request->data);exit;
      $fecha1 = $this->Fechasconvert->doFormatdia($this->request->data['Persona']['fecha1']);
      $fecha2 = $this->Fechasconvert->doFormatdia($this->request->data['Persona']['fecha2']);
      if (!empty($this->request->data['Persona']['id'])) {
        $distirbuidor = $this->request->data['Persona']['id'];

        $datos = $this->User->find('first', array('conditions' => array('Persona.id' => $distirbuidor)));
        $user = $datos['User']['id'];
        $this->redirect(array('action' => 'ventasdistribuidor', $distirbuidor, $user, $fecha1, $fecha2));
      } else {
        $cliente = $this->request->data['Persona']['cliente'];

        $datos = $this->Cliente->find('first', array('conditions' => array('Cliente.num_registro' => $cliente)));

        $user = $datos['Cliente']['id'];
        $this->redirect(array('action' => 'ventasclientes', $user, $fecha1, $fecha2));
      }
    }
    $distribuidores = $this->User->find('all', array(
      'conditions' => array('User.group_id' => '2')
    ));
    $this->set(compact('distribuidores'));
  }

  public function ventasdistribuidor($persona = null, $usuario_id = null, $desde = null, $hasta = null) {
    $this->layout = 'imprimetabla';
    $datos = $this->Persona->find('first', array(
      'conditions' => array('Persona.id' => $persona),
      'recursive' => -1));
    $distribuidor = $datos['Persona']['nombre'] . ' ' . $datos['Persona']['ap_paterno'];
    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array(
        'Ventasdistribuidore.fecha >=' => $desde,
        'Ventasdistribuidore.fecha <=' => $hasta,
        'Ventasdistribuidore.user_id' => $usuario_id),
      'fields' => array('Producto.nombre', 'sum(Ventasdistribuidore.cantidad) as cantidad', 'sum(Ventasdistribuidore.total) as total'),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC'),
      'group' => array('Ventasdistribuidore.producto_id')));
    //debug($ventas);
    $this->set(compact('ventas', 'distribuidor', 'desde', 'hasta'));
  }

  public function ventasclientes($cliente = null, $desde = null, $hasta = null) {

    $this->layout = 'imprimetabla';
    $datos = $this->Cliente->find('first', array(
      'conditions' => array('Cliente.id' => $cliente),
      'recursive' => -1));

    $ventas = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array(
        'Ventasdistribuidore.fecha >=' => $desde,
        'Ventasdistribuidore.fecha <=' => $hasta,
        'Ventasdistribuidore.cliente_id' => $cliente),
      'fields' => array('Producto.nombre', 'sum(Ventasdistribuidore.cantidad) as cantidad', 'sum(Ventasdistribuidore.total) as total'),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC'),
      'group' => array('Ventasdistribuidore.producto_id')));
    $this->set(compact('ventas', 'datos', 'desde', 'hasta'));
  }

  public function reportedistribuidor($persona = null, $usuario_id = null, $hoy = null) {

    //debug($hoy);exit;
    $this->layout = "imprimetabla";


    $datos = $this->Persona->find('first', array(
      'conditions' => array('Persona.id' => $persona),
      'recursive' => -1));
    $distribuidor = $datos['Persona']['nombre'] . ' ' . $datos['Persona']['ap_paterno'];

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
      'conditions' => array(
        'Ventasdistribuidore.fecha >=' => $desde,
        'Ventasdistribuidore.fecha <=' => $hasta,
        'Ventasdistribuidore.user_id' => $usuario_id),
      'fields' => array('Producto.nombre', 'sum(Ventasdistribuidore.cantidad) as cantidad', 'sum(Ventasdistribuidore.total) as total'),
      'order' => array('Ventasdistribuidore.cliente_id ASC', 'Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC'),
      'group' => array('Ventasdistribuidore.producto_id')));

    $clientes = $this->Ventasdistribuidore->find('all', array(
      'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
      'group' => array('Ventasdistribuidore.cliente_id'),
      'order' => array('Ventasdistribuidore.cliente_id ASC')
    ));
    //debug($clientes);exit;
    //$sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
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
    //debug($deposito);exit;
    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'usuario_id', 'deposito'));
  }

  public function reportedistribuidor2($persona = null, $hoy = null) {
    $this->layout = "imprimetabla";
    $usuario = $this->User->find('first', array('recursive' => -1, 'conditions' => array('User.persona_id' => $persona)));
    //$usuario_id = $this->Session->read('Auth.User.id');
    $usuario_id = $usuario['User']['id'];
    $per = $this->Persona->find('first', array('recursive' => -1, 'conditions' => array('Persona.id' => $persona)));

    $distribuidor = $per['Persona']['nombre'];
    //$persona =$this->Session->read('Auth.User.Persona.id');
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
    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $hoy, 'Deposito.persona_id' => $persona)
    ));
    $this->set(compact('precios', 'rows', 'clientes', 'recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito', 'usuario_id'));
  }

  public function saldos($idDistribuidor = null) {

    if (!empty($this->request->data)) {
      //debug($this->request->data);exit;
      $fecha = $this->request->data['Persona']['fecha'];
      $date = $this->Fechasconvert->doFormatdia($fecha);

      $user = $this->request->data['Persona']['id'];

      $dato = $this->User->find('first', array(
        'conditions' => array('User.id' => $user)));
      $distribuidor = $dato['User']['persona_id'];

      $this->redirect(array('action' => 'reportesaldos', $user, $distribuidor, $date));
    }
    $personas = $this->User->find('all', array('conditions' => array('User.group_id' => 2)));
    $this->set(compact('personas'));
  }

  public function reportesaldos($idUser = null, $idDistribuidor = null, $fecha = null) {
    $this->layout = "imprimetabla";
    $dato = $this->Persona->find('first', array('conditions' => array('Persona.id' => $idDistribuidor)));
    $nombre = $dato['Persona']['nombre'] . ' ' . $dato['Persona']['ap_paterno'];
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

    $recargas = $this->Recarga->find('all', array(
      'conditions' => array('Recarga.persona_id' => $idDistribuidor, 'Recarga.created' => $fecha)
    ));


    $this->set(compact('nombre', 'rows', 'precios', 'idDistribuidor', 'fecha', 'recargas'));
  }

  public function saldoshorizontal($idUser = null, $fecha = null) {

    $distribuidor = $this->User->find('first', array('conditions' => array('User.id' => $idUser)));
    $idDistribuidor = $distribuidor['User']['persona_id'];
    $this->layout = "imprimetabla";
    $dato = $this->Persona->find('first', array('conditions' => array('Persona.id' => $idDistribuidor)));
    $nombre = $dato['Persona']['nombre'] . ' ' . $dato['Persona']['ap_paterno'];


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
      'conditions' => array('Recarga.persona_id' => $idDistribuidor, 'Recarga.created like' => $fech)
    ));

    $hora = $this->Ventasdistribuidore->find('first', array('conditions' => array(
        'Ventasdistribuidore.persona_id' => $idDistribuidor, 'Ventasdistribuidore.fecha' => $fecha)
    ));

    if (empty($hora)) {
      $hora = $this->Fechasconvert->getHora($hora[0]['Recarga']['created']);
    } else
      $hora = $this->Fechasconvert->getHora($hora['Ventasdistribuidore']['created']);

    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $fecha, 'Deposito.persona_id' => $idDistribuidor)
    ));

    $this->set(compact('nombre', 'hora', 'fecha', 'rows', 'precios', 'idDistribuidor', 'recargas', 'deposito'));
  }

  public function saldoshorizontal_mobile($idUser = null, $fecha = null) {

    $distribuidor = $this->User->find('first', array('conditions' => array('User.id' => $idUser)));
    $idDistribuidor = $distribuidor['User']['persona_id'];
    $this->layout = "vivadistribuidor_mobile";
    $dato = $this->Persona->find('first', array('conditions' => array('Persona.id' => $idDistribuidor)));
    $nombre = $dato['Persona']['nombre'] . ' ' . $dato['Persona']['ap_paterno'];


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
      'conditions' => array('Recarga.persona_id' => $idDistribuidor, 'Recarga.created like' => $fech)
    ));

    $hora = $this->Ventasdistribuidore->find('first', array('conditions' => array(
        'Ventasdistribuidore.persona_id' => $idDistribuidor, 'Ventasdistribuidore.fecha' => $fecha)
    ));

    if (empty($hora)) {
      $hora = $this->Fechasconvert->getHora($hora[0]['Recarga']['created']);
    } else
      $hora = $this->Fechasconvert->getHora($hora['Ventasdistribuidore']['created']);

    $deposito = $this->Deposito->find('first', array(
      'conditions' => array('Deposito.created' => $fecha, 'Deposito.persona_id' => $idDistribuidor)
    ));

    $this->set(compact('nombre', 'hora', 'fecha', 'rows', 'precios', 'idDistribuidor', 'recargas', 'deposito'));
  }

  public function detalletienda() {
    if (!empty($this->request->data)) {
      $sucursal = $this->request->data['Tienda']['sucursal_id'];
      $fecha = $this->request->data['Tienda']['fecha'];
      $this->redirect(array('controller' => 'Tiendas', 'action' => 'reporteventastienda', $sucursal, $fecha));
    }
    $sucursales = $this->Sucursal->find('list', array('fields' => 'Sucursal.nombre'));
    $this->set(compact('sucursales'));
  }

  public function xmayortienda() {
    if (!empty($this->request->data)) {
      $sucursal = $this->request->data['Tienda']['sucursal_id'];
      $fecha = $this->request->data['Tienda']['fecha'];
      $this->redirect(array('controller' => 'Tiendas', 'action' => 'reporte149', $sucursal, $fecha));
    }
    $sucursales = $this->Sucursal->find('list', array('fields' => 'Sucursal.nombre'));
    $this->set(compact('sucursales'));
  }

  public function reporte_chips() {
    $datos = array();
    if (!empty($this->request->data['Dato'])) {
      //debug($this->request->data['Dato']);exit;
      $fecha_ini = $this->request->data['Dato']['fecha_ini'];
      $fecha_fin = $this->request->data['Dato']['fecha_fin'];
      $sql1 = "(SELECT users.persona_id FROM users WHERE (users.id = Chip.distribuidor_id))";
      $sql11 = "(SELECT CONCAT(personas.nombre,' ',personas.ap_paterno,' ',personas.ap_materno) FROM personas WHERE (personas.id = ($sql1)))";
      $sql2 = "(SELECT COUNT(*) FROM chips c,activados a WHERE c.telefono = a.phone_number)";
      $this->Chip->virtualFields = array(
        'distribuidor' => "CONCAT($sql11)",
        'activados' => "CONCAT($sql2)"
      );
      $datos = $this->Chip->find('all', array(
        'recursive' => 0,
        'conditions' => array('Chip.fecha_entrega_d >=' => $fecha_ini, 'Chip.fecha_entrega_d <=' => $fecha_fin),
        'group' => array('Chip.distribuidor_id'),
        'fields' => array('Chip.distribuidor', 'Chip.activados', 'COUNT(*) entregados')
      ));

      /* debug($datos);
        exit; */
    }
    $this->set(compact('datos'));
  }

  public function reporte_chips_clientes() {
    $datos = array();
    if (!empty($this->request->data['Dato'])) {
      $fecha_ini = $this->request->data['Dato']['fecha_ini'];
      $fecha_fin = $this->request->data['Dato']['fecha_fin'];
      $sql1 = "(SELECT users.persona_id FROM users WHERE (users.id = Chip.distribuidor_id))";
      $sql11 = "(SELECT CONCAT(personas.nombre,' ',personas.ap_paterno,' ',personas.ap_materno) FROM personas WHERE (personas.id = ($sql1)))";
      $sql2 = "(SELECT nombre FROM lugares WHERE lugares.id = (SELECT users.lugare_id FROM users WHERE (users.id = Chip.distribuidor_id)))";
      $this->Chip->virtualFields = array(
        'distribuidor' => "CONCAT($sql11)",
        'lugar_dis' => "CONCAT($sql2)"
      );
      $datos = $this->Chip->find('all', array(
        'conditions' => array('Chip.fecha >=' => $fecha_ini,'Chip.fecha <=' => $fecha_fin,'Chip.cliente_id !=' => NULL)
      ));
    }
    $this->set(compact('datos'));
  }

}
?>

