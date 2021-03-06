<?php

class ProductospreciosController extends AppController
{
    public $name = 'Productosprecios';
    public $layout = 'viva';
    public $uses = array(
        'Productosprecio',
        'Precio',
        'Escala',
        'Tipousuario',
        'Producto',
        'Ventastienda'
        );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow();
    }

    //public

    public function index()
    {

        $productosprecios = $this->Productosprecio->find('all');
        $this->set(compact('productosprecios'));
        //$this->paginate = array('Productosprecio' => array('limit' => 10));
        
        //$this->set('productosprecios', $this->paginate('Productospreio'));
        //debug($productosprecios);
    }
    public function precios($id = null)
    {
        $productosprecios = $this->Productosprecio->find('all', array('conditions' => array('Productosprecio.producto_id' => $id)));
        $this->set(compact('productosprecios'));
    }
    function delete($id = Null)
    {
        if (!$id) {
            $this->Session->setFlash('Codigo invalido');
            $this->redirect(array('action' => 'index'), null, true);
        }
        if ($this->Productosprecio->delete($id)) {
            $this->Session->setFlash('El precio  ' . $id . ' fue borrado');
            $this->redirect(array('action' => 'index'), null, true);
        }
    }
    function editar($id = null, $ido = null)
    {
        $this->Productosprecio->id = $id;
        if (!$id)
        {
            $this->Session->setFlash('No existe el producto');
            $this->redirect(array('action' => 'precios', $id), null, true);
        }

        if (empty($this->data))
        {
            $this->data = $this->Productosprecio->read(); //find(array('id' => $id));
        }
        else
        {
            if ($this->Productosprecio->save($this->data))
            {
                $this->Session->setFlash('Los datos fueron modificados', 'mensajebueno');
                $this->redirect(array('action' => 'precios', $ido), null, true);
            }
            else
            {
                $this->Session->setFlash('no se pudo modificar!!');
            }
        }
        //$precios = $this->Productosprecio->find('all', array ('recursive'=>-1));
        //$this->set(compact('precios'));
        $tipos = $this->Tipousuario->find('list',array('fields'=>'Tipousuario.nombre'));
        $this->set(compact('tipos'));
    }        
    
    public function nuevoprecio($id = null)
    {
        if (!empty($this->request->data))
        {
            $this->Productosprecio->create();

            $escala = $this->Escala->find('first', array('conditions' => array('Escala.id' => $this->request->data['Productosprecio']['escala_id'])));
            $this->request->data['Productosprecio']['escala'] = $escala['Escala']['nombre'];
            $idProducto = $this->request->data['Productosprecio']['producto_id'];
            if ($this->Productosprecio->save($this->request->data))
            {
                $this->Session->setFlash('Nuevo precio creado', 'msgbueno');
                $this->redirect(array('action' => 'precios', $idProducto), null, true);
            }
            else
            {
                $this->Session->setFlash('no se pudo crear el nuevo precio intentelo de nuevo!!', 'msgerror');
                $this->redirect(array('action' => 'precios', $idProducto), null, true);
            }
        }
        else
        {
            $escalas = $this->Escala->find('all', array('recursive' => -1));
            $usuarios = $this->Tipousuario->find('all', array('recursive' => -1));
            $productos = $this->Producto->find('list',array('fields'=>'Producto.nombre'));
            $this->set(compact('escalas', 'id', 'usuarios','productos'));
            
        }
    }
    
    public function tienda()
    {
        $productos = $this->Productosprecio->find('all', array(
            'recursive'=>0,
            'conditions'=>array(
                'Productosprecio.escala'=>'TIENDA',
                'Productosprecio.tipousuario_id'=>2
            ),
            //'group'=>'Producto.tipo_producto'
        ));   
        
        $categorias = $this->Producto->find('all', array(
            'recursive'=>-1,
            'group'=>'Producto.tipo_producto'
        ));
        //debug($productos);        
        $this->set(compact('productos', 'categorias'));
    }
    
    
}

?>
