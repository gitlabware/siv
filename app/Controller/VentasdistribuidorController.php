<?php

class VentasdistribuidorController extends AppController {

    public $helpers = array(
        'Html',
        'Form',
        'Js');
    
    public $uses = array(
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
        'Cliente',
        'Tiposobservacione',
        'Deposito',
        'Listacliente', 'User');
    public $layout = 'vivadistribuidor';
    public $components = array('RequestHandler', 'Session');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
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
        if (!empty($this->request->data)){
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
    public function registrafecha()
    {
        if (!empty($this->request->data)){
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
    public function formulario2($id_cli = null,$fecha = null)
    {
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
                       'conditions'=>array(
                           'Movimiento.persona_id'=>$usuario, 
                           'Movimiento.producto_id'=>$productoid),
                       'order'=>array('Movimiento.id DESC'),
                       'recursive'=>-1
                   ));
                   
                    if (empty($movs)) {
                        $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror');
                        $this->redirect(array('action' => 'formulario', $clienteId), null, true);
                    } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
                        $total_ante = $movs['Movimiento']['total'];
                        /***verificar esta parte 15 abril 2013***/
                        $fechamov = $movs['Movimiento']['created'];
                        if($fechamov == $fecha){
                            $saldo = $movs['Movimiento']['saldo'];
                            $total = $movs['Movimiento']['total'] - $cantidad;
                            $ingreso =$movs['Movimiento']['ingreso'];
                            $venta = $movs['Movimiento']['salida'] + $cantidad;
                        }else{
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
                            }else{
                                $idventa = $this->Ventasdistribuidore->getLastInsertID();
                            }
                        }else{
                            $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
                            $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
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
                            $this->redirect(array('action' => 'formulario', $this->request->data['0']['Ventasdistribuidore']['cliente_id']),null, true);
                        }

                        //**************************************************************
                    } elseif ($movs['Movimiento']['total'] == 0) {
                        //debug($movs);exit;
                        $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario', $clienteId), null, true);
                    }else{
                        $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario', $clienteId), null, true);
                    }

                    //**************************************************************
                } //fin del if decantidad != 0
                else{
                    $this->Ventasdistribuidore->create();
                    $this->Ventasdistribuidore->save($d);
                }
            } //end del recorrido de datos del thisdata
           
           
			$recargas= $this->request->data['Recarga'];
                
                if (!empty($recargas)) {
                    foreach ($recargas as $data) {
                        //debug($r);exit;

                        if(!empty($data['monto'])){
                            //debug($data);
                            $this->Recarga->create();
                            if (!($this->Recarga->save($data))) {
                                $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror');
                                $this->redirect(array('action' => 'index'), null, true);
                            }
                            else{
                                $this->requestAction(array('controller'=>'Recargas','action'=>'notifica'));
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
            $this->set(compact('precios', 'rows', 'usuario', 'usu', 'datoscli', 'recargas','fecha','id_cli','fecha'));
        }
    }
    function formulario($id_cli = null) {
        //$this->layout= 'ajax';
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
                       'conditions'=>array(
                           'Movimiento.persona_id'=>$usuario, 
                           'Movimiento.producto_id'=>$productoid),
                       'order'=>array('Movimiento.id DESC'),
                       'recursive'=>-1
                   ));
                   
                    if (empty($movs)) {
                        $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror');
                        $this->redirect(array('action' => 'formulario', $clienteId), null, true);
                    } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
                        $total_ante = $movs['Movimiento']['total'];
                        /***verificar esta parte 15 abril 2013***/
                        $fechamov = $movs['Movimiento']['created'];
                        if($fechamov == date('Y-m-d')){
                            $saldo = $movs['Movimiento']['saldo'];
                            $total = $movs['Movimiento']['total'] - $cantidad;
                            $ingreso =$movs['Movimiento']['ingreso'];
                            $venta = $movs['Movimiento']['salida'] + $cantidad;
                        }else{
                            $saldo = $movs['Movimiento']['total'];
                            $total = $movs['Movimiento']['total']  - $cantidad;
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
                                        ', consulte al administrador del sistema', 'msgerror');
                                $this->redirect(array('action' => 'formulario', $this->data['0']['Ventasdistribuidore']['cliente_id']), null, true);
                            }else{
                                $idventa = $this->Ventasdistribuidore->getLastInsertID();
                            }
                        }else{
                            $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
                            $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
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
                        
                        //debug($this->request->data['Movimiento']);exit;
                        if (!($this->Movimiento->save($this->request->data['Movimiento']))) {
                            $this->Session->setFlash('No se pudo guardar el movimiento del producto:' . $prodnomb .
                                    ', consulte al administrador del sistema', 'msgerror');
                            $this->redirect(array('action' => 'formulario', $this->request->data['0']['Ventasdistribuidore']['cliente_id']),null, true);
                        }

                        //**************************************************************
                    } elseif ($movs['Movimiento']['total'] == 0) {
                        //debug($movs);exit;
                        $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario', $clienteId), null, true);
                    }else{
                        $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario', $clienteId), null, true);
                    }

                    //**************************************************************
                } //fin del if decantidad != 0
                else{
                    $this->Ventasdistribuidore->create();
                    $this->Ventasdistribuidore->save($d);
                }
            } //end del recorrido de datos del thisdata
           
           
			$recargas= $this->request->data['Recarga'];
                
                if (!empty($recargas)) {
                    foreach ($recargas as $data) {
                        //debug($r);exit;
//                        $id = $r['Recarga']['id'];
//                        $this->Recarga->id = $id;
//                        $this->data = $this->Recarga->read();
//                        $this->request->data['Recarga']['estado'] = 1;
                        if(!empty($data['monto'])){
                            //debug($data);
                            $this->Recarga->create();
                            if (!($this->Recarga->save($data))) {
                                $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror');
                                $this->redirect(array('action' => 'index'), null, true);
                            }
                            else{
                                $this->requestAction(array('controller'=>'Recargas','action'=>'notifica'));
                            }
                        }
                        
                    }
                }
                
                $this->Session->setFlash('Venta registrada!!!!!', 'msgbueno');
                $this->redirect(array('action' => 'pidecodigo'), null, true);
			
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
    public function reporte1492(){
        $this->layout = "imprimetabla";
      $usuario_id = $this->Session->read('Auth.User.id');
        
        $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
        $persona =$this->Session->read('Auth.User.Persona.id');
        $hoy = date('Y-m-d');
        $dia = $hoy; 
        $precios = $this->Productosprecio->find('all', array(
                'conditions' => array(
                    'Productosprecio.tipousuario_id' => 3,
                    'Producto.proveedor like' => 'VIVA',
                    'Producto.estado' => '1'), 
                'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC','Productosprecio.precio DESC',
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
            'order'=>array('Ventasdistribuidore.cliente_id ASC','Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
        //debug($ventas);
        $clientes = $this->Ventasdistribuidore->find('all', array(
            'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
            'group'=>array('Ventasdistribuidore.cliente_id'),
            'order'=>array('Ventasdistribuidore.cliente_id ASC')
        ));
      //debug($clientes);exit;
        $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
        //debug($sql);exit;
        
        $recargas = $this->Recarga->find('all', array(
            'conditions'=>array(
                'Recarga.created'=>$hoy, 
                'Recarga.user_id'=>$usuario_id)));
        //debug($recargas);exit; 
        //if (empty($ventas)) {
        /*$ruta = $this->Ruteo->find('first', array('conditions' => array('Ruteo.dia' => $dia, 'Ruteo.distribuidor_id' => $usuario_id),
            'fields' => array('Ruteo.id')
        ));*/
        //debug($ruta);exit;
        /*$clientes = $this->Listacliente->find('all', array('conditions' => array('Listacliente.ruteo_id' =>
                $ruta['Ruteo']['id'], 'Listacliente.distribuidor_id' => $usuario_id),
            'group' => array('Listacliente.cliente_id')
        ));*/
        //debug($clientes);exit;
       // $ides = array();
        //$i = 0;
        /*foreach ($clientes as $id) {
            $ides[$i] = $id['Listacliente']['cliente_id'];
            $i++;
        }*/
        //debug($ides);exit;
        /*$obs = $this->Detalleobservacione->find('all', array('conditions' => array('Detalleobservacione.fecha_registro' =>
                $hoy, 'Detalleobservacione.cliente_id' => $ides)));*/

        //debug($obs);exit;
        //}
        $deposito = $this->Deposito->find('first', array(
            'conditions'=>array('Deposito.created'=>$hoy, 'Deposito.persona_id'=>$persona)
            ));
        $this->set(compact('precios', 'rows','clientes','recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
    }
    public function reporte1492nuevo($hoy = null){
        //debug($hoy);exit;
        $this->layout = "imprimetabla";
      $usuario_id = $this->Session->read('Auth.User.id');
        
        $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
        $persona =$this->Session->read('Auth.User.Persona.id');
        //$hoy = date('Y-m-d');
        $dia = $hoy; 
        $precios = $this->Productosprecio->find('all', array(
                'conditions' => array(
                    'Productosprecio.tipousuario_id' => 3,
                    'Producto.proveedor like' => 'VIVA',
                    'Producto.estado' => '1'), 
                'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC','Productosprecio.precio DESC',
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
            'order'=>array('Ventasdistribuidore.cliente_id ASC','Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
        //debug($ventas);
        $clientes = $this->Ventasdistribuidore->find('all', array(
            'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
            'group'=>array('Ventasdistribuidore.cliente_id'),
            'order'=>array('Ventasdistribuidore.cliente_id ASC')
        ));
      //debug($clientes);exit;
        $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
        //debug($sql);exit;
        
        $recargas = $this->Recarga->find('all', array(
            'conditions'=>array(
                'Recarga.created'=>$hoy, 
                'Recarga.user_id'=>$usuario_id)));
        $deposito = $this->Deposito->find('first', array(
            'conditions'=>array('Deposito.created'=>$hoy, 'Deposito.persona_id'=>$persona)
            ));
        $this->set(compact('precios', 'rows','clientes','recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
    }
    public function reporte1492fecha()
    {
        //debug($this->request->data['Ventasdistribuidor']['fecha']);exit;
        if ($this->request->data['Ventasdistribuidor']['fecha'] != null)
        {
            $this->redirect(array('controller'=>'Ventasdistribuidor','action' => 'reporte1492nuevo',$this->request->data['Ventasdistribuidor']['fecha']), null, true);
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
            'order'=>array('Ventasdistribuidore.cliente_id ASC','Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
       // debug($ventas);
        $clientes = $this->Ventasdistribuidore->find('all', array(
            'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
            'group'=>array('Ventasdistribuidore.cliente_id'),
            'order'=>array('Ventasdistribuidore.cliente_id ASC')
        ));
       // debug($clientes);exit;
        $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
        //debug($sql);exit;
        
        $recargas = $this->Recarga->find('all', array('conditions'=>array('Recarga.created'=>$hoy, 'Recarga.user_id'=>$usuario_id)));
        //debug($recargas);exit; 
        //if (empty($ventas)) {
        /*$ruta = $this->Ruteo->find('first', array('conditions' => array('Ruteo.dia' => $dia, 'Ruteo.distribuidor_id' => $usuario_id),
            'fields' => array('Ruteo.id')
        ));*/
        //debug($ruta);exit;
        /*$clientes = $this->Listacliente->find('all', array('conditions' => array('Listacliente.ruteo_id' =>
                $ruta['Ruteo']['id'], 'Listacliente.distribuidor_id' => $usuario_id),
            'group' => array('Listacliente.cliente_id')
        ));*/
        //debug($clientes);exit;
       // $ides = array();
        //$i = 0;
        /*foreach ($clientes as $id) {
            $ides[$i] = $id['Listacliente']['cliente_id'];
            $i++;
        }*/
        //debug($ides);exit;
        /*$obs = $this->Detalleobservacione->find('all', array('conditions' => array('Detalleobservacione.fecha_registro' =>
                $hoy, 'Detalleobservacione.cliente_id' => $ides)));*/

        //debug($obs);exit;
        //}
        $this->set(compact('recargas', 'obs', 'ventas', 'hoy', 'distribuidor'));
    }
    public function deposito(){
        debug($this->request->data);exit;
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
        if (!empty($this->request->data)){
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
                        ', consulte al administrador del sistema','msgerror_mobil');
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
                       'conditions'=>array(
                           'Movimiento.persona_id'=>$usuario, 
                           'Movimiento.producto_id'=>$productoid),
                       'order'=>array('Movimiento.id DESC'),
                       'recursive'=>-1
                   ));
                   
                    if (empty($movs)) {
                        $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror_mobil');
                        $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
                    } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
                        $total_ante = $movs['Movimiento']['total'];
                        /***verificar esta parte 15 abril 2013***/
                        $fechamov = $movs['Movimiento']['created'];
                        if($fechamov == date('Y-m-d')){
                            $saldo = $movs['Movimiento']['saldo'];
                            $total = $movs['Movimiento']['total'] - $cantidad;
                            $ingreso =$movs['Movimiento']['ingreso'];
                            $venta = $movs['Movimiento']['salida'] + $cantidad;
                        }else{
                            $saldo = $movs['Movimiento']['total'];
                            $total = $movs['Movimiento']['total']  - $cantidad;
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
                            }else{
                                $idventa = $this->Ventasdistribuidore->getLastInsertID();
                            }
                        }else{
                            $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
                            $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
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
                            $this->redirect(array('action' => 'formulario_mobile', $this->request->data['0']['Ventasdistribuidore']['cliente_id']),null, true);
                        }

                        //**************************************************************
                    } elseif ($movs['Movimiento']['total'] == 0) {
                        //debug($movs);exit;
                        $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
                    }else{
                        $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario_mobile', $clienteId), null, true);
                    }

                    //**************************************************************
                } //fin del if decantidad != 0
                else{
                    $this->Ventasdistribuidore->create();
                    $this->Ventasdistribuidore->save($d);
                }
            } //end del recorrido de datos del thisdata
           
           
			$recargas= $this->request->data['Recarga'];
                
                if (!empty($recargas)) {
                    foreach ($recargas as $data) {
                        //debug($r);exit;
//                        $id = $r['Recarga']['id'];
//                        $this->Recarga->id = $id;
//                        $this->data = $this->Recarga->read();
//                        $this->request->data['Recarga']['estado'] = 1;
                        if(!empty($data['monto'])){
                            //debug($data);
                            $this->Recarga->create();
                            if (!($this->Recarga->save($data))) {
                                $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror_mobil');
                                $this->redirect(array('action' => 'index'), null, true);
                            }
                            else{
                                $this->requestAction(array('controller'=>'Recargas','action'=>'notifica'));
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
    public function registrafecha_mobile()
    {
        $this->layout = 'vivadistribuidor_mobile';
        if (!empty($this->request->data)){
            $codigo = $this->request->data['Cliente']['codigo'];
            $fecha = $this->request->data['Cliente']['fecha'];
            $cliente = $this->Cliente->find('first', array(
                'conditions' => array(
                    'Cliente.num_registro' => $codigo
            )));
            if (!empty($cliente)) {
                $idCliente = $cliente['Cliente']['id'];
                $fecha = $this->request->data['Cliente']['fecha'];
                list($mes, $día, $año) = explode('/', $fecha);
                /*debug($mes);
                debug($día);
                debug($año);exit;*/
                //debug($this->request->data);exit;
                $this->redirect(array('action' => 'formulario2_mobile', $idCliente, $año.'-'.$mes.'-'.$día));
            } else {
                $this->Session->setFlash('No existe el cliente con codigo de registro: ' . $codigo .
                        ', consulte al administrador del sistema','msgerror_mobil');
                $this->redirect(array('action' => 'pidecodigo_mobile'), null, true);
            }
        }
    }
    public function login()
    {
        $this->layout = 'login_mobile';
        if ($this->request->is('post'))
        {
            if ($this->Auth->login())
            {
                
                $this->redirect(array('action' => 'pidecodigo_mobile'));
            }
            else{
                $this->Session->setFlash('Usuario o password incorrectos', 'msgerror_mobil');
            }
        }
    }
    public function formulario2_mobile($id_cli = null,$fecha = null)
    {
        //debug($fecha);exit;
        $this->layout= 'vivadistribuidor_mobile';
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
                       'conditions'=>array(
                           'Movimiento.persona_id'=>$usuario, 
                           'Movimiento.producto_id'=>$productoid),
                       'order'=>array('Movimiento.id DESC'),
                       'recursive'=>-1
                   ));
                   
                    if (empty($movs)) {
                        $this->Session->setFlash('No cuenta con : ' . $prodnomb, 'msgerror_mobil');
                        $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
                    } elseif ($movs['Movimiento']['total'] != 0 && $movs['Movimiento']['total'] >= $cantidad) {
                        $total_ante = $movs['Movimiento']['total'];
                        /***verificar esta parte 15 abril 2013***/
                        $fechamov = $movs['Movimiento']['created'];
                        if($fechamov == $fecha){
                            $saldo = $movs['Movimiento']['saldo'];
                            $total = $movs['Movimiento']['total'] - $cantidad;
                            $ingreso =$movs['Movimiento']['ingreso'];
                            $venta = $movs['Movimiento']['salida'] + $cantidad;
                        }else{
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
                            }else{
                                $idventa = $this->Ventasdistribuidore->getLastInsertID();
                            }
                        }else{
                            $this->Session->setFlash('no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
                            $this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
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
                            $this->redirect(array('action' => 'formulario2_mobile', $this->request->data['0']['Ventasdistribuidore']['cliente_id']),null, true);
                        }

                        //**************************************************************
                    } elseif ($movs['Movimiento']['total'] == 0) {
                        //debug($movs);exit;
                        $this->Session->setFlash('Ya no le sobran o no le alcanzan: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
                    }else{
                        $this->Session->setFlash('Ud. no cuenta con: ' . $prodnomb .
                                '. Ingrese los datos de venta nuevamente!!', 'msgerror_mobil');
                        //$this->Movimiento->deleteAll(array('Movimiento.ventasdistribuidore_id'=>$idventa));
                        $this->redirect(array('action' => 'formulario2_mobile', $clienteId), null, true);
                    }

                    //**************************************************************
                } //fin del if decantidad != 0
                else{
                    $this->Ventasdistribuidore->create();
                    $this->Ventasdistribuidore->save($d);
                }
            } //end del recorrido de datos del thisdata
           
           
			$recargas= $this->request->data['Recarga'];
                
                if (!empty($recargas)) {
                    foreach ($recargas as $data) {
                        //debug($r);exit;

                        if(!empty($data['monto'])){
                            //debug($data);
                            $this->Recarga->create();
                            if (!($this->Recarga->save($data))) {
                                $this->Session->setFlash('No se pudo registrar una de las recargas el registro del pago de recargas', 'msgerror_mobil');
                                $this->redirect(array('action' => 'registrafecha_mobile'), null, true);
                            }
                            else{
                                $this->requestAction(array('controller'=>'Recargas','action'=>'notifica'));
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
            $this->set(compact('precios', 'rows', 'usuario', 'usu', 'datoscli', 'recargas','fecha','id_cli','fecha'));
        }
    }
    public function salir() {
        //$this->Session->setFlash('Good-Bye');
        $this->Auth->logout();
        $this->redirect(array('controller' => 'Ventasdistribuidor','action' => 'login'));
    }
    public function reporte1492_mobile(){
        $this->layout = "vivadistribuidor_mobile";
      $usuario_id = $this->Session->read('Auth.User.id');
        
        $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
        $persona =$this->Session->read('Auth.User.Persona.id');
        $hoy = date('Y-m-d');
        $dia = $hoy; 
        $precios = $this->Productosprecio->find('all', array(
                'conditions' => array(
                    'Productosprecio.tipousuario_id' => 3,
                    'Producto.proveedor like' => 'VIVA',
                    'Producto.estado' => '1'), 
                'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC','Productosprecio.precio DESC',
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
            'order'=>array('Ventasdistribuidore.cliente_id ASC','Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
        //debug($ventas);
        $clientes = $this->Ventasdistribuidore->find('all', array(
            'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
            'group'=>array('Ventasdistribuidore.cliente_id'),
            'order'=>array('Ventasdistribuidore.cliente_id ASC')
        ));
      //debug($clientes);exit;
        $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
        //debug($sql);exit;
        
        $recargas = $this->Recarga->find('all', array(
            'conditions'=>array(
                'Recarga.created'=>$hoy, 
                'Recarga.user_id'=>$usuario_id)));
        
        $deposito = $this->Deposito->find('first', array(
            'conditions'=>array('Deposito.created'=>$hoy, 'Deposito.persona_id'=>$persona)
            ));
        $this->set(compact('precios', 'rows','clientes','recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
    }
    public function reporte1492fecha_mobile()
    {
        $this->layout = 'vivadistribuidor_mobile';
        //debug($this->request->data['Ventasdistribuidor']['fecha']);exit;
        $fecha = $this->request->data['Ventasdistribuidor']['fecha'];
        list($mes, $día, $año) = explode('/', $fecha);
        if ($this->request->data['Ventasdistribuidor']['fecha'] != null)
        {
            $this->redirect(array('controller'=>'Ventasdistribuidor','action' => 'reporte1492nuevo_mobile',$año.'-'.$mes.'-'.$día), null, true);
        }
        
        
    }
    public function reporte1492nuevo_mobile($hoy = null){
        //debug($hoy);exit;
        $this->layout = "vivadistribuidor_mobile";
      $usuario_id = $this->Session->read('Auth.User.id');
        
        $distribuidor = $this->Session->read('Auth.User.Persona.nombre');
        $persona =$this->Session->read('Auth.User.Persona.id');
        //$hoy = date('Y-m-d');
        $dia = $hoy; 
        $precios = $this->Productosprecio->find('all', array(
                'conditions' => array(
                    'Productosprecio.tipousuario_id' => 3,
                    'Producto.proveedor like' => 'VIVA',
                    'Producto.estado' => '1'), 
                'order' => array('Producto.id ASC', 'Producto.tipo_producto DESC','Productosprecio.precio DESC',
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
            'order'=>array('Ventasdistribuidore.cliente_id ASC','Ventasdistribuidore.producto_id ASC', 'Ventasdistribuidore.precio DESC')));
        //debug($ventas);
        $clientes = $this->Ventasdistribuidore->find('all', array(
            'conditions' => array('Ventasdistribuidore.fecha' => $hoy, 'Ventasdistribuidore.user_id' => $usuario_id),
            'group'=>array('Ventasdistribuidore.cliente_id'),
            'order'=>array('Ventasdistribuidore.cliente_id ASC')
        ));
      //debug($clientes);exit;
        $sql = "SELECT * FROM recargas WHERE recargas.created like '$hoy' AND recargas.user_id = '$usuario_id'";
        //debug($sql);exit;
        
        $recargas = $this->Recarga->find('all', array(
            'conditions'=>array(
                'Recarga.created'=>$hoy, 
                'Recarga.user_id'=>$usuario_id)));
        $deposito = $this->Deposito->find('first', array(
            'conditions'=>array('Deposito.created'=>$hoy, 'Deposito.persona_id'=>$persona)
            ));
        $this->set(compact('precios', 'rows','clientes','recargas', 'obs', 'ventas', 'hoy', 'distribuidor', 'deposito'));
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
                $this->Session->setFlash('Su Password fue modificado Exitosamente','msgbueno_mobil');
                $this->redirect(array('action' => 'pidecodigo_mobile'), null, true);
            } else {
                $this->Session->setFlash('no se pudo modificar!!','msgerror_mobil');
            }
        }
    }
}

?>