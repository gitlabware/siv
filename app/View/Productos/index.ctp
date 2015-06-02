<style>
    
</style>
<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Productos</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="tabla-json">

            <thead>
                <tr>
                    <th scope="col" width="5%">Precios</th>
                    <th scope="col" width="5%">Imagen</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Nombre</th>
                    <th scope="col" width="5%" class="align-center hide-on-mobile">Precio compra</th>
                    <th scope="col" width="10%" class="align-center hide-on-mobile-portrait">proveedor</th>  
                    <th scope="col" width="8%" class="align-center hide-on-mobile-portrait">Fecha Ingreso</th>  
                    <th scope="col" width="20%" class="align-center">Acciones</th>
                </tr>
            </thead>          

            <tbody>
                
            </tbody>
        </table>               
    </div>
</section>
<script>
  urljsontabla = '<?php echo $this->Html->url(array('action' => 'index.json')); ?>';
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
<?php echo $this->element('sidebar/administrador'); ?>
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
  function editar_p(idproducto){
    window.location = '<?php echo $this->Html->url(array('action' => 'editar'));?>/'+idproducto;
  }
  function elimina_p(idproducto){
    if(confirm('Esta seguro de eliminar el producto??')){window.location = '<?php echo $this->Html->url(array('action' => 'delete'));?>/'+idproducto;}
  }
</script>