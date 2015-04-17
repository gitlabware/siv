<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Productos</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                      
                    <th scope="col" width="5%" class="align-center hide-on-mobile">Nro.</th>
                    <th scope="col" width="10%" class="align-center hide-on-mobile">Nombre</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Precio compra</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">proveedor</th>  
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">Fecha Ingreso</th>
                    <th scope="col" width="20%" class="align-center hide-on-mobile-portrait">Observaciones</th>    
                    <th scope="col" width="20" class="align-center">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                <?php $i=1; foreach ($productos as $p): ?>
                    <tr>
                        <td><?php echo $i; $i++; ?></td>                      
                        <td><?php echo $p['Producto']['nombre']; ?></td>
                        <td><?php echo $p['Producto']['precio_compra']; ?></td>
                        <td><?php echo $p['Producto']['proveedor']; ?></td>
                        <td><?php echo $p['Producto']['fecha_ingreso']; ?></td>
                        <td><?php echo $p['Producto']['observaciones']; ?></td>             
                        <td>
                        <?php //echo $this->html->url(array('action'=>'editar',$p['Producto']['id'])); ?>
                        <?php //echo $this->Html->url(array( 'controller'=>'Productosprecios','action'=>'precios',$p['Producto']['id'])); ?>
                        <?php //echo $this->Html->url(array('action'=>'delete',$p['Producto']['id'])); ?>
                        
                        <?php echo $this->Html->link($this->Html->image("iconos/editar.png", array("alt" =>'Editar', 'title' => 'editar')), array('action' => 'editar', $p['Producto']['id']),array('escape' => false)); ?>
                        <?php echo $this->Html->link($this->Html->image("iconos/otro.png", array("alt" =>'Editar', 'title' => 'Productos Precios')), array('controller'=>'Productosprecios','action'=>'precios',$p['Producto']['id']),array('escape' => false)); ?>
                        <?php echo $this->Html->link($this->Html->image("iconos/eliminar.png", array("alt" =>'eliminar', 'title' => 'eliminar')), array('action'=>'delete',$p['Producto']['id']),array('escape' => false), 
                        ("Desea eliminar realmente??")); ?>
                        </td>
                    </tr>               
                <?php endforeach; ?>
            </tbody>
        </table>   
        <td class="low-padding align-center">
<?php echo $this->Html->link('INSERTAR NUEVO PRODUCTO',array('action'=>'insertar'),array('class'=>'button blue-gradient'));?>
</td>    
    </div>
</section>	

<script>
$(document).ready(function(){$("#formID").validationEngine();});
</script>
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
