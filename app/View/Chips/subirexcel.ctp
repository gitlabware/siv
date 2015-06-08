<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>
    <hgroup id="main-title" class="thin">
        <h1>Registro de Chips</h1>
    </hgroup>
    <div class="with-padding">        
        <a href="javascript:void(0)" class="button orange-gradient glossy" id="btMuestraFormAsignaciones">SUBIR EXCEL ASIGNACIONES</a>
        <a href="javascript:void(0)" class="button green-gradient glossy" id="btMuestraFormActivaciones">SUBIR EXCEL ACTIVACIONES</a>
        <p>&nbsp;</p>
        <div id="muestraFormAsignaciones">
            <?php echo $this->Form->create('Chips', array('action' => 'guardaexcel', 'id' => 'formAsig', 'enctype' => 'multipart/form-data')); ?>
            <!--        <form method="post" action="" class="columns" onsubmit="return false">                               -->
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <!--                <h3 class="thin underline">&nbsp;</h3>                                          -->
                <fieldset class="fieldset fields-list">
                    <legend class="legend orange-gradient">Formulario Subida Excel Asignaciones</legend>
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Seleccionar Excel :</b></label>
                        <?php //echo $this->Form->text('Persona.nombre', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                        <span class="input file">
                            <span class="file-text"></span>
                            <span class="button compact orange-gradient">Seleccione</span>
                            <input type="file" name="data[Excel][excel]" id="special-input-1" class="file withClearFunctions" required />
                        </span>
                    </div>                                                                 

                    <div class="field-block button-height">
                        <button type="submit" id="btAsig" class="button glossy mid-margin-right">
                            <span class="button-icon orange-gradient"><span class="icon-save"></span></span>
                            Guardar Excel
                        </button>
                        &nbsp;
                        <button type="button" class="button glossy mid-margin-right" id="btMuestraFAsignaciones" onclick="openModal()"> 
                            <span class="button-icon"><span class="icon-search"></span></span>
                            Ver Formato
                        </button>
                    </div>                                        

                </fieldset>
            </div>
            </form>
        </div>

        <script>
          $("#formAsig").on("submit", function (e) {
             
             $("#btAsig").replaceWith("<span class='loader big working'></span> Trabajando ;)");
          });       

          function openModal()
          {
              //console.log('hizo click');
              $.modal({
                  content: '<div id="idmodal"></div>',
                  title: 'Formato del Archivo',
                  content: '<?php echo $this->Html->image('iconos/asignados.png'); ?>',
                          center: true,
                  width: 850,
                  height: 450,
              });
          }
          ;

        </script>

        <div id="muestraFormActivaciones" style="display: none;">
            <?php echo $this->Form->create('Chips', array('action' => 'guardaexcelactivados', 'id'=>'formActi', 'enctype' => 'multipart/form-data')); ?>
            <!--        <form method="post" action="" class="columns" onsubmit="return false">                               -->
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <!--                <h3 class="thin underline">&nbsp;</h3>                                          -->
                <fieldset class="fieldset fields-list">
                    <legend class="legend green-gradient">Formulario Subida Excel Activaciones</legend>
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Seleccionar Excel :</b></label>
                        <?php //echo $this->Form->text('Persona.nombre', array('class' => 'span12', 'required'));   ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                        <span class="input file">
                            <span class="file-text"></span>
                            <span class="button compact green-gradient">Seleccione</span>
                            <input type="file" name="data[Excel][excel]" id="special-input-1" value="" class="file withClearFunctions" required="" />
                        </span>
                    </div>                                                                 

                    <div class="field-block button-height">
                        <button type="submit" id="btActi" class="button glossy mid-margin-right">
                            <span class="button-icon green-gradient"><span class="icon-save"></span></span>
                            Guardar Excel
                        </button>

                        <button type="button" class="button glossy mid-margin-right" onclick="openModal2();">
                            <span class="button-icon"><span class="icon-search"></span></span>
                            Ver Formato
                        </button>
                    </div>                                       

                </fieldset>
            </div>
            </form>
        </div>

        <script>
          $("#formActi").on("submit", function (e) {
             
             $("#btActi").replaceWith("<span class='loader big working'></span> Trabajando ;)");
          }); 
          
          $(document).ready(function () {
              $("#btMuestraFormAsignaciones").click(function () {
                  $("#muestraFormAsignaciones").show('slow');
                  $("#muestraFormActivaciones").hide('slow');
              });

              $("#btMuestraFormActivaciones").click(function () {
                  $("#muestraFormActivaciones").show('slow');
                  $("#muestraFormAsignaciones").hide('slow');
              });
          });

          function openModal2()
          {
              //console.log('hizo click');
              $.modal({
                  title: 'Formato del Archivo',
                  content: '<?php echo $this->Html->image('iconos/activados.png'); ?>',
                  center: true,
                  width: 1190,
                  height: 450,
              });
          }
          ;
        </script>

        <p>&nbsp;</p>
        <table class="table responsive-table" id="sorting-advanced">

            <thead>
                <tr>                      
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Nombre</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile">Fecha</th>
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">Tipo</th>                                         
                    <th scope="col" width="15%" class="align-center hide-on-mobile-portrait">Acciones</th> 
                </tr>
            </thead>          

            <tbody>
                <?php foreach ($excels as $e): ?>
                  <tr>                      
                      <td><?php echo $e['Excel']['nombre_original']; ?></td>                        
                      <td><?php echo $e['Excel']['created']; ?></td>
                      <td><?php echo $e['Excel']['tipo']; ?></td>                       
                      <td>Detalle</td>                       
                  </tr>               
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/administrador'); ?>
<!-- End sidebar/drop-down menu -->