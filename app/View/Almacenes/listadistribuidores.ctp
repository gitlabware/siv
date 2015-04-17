<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Distribuidores y tiendas</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                     
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Apellido Paterno</th>  
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Apellido Materno</th>  
                    <th scope="col" width="15%">Nombre</th>
                    <th class="align-center hide-on-mobile">Tipo</th>                  
                    <th scope="col" class="align-center">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                <?php foreach ($distribuidores as $distribuidor): ?>
                    <tr>    
                        <td><?php echo $distribuidor['Persona']['ap_paterno']; ?></td>
                        <td><?php echo $distribuidor['Persona']['ap_materno']; ?></td>               
                        <td><?php echo $distribuidor['Persona']['nombre']; ?></td>                   
                        <td><?php echo $distribuidor['Group']['name'] ?></td>
                        <td class="low-padding align-center">
                           <?php echo $this->Html->link('repar', array('action'=>'listaentregas',$distribuidor['Persona']['id'],0),array('class'=>"button compact icon-gear")) ?>
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