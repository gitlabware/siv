<section id="menu" role="complementary">
    <!-- This wrapper is used by several responsive layouts -->
    <div id="menu-content">
        <header>
            Distribuidor
        </header>

        <div id="profile">
            <img src="<?php echo $this->webroot; ?>img/user.png" width="64" height="64" alt="User name" class="user-icon">
            Bienvenido
            <span class="name"><?php echo $this->Session->read('Auth.User.Persona.nombre');?>
             <b><?php echo $this->Session->read('Auth.User.Persona.ap_paterno');?></b>
             </span>
        </div>

        <!-- By default, this section is made for 4 icons, see the doc to learn how to change this, in "basic markup explained" -->
        <ul id="access" class="children-tooltip">
            <li><a href="<?php echo $this->Html->url(array('controller'=>'ventasdistribuidor', 'action'=>'pidecodigo'))?>" title="INICIO"><span class="icon-gear"></span></span></a></li>
            <li><a href="<?php echo $this->Html->url(array('controller'=>'users', 'action'=>'salir'))?>" title="Cerrar Session"><span class="icon-user"></span></a></li>
        </ul>

        <section class="navigable">            
            <ul class="big-menu">
                <?php  $id = $this->Session->read("Auth.User.id")?>
                <li><a href="<?php echo $this->Html->url(array('action'=>'cambiopass', $id)); ?>">Cambiar Password</a></li>
                <li><a href="<?php echo $this->Html->url(array('action'=>'registrafecha')); ?>">Registrar por Fecha</a></li>
                <li class="with-right-arrow">
                    <span>Reportes</span>
                    <ul class="big-menu">
                    <?php 
                    $idusuario = $this->Session->read("Auth.User.id");
                    $fecha = date('Y-m-d');
                    ?>
                        <li><a href="<?php echo $this->Html->url(array('controller'=>'Reportes', 'action'=>'saldoshorizontal', $idusuario, $fecha)); ?>">Reporte General</a></li>
                        <li><a href="<?php echo $this->Html->url(array('controller'=>'Ventasdistribuidor', 'action'=>'reporte1492')); ?>">Reporte 149</a></li>                                                                        
                        <li><a href="<?php echo $this->Html->url(array('controller'=>'Ventasdistribuidor', 'action'=>'reporte1492fecha')); ?>">Reporte por Fecha</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $this->Html->url(array('action'=>'clientes')); ?>">Clientes</a></li>
            </ul>
        </section>
    </div>

</section>