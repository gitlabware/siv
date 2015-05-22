<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Listado de Clientes</h1>
    </hgroup>

    <div class="with-padding">                   

        <table class="table responsive-table" id="tabla-json">

            <thead>
                <tr>
                    <th style="width: 10%;">numero de registro</th>
                    <th >nombre</th>
                    <th style="width: 30%;" class="hide-on-mobile">direccion</th>  
                    <th class="hide-on-mobile">celular</th>
                    <th >zona</th>
                    <th >Acciones</th>
                </tr>
            </thead>          
            <tbody>
                
            </tbody>
        </table>  
    </div>
</section>	

<script>
  function asignar(idcliente) {
      location = '<?php echo $this->Html->url(array('action' => 'chips')); ?>/' + idcliente;
  }
  function venta(idcliente) {
      location = '<?php echo $this->Html->url(array('action' => 'formulario')); ?>/' + idcliente;
  }
</script>
<script>
  urljsontabla = '<?php echo $this->Html->url(array('action' => 'clientes.json')); ?>';
  datos_tabla2 = {};
  datos_tabla2 = {
      'sPaginationType': 'full_numbers',
      'sDom': '<"dataTables_header"lfr>t<"dataTables_footer"ip>',
      'bProcessing': true,
      'sAjaxSource': urljsontabla,
      'sServerMethod': 'POST',
      "order": [],
      'fnInitComplete': function (oSettings)
      {
          // Style length select
          table2.closest('.dataTables_wrapper').find('.dataTables_length select').addClass('select blue-gradient glossy').styleSelect();
          tableStyled = true;
      }, "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
          $('td:eq(2)', nRow).addClass('hide-on-mobile');
          $('td:eq(3)', nRow).addClass('hide-on-mobile');
      }
  };
</script>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/distribuidor'); ?>
<!-- End sidebar/drop-down menu --> 

