<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Precios</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                      
                    <th>Nombre</th>
                    <th>Escala</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
                
            </thead>          

            <tbody>
               <?php foreach ($productosprecios as $pe): ?>
               <tr> 
                  
                
                        <td><?php echo $pe['Producto']['nombre'];?></td>                 
                        <td><?php echo $pe['Productosprecio']['escala']; ?></td>
                        <td><?php echo $pe['Productosprecio']['precio']?></td>
                    <td class="low-padding align-center"><a href="<?php echo $this->html->url(array('action'=>'editar',$pe['Productosprecio']['id'])); ?>" class="button compact icon-gear">Edit</a>
                         <a href="<?php echo $this->html->url(array('action'=>'delete',$pe['Productosprecio']['id'])); ?>" class="button compact icon-gear">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
               
            </tbody>
        </table>       
    </div>
</section>	

<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 

