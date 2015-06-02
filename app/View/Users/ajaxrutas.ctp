<div class="with-padding">
    <?php echo $this->Form->create('User', array('action' => 'registraruta', 'id' => 'form_ruta')); ?>
    <div class="columns">

        <div class="new-row twelve-columns">
            <p class="block-label button-height">
                <label for="block-label-1" class="label">Ruta <small>(requerido)</small></label> 
                <?php echo $this->Form->hidden('user_id', array('value' => $idUsuario)) ?>
                <?php echo $this->Form->select('ruta_id', $csr, array('class' => 'select full-width')); ?>
            </p>
        </div>                
        <div class="twelve-columns">
            <p class="block-label button-height">
                <a href="javascript:" class="button green-gradient full-width" onclick="registra_rutas();">REGISTRAR</a>
            </p>
        </div>
    </div>
    <?php echo $this->Form->end(); ?>

    <table class="simple-table responsive-table" id="sorting-example2">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ruta</th>                
                <th>Quitar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rutasUsuario as $r): ?>
              <tr>
                  <td><?php echo $r['Rutasusuario']['id']; ?></td>
                  <td><?php echo $r['Ruta']['nombre']; ?></td>                  
                  <td>
                      <a class="button red-gradient compact icon-cross-round" href="javascript:" onclick="quita_precio(<?php echo $pre['Productosprecio']['id']; ?>)">Quitar</a>
                  </td>
              </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
<?php if (count($precios) == 0): ?>
    $('#idproducto-<?php echo $idProducto ?>').addClass('red-bg');
    $('#idproducto-<?php echo $idProducto ?>').html('<?php echo count($precios);?>');
<?php else: ?>
    $('#idproducto-<?php echo $idProducto ?>').removeClass('red-bg');
    $('#idproducto-<?php echo $idProducto ?>').html('<?php echo count($precios);?>');
<?php endif; ?>
  function registra_rutas() {
      var postData = $('#form_ruta').serializeArray();
      var formURL = $('#form_ruta').attr("action");
      $.ajax(
              {
                  url: formURL,
                  type: "POST",
                  data: postData,
                  /*beforeSend:function (XMLHttpRequest) {
                   alert("antes de enviar");
                   },
                   complete:function (XMLHttpRequest, textStatus) {
                   alert('despues de enviar');
                   },*/
                  success: function (data, textStatus, jqXHR)
                  {
                      if ($.parseJSON(data).correcto != '') {
                          mensaje_nota('Excelente!!', $.parseJSON(data).correcto);
                      } else {
                          mensaje_nota('Error!!', 'No se registro el precio!!');
                      }

                      $('#idmodal').load('<?php echo $this->Html->url(array('controller' => 'Users', 'action' => 'ajaxrutas', $idUsuario)); ?>');
                      //data: return data from server
                      //$("#parte").html(data);
                  },
                  error: function (jqXHR, textStatus, errorThrown)
                  {
                      //if fails   
                      alert("error");
                  }
              });
  }
  function quita_precio(idprecio) {
      if (confirm('Esta seguro de quitar el precio??')) {

          $("#idmodal").load("<?php echo $this->Html->url(array('controller' => 'Productosprecios', 'action' => 'quita_precio')); ?>/" + idprecio + "/<?php echo $idProducto; ?>", function (responseTxt, statusTxt, xhr) {
              if ($.parseJSON(responseTxt).correcto != '') {
                  mensaje_nota('Excelente!!', $.parseJSON(responseTxt).correcto);
              } else {
                  mensaje_nota('Error!!', 'No se registro el precio!!');
              }
              $('#idmodal').load('<?php echo $this->Html->url(array('controller' => 'Productosprecios', 'action' => 'ajax_precios', $idProducto)); ?>');
              if (statusTxt == "error")
                  alert("Error: " + xhr.status + ": " + xhr.statusText);
          });
      }
  }

</script>