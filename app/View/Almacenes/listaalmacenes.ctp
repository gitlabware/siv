<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Almacenes</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                     
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Almacen</th>  
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Sucursal</th>  
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Direccion</th>
                    <th>Telefono</th>                  
                    <th scope="col" width="60" class="align-center">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                <?php foreach ($distribuidores as $distribuidor): ?>
                    <tr>    
                        <td><?php echo $distribuidor['Almacene']['nombre']; ?></td>
                        <td><?php echo $distribuidor['Sucursal']['nombre']; ?></td>               
                        <td><?php echo $distribuidor['Sucursal']['direccion']; ?></td>                   
                        <td><?php echo $distribuidor['Sucursal']['telefono'] ?></td>
                        <td class="low-padding align-center">
                           <?php 
                           if($distribuidor['Almacene']['central'] == 1){
                               echo $this->Html->link('repartir', array('action'=>'listaentregas',$distribuidor['Almacene']['id'],1),array('class'=>"button compact icon-gear")); 
                               //echo $this->Html->link('registro recargas', array('action'=>'listaentregas',$distribuidor['Almacene']['id'],1),array('class'=>"button compact icon-gear")); 
                           }else
                               echo $this->Html->link('dar', array('action'=>'listaentregas',$distribuidor['Almacene']['id'],1),array('class'=>"button compact icon-gear")); 
                           ?>
                        </td>
                    </tr>               
                <?php endforeach; ?>
            </tbody>
        </table>       
    </div>
</section>	

<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 