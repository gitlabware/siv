<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Entregas a <?php echo $cliente['Cliente']['nombre'] ?> de <?php echo $fecha; ?></h1>
    </hgroup>
    <div class="with-padding">                   
        <table class="Clientestable responsive-table" id="sorting-advanced">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Cantidad</th>
                    <th>Tipo</th>
                    <th>Sim</th>
                    <th>Imsi</th>
                    <th>Telefono</th>
                    <th>Accion</th>
                </tr>
            </thead>          
            <tbody>
                <?php foreach ($entregados as $ent): ?>
                  <tr>
                      <td><?php echo $ent['Chip']['id'] ?></td>
                      <td><?php echo $ent['Chip']['cantidad'] ?></td>
                      <td><?php echo $ent['Chip']['tipo_sim'] ?></td>
                      <td><?php echo $ent['Chip']['sim'] ?></td>
                      <td><?php echo $ent['Chip']['imsi'] ?></td>
                      <td><?php echo $ent['Chip']['telefono'] ?></td>
                      <td>
                          <a href="javascript:" class="" onclick="cancelar('<?php echo $this->Html->url(array('controller' => 'Ventasdistribuidor', 'action' => 'cancela_entrega_id', $ent['Chip']['id'])); ?>');">Cancelar</a>
                      </td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
        </table>  
    </div>
</section>	

<script>
  function cancelar(url){
    if(confirm("Esta seguro de cancelar la entrega??")){
      window.location = url;
    }
  }
</script>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/distribuidor'); ?>
<!-- End sidebar/drop-down menu --> 

