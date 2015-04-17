<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Almacenes</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                      
                    <th scope="col">id</th>
                    <th scope="col" >Nombre almacen</th>
                    <th scope="col" >Sucursal</th>                    
                    <th scope="col" >Descripcion</th>
                    
                    <th scope="col" >Actions</th>
                </tr>
            </thead>          

            <tbody>
                <?php foreach ($almacenes as $p): ?>
                    <tr>                      
                        <td><?php echo $p['Almacene']['id']; ?></td>
                        <td><?php echo $p['Almacene']['nombre']; ?></td>
                        <td><?php echo $p['Sucursal']['nombre']; ?></td>
                        <td><?php echo $p['Almacene']['descripcion']; ?></td> 
                        
                        <td>
                        <?php echo $this->html->link('editar', array('action'=>'editar',$p['Almacene']['id'])); ?> 
                        &nbsp;
                        <?php //echo $this->html->link('usuarios', array('action'=>'usuarios',$p['Sucursal']['id'])); ?>
                        <?php echo $this->html->link('Eliminar', array('action'=>'eliminar',$p['Almacene']['id'])); ?>
                        </td>  
                    </tr>               
                <?php endforeach; ?>
            </tbody>
        </table> 
        <td><?php echo $this->html->link('insertar', array('action'=>'add'),array('class'=>'button green-gradient')); ?> </td>
    </div>
</section>	

<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 