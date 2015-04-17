<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de usuarios</h1>
    </hgroup>

    <div class="with-padding">   
        <div class="twelve-columns twelve-columns-mobile">
            <p class="button-height">
                <a href="<?php echo $this->Html->url(array('action' => 'insertar')) ?>" class="button">
                    <span class="button-icon"><span class="icon-star"></span></span>
                    Crear nuevo Usuario
                </a>

            </p>
            <p>

            </p>

        </div>

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                      
                    <th scope="col" width="5%" class="align-center hide-on-mobile">Nro.</th>
                    <th scope="col" width="20%" class="align-center hide-on-mobile">Nombre</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">Apellido Paterno</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">Usuario</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">Tipo Usuario</th>
                    <th scope="col" width="10" class="align-center">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                <?php $i = 1;foreach ($users as $usu): ?>
                    
                    <tr>                      
                        <td><?php echo $i;$i++; ?></td> 
                        <td><?php echo $usu['Persona']['nombre']; ?></td>
                        <td><?php echo $usu['Persona']['ap_paterno']; ?></td>
                        <td><?php echo $usu['User']['username']; ?></td>
                        <td><?php echo $usu['Group']['name']; ?></td>
                        <td class="low-padding align-center">
                        <?php $ajaxv = 'openAjax('.$usu['User']['id'].')'?>
                            <?php echo $this->Html->image("iconos/menu.png", array('onclick'=>$ajaxv)); ?>
                            <?php echo $this->Html->link($this->Html->image("iconos/editar.png", array("alt" => 'Editar', 'title' => 'editar')), array('action' => 'editar', $usu['User']['id']), array('escape' => false)); ?>
                            <?php echo $this->Html->link($this->Html->image("iconos/cambiopass.png", array("alt" => 'Editar', 'title' => 'Cambio Password')), array('action' => 'cambiopass', $usu['User']['id']), array('escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image("iconos/eliminar.png", array("alt" => 'eliminar', 'title' => 'eliminar')), array('action' => 'delete', $usu['User']['id']), array('escape' => false), ("Desea eliminar realmente??"));
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

<script>
    
    function openAjax(id)
		{
		 
			$.modal({
				title: 'Datos de Usuario',
				url: '<?php echo $this->Html->url(array('action' => 'ajaxver')) ?>/'+id,
				width: 300
			});
		};
</script>

