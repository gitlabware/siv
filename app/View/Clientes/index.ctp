<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Clientes</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                      
                    
                    <th>numero de registro</th>
                    <th >nombre</th>
                    <th >direccion</th>  
                    <th >celular</th>
                    <th >zona</th>
                    <th style="width: 10;">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                <?php foreach ($clientes as $p): ?>
                    <tr>                      
                        <td><?php echo $p['Cliente']['num_registro']; ?></td>
                        <td><?php echo $p['Cliente']['nombre']; ?></td>
                        <td><?php echo $p['Cliente']['direccion']; ?></td>
                        <td><?php echo $p['Cliente']['celular']; ?></td>
                        <td><?php echo $p['Cliente']['zona']; ?></td>
                        <td class="low-padding align-center"><?php echo $this->Html->link($this->Html->image("iconos/editar.png", array("alt" => 'Editar', 'title' => 'editar')), array('action' => 'edit', $p['Cliente']['id']), array('escape' => false));?>
                        <?php echo $this->Html->link($this->Html->image("iconos/eliminar.png", array("alt" => 'eliminar', 'title' => 'eliminar')), array('action' => 'delete', $p['Cliente']['id']), array('escape' => false), ("Desea eliminar realmente??"));
                            ?>
                        </td>
                    </tr>               
                <?php endforeach; ?>
            </tbody>
        </table>   
        <td class="low-padding align-center"><a href="<?php echo $this->html->url(array('action'=>'insertar')); ?>" class="button compact icon-gear">Nuevo Cliente</a></td>    
    </div>
</section>	

<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
