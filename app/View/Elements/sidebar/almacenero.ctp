
<section id="menu" role="complementary">
    <!-- This wrapper is used by several responsive layouts -->
    <div id="menu-content">
        <header>
            Almacenes
        </header>

        <div id="profile">
            <img src="<?php echo $this->webroot; ?>img/user.png" width="64" height="64" alt="User name" class="user-icon">
            Bienvenido
            <span class="name"><?php echo $this->Session->read('Auth.User.Persona.nombre'); ?>
                <b><?php echo $this->Session->read('Auth.User.Persona.ap_paterno'); ?></b>
            </span>
        </div>

        <!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->


       <ul id="access" class="children-tooltip">
            <li><a href="<?php echo $this->Html->url(array('controller'=>'almacenes', 'action'=>'listadistribuidores'))?>" title="INICIO"><span class="icon-gear"></span></span></a></li>
            <li><a href="<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'salir'))?>" title="Cerrar Session"><span class="icon-user"></span></a></li>

            <li><a href="<?php echo $this->Html->url(array('controller'=>'Productos', 'action'=>'index'))?>" title="PRODUCTOS"><span class="icon-inbox"></span></a></li>
            
       </ul>
        <section class="navigable">            
            <ul class="big-menu"> 
                <li class="with-right-arrow">
                    <span>Entregas</span>
                    <ul class="big-menu">

                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'listadistribuidores')); ?>">Repartir personal</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'listaalmacenes')) ?>">Repartir Almacenes</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Chips', 'action' => 'verexcels')); ?>">Listado excels</a></li>	
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Chips', 'action' => 'subirexcel')); ?>">Subir excel chips</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'filtra')); ?>">Reporte entrega</a></li>

                    </ul>
                </li>
                <li class="with-right-arrow">
                    <span>Recargas</span>
                    <ul class="big-menu">
                        <li>
                            <a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'registrarecarga')); ?>">Registrar recarga</a></li>
                        <li>
                            <a href="<?php echo $this->Html->url(array('controller' => 'Recargas', 'action' => 'estadorecargas2')); ?>">Reporte Recargas</a>

                        </li>                        
                    </ul>
                </li>
                <li class="with-right-arrow">
                    <span>Depositos</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'deposito')); ?>">Registrar Deposito</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'listadepositos')) ?>">Listado depositos</a></li>	
                    </ul>
                </li>

                <li class="with-right-arrow">
                    <span>Productos</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productos', 'action' => 'index')); ?>">Listado de Productos</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productos', 'action' => 'insertar')); ?>">Nuevo Producto</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productosprecios', 'action' => 'index')); ?>">Listado de Precio</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productosprecios', 'action' => 'nuevoprecio')); ?>">Nuevos Precios</a></li>
                    </ul>
                </li>  
                <li class="with-right-arrow">
                    <span>Tiendas</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Sucursals', 'action' => 'index')); ?>">Listado de tiendas</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Sucursals', 'action' => 'insertar')); ?>">Nueva tienda</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Clientes', 'action' => 'index')); ?>">Clientes</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Sucursals', 'action' => 'addrecargacabina')); ?>">Nuevo Producto Cabina</a></li>
                    </ul>
                </li> 

                <li class="with-right-arrow">
                    <span>Reportes</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Reportes', 'action' => 'form')); ?>">Reporte ventas</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'filtra')); ?>">Reporte entregas</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productos', 'action' => 'index')); ?>">Listado de Productos</a></li>                                                                        
                        <li><a href="<?php echo $this->Html->url(array('controller'=>'Reportes', 'action'=>'form2')); ?>">Reporte ventas distribuidor x fechas</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller'=>'Reportes', 'action'=>'detalletienda')); ?>">Reporte detalle tienda</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller'=>'Reportes', 'action'=>'xmayortienda')); ?>">Reporte x Mayor tienda</a></li>
                    </ul>
                </li>
                <li class="with-right-arrow">
                    <span>Almacenes</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'add')); ?>">Insertar Almacen</a></li>
                       
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'index')); ?>">Listado de Almacenes</a></li>                                                                        
                        
                    </ul>
                </li>


            </ul>
        </section>



    </div>

</section>

