<section id="menu" role="complementary">
    <!-- This wrapper is used by several responsive layouts -->
    <div id="menu-content">
        <header>
            Administrador 2
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
            <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'index')) ?>" title="INICIO"><span class="icon-gear"></span></span></a></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'salir')) ?>" title="Cerrar Session"><span class="icon-user"></span></a></li>
            <li><a href="<?php echo $this->Html->url(array('controller' => 'productos', 'action' => 'index')) ?>" title="PRODUCTOS"><span class="icon-inbox"></span></a></li>
        </ul>

        <section class="navigable">            
            <ul class="big-menu">
                <li class="with-right-arrow">
                    <span>Usuarios</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'index')); ?>">Listado de Usuarios</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'add')); ?>">Insertar Nuevo Usuario</a></li>    
                    </ul>
                </li>    
                <li><a href="<?php echo $this->Html->url(array('controller' => 'almacenes', 'action' => 'listadistribuidores')); ?>">Repartir personal</a></li>
                <li><a href="<?php echo $this->Html->url(array('controller' => 'almacenes', 'action' => 'listaalmacenes')) ?>">Repartir almacenes</a></li>	
                <li class="with-right-arrow">
                    <span>Productos</span>
                    <ul class="big-menu">
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productos', 'action' => 'insertar')); ?>">Nuevo Producto</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Productos', 'action' => 'index')); ?>">Listado de Productos</a></li>                                                                        

                    </ul>
                </li>  
                <li class="with-right-arrow">
                    <span>Reportes</span>
                    <ul class="big-menu">

                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Reportes', 'action' => 'form')); ?>">Reporte ventas diario distribuidor</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Reportes', 'action' => 'form2')); ?>">Reporte ventas distribuidor x fechas</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller' => 'Almacenes', 'action' => 'filtra')); ?>">Reporte entregas</a></li>

                </li> 


            </ul>
        </section>



    </div>

</section>