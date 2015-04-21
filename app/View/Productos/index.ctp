<style>
    .sin-precios{
        background-color: #FFCCBA;
    }
</style>
<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Productos</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>
                    <th scope="col" width="5%">Precios</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Nombre</th>
                    <th scope="col" width="5%" class="align-center hide-on-mobile">Precio compra</th>
                    <th scope="col" width="10%" class="align-center hide-on-mobile-portrait">proveedor</th>  
                    <th scope="col" width="10%" class="align-center hide-on-mobile-portrait">Fecha Ingreso</th>
                    <th scope="col" width="20%" class="align-center hide-on-mobile-portrait">Observaciones</th>    
                    <th scope="col" width="25%" class="align-center">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                <?php
                $i = 1;
                foreach ($productos as $p):
                  $clasetd = '';
                  if ($p['Producto']['precios'] == 0) {
                    $clasetd = 'sin-precios';
                  }
                  ?>
                  <tr> 
                      <td><?php echo $p['Producto']['precios']; ?></td>
                      <td id="idproducto-<?php echo $p['Producto']['id']; ?>" class="<?php echo $clasetd; ?>"><?php echo $p['Producto']['nombre']; ?></td>
                      <td><?php echo $p['Producto']['precio_compra']; ?></td>
                      <td><?php echo $p['Producto']['proveedor']; ?></td>
                      <td><?php echo $p['Producto']['fecha_ingreso']; ?></td>
                      <td><?php echo $p['Producto']['observaciones']; ?></td>             
                      <td>
                          <?php //echo $this->html->url(array('action'=>'editar',$p['Producto']['id'])); ?>
                          <?php //echo $this->Html->url(array( 'controller'=>'Productosprecios','action'=>'precios',$p['Producto']['id']));    ?>
                          <?php //echo $this->Html->url(array('action'=>'delete',$p['Producto']['id'])); ?>
                          <a href="javascript:" class="button orange-gradient compact icon-pencil" onclick="window.location = '<?php echo $this->Html->url(array('action' => 'editar', $p['Producto']['id']));?>';">Editar</a>
                          <a href="javascript:" class="button anthracite-gradient compact icon-page-list" onclick="precios_productos(<?php echo $p['Producto']['id'] ?>);">Percios</a>
                          <a href="javascript:" class="button red-gradient compact icon-cross-round" onclick="if(confirm('Esta seguro de eliminar el producto??')){window.location = '<?php echo $this->Html->url(array('action' => 'delete', $p['Producto']['id']));?>';}">Eliminar</a>
                          <?php //echo $this->Html->link($this->Html->image("iconos/editar.png", array("alt" => 'Editar', 'title' => 'editar')), array('action' => 'editar', $p['Producto']['id']), array('escape' => false)); ?>
                          <?php //echo $this->Html->link($this->Html->image("iconos/otro.png", array("alt" => 'Editar', 'title' => 'Productos Precios')), array('controller' => 'Productosprecios', 'action' => 'precios', $p['Producto']['id']), array('escape' => false)); ?>
                          <?php //echo $this->Html->link($this->Html->image("iconos/eliminar.png", array("alt" => 'eliminar', 'title' => 'eliminar')), array('action' => 'delete', $p['Producto']['id']), array('escape' => false), ("Desea eliminar realmente??")); ?>
                          
                      </td>
                  </tr>               
                <?php endforeach; ?>
            </tbody>
        </table>   
        <div class="low-padding align-center">
            <?php echo $this->Html->link('INSERTAR NUEVO PRODUCTO', array('action' => 'insertar'), array('class' => 'button blue-gradient')); ?>
        </div>    
    </div>
</section>
<script>
  $(document).ready(function () {
      $("#formID").validationEngine();
  });
  function precios_productos(idproducto)
  {
      /*$.modal({
       title: 'Iframe content',
       url: '<?php echo $this->Html->url(array('controller' => 'Productosprecios', 'action' => 'ajax_precios')); ?>/' + idproducto,
       useIframe: true,
       width: 600,
       height: 400
       });*/



      $.modal({
          content: '<div id="idmodal"></div>',
          title: 'PRECIOS DEL PRODUCTO',
          width: 600,
          height: 400,
          actions: {
              'Close': {
                  color: 'red',
                  click: function (win) {
                      win.closeModal();
                  }
              }
          },
          buttonsLowPadding: true
      });
      $('#idmodal').load('<?php echo $this->Html->url(array('controller' => 'Productosprecios', 'action' => 'ajax_precios')); ?>/' + idproducto);
  }

</script>
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
<script>
  function mensaje_nota(titulo, texto) {
      notify(titulo, texto, {
          system: true,
          vPos: 'top',
          hPos: 'right',
          autoClose: true,
          icon: false ? 'img/demo/icon.png' : '',
          iconOutside: true,
          closeButton: true,
          showCloseOnHover: true,
          groupSimilar: true
      });
  }
</script>