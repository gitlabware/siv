
<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Insertar Nuevo Usuario</h1>
    </hgroup>
  <?php echo $this->Form->create('User', array('id' => 'formID')); ?>
    <div class="with-padding"> 

        <div class="columns">

            <div class="new-row four-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Nombre <small>(requerido)</small></label>                    
                    <?php echo $this->Form->text('Persona.nombre', array('class' => 'input full-width', 'placeholder' => 'Ingrese el nombre', 'value'=>"")); ?>
                </p>
            </div>
            <div class="four-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Apellido Paterno <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona-ap_paterno', array('class'=>'input full-width', 'placeholder'=>'Ingrese el apellido paterno','value'=>""));?>
                    <input type="text" name="block-label-2" id="block-label-2" class="input full-width" value="">
                </p>  
            </div>

            <div class="four-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Apellido Paterno <small>(requerido)</small></label>
                    <input type="text" name="block-label-2" id="block-label-2" class="input full-width" value="">
                </p>  
            </div>

            <div class="new-row four-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">C.I. <small>(requerido)</small></label>
                    <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">                       
                </p>
            </div>

            <div class="eight-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Direccion <small>(requerido)</small></label>
                    <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">                       
                </p>
            </div>

            <div class="six-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Telefono <small>(requerido)</small></label>
                    <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">                       
                </p>
            </div>

            <div class="six-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Celular <small>(requerido)</small></label>
                    <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">
                </p>
            </div>

            <div class="four-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Usuario <small>(requerido)</small></label>
                    <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">
                </p>
            </div>

            <div class="four-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Password <small>(requerido)</small></label>
                    <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">
                </p>
            </div>
            <div class="four-columns">
              <p class="button-height inline-label">
                  <label for="validation-select" class="label">Tipo de usuario <small>(requerido)</small></label>
                  <select id="validation-select" name="validation-select" class="select validate[required]" class="input full-width" >
                    <option value="">Please select</option>
                   
                </select>
            </p>  
            </div>
            

        </div>

        <?php echo $this->Form->create('User', array('id' => 'formID')); ?>

        <form method="post" action="" class="columns" onsubmit="return false" name="formulario">                               
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <h3 class="thin underline">&nbsp;</h3>                                          

                <fieldset class="fieldset fields-list">

                    <legend class="legend">Formulario Registro de Usuario </legend>

                    <p class="block-label button-height">
                        <label for="block-label-1" class="label">Full article title <small>(255 chars max.)</small></label>
                        <input type="text" name="block-label-1" id="block-label-1" class="input full-width" value="">                       
                    </p>

                    <p class="block-label button-height">
                        <label for="block-label-2" class="label">Author name <small>(may be empty)</small></label>
                        <input type="text" name="block-label-2" id="block-label-2" class="input full-width" value="">
                    </p>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Nombre :</b></label>
                        <?php echo $this->Form->text('Persona.nombre', array('class' => 'span12', 'required')); ?>

                        <label for="login" class="label"><b>Nombre :</b></label>
                        <?php echo $this->Form->text('Persona.nombre', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Apellido Paterno :</b></label>
                        <?php echo $this->Form->text('Persona.ap_paterno', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Apellido Materno :</b></label>
                        <?php echo $this->Form->text('Persona.ap_materno'); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Ci :</b></label>
                        <?php echo $this->Form->text('Persona.ci', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Direccion :</b></label>
                        <?php echo $this->Form->text('Persona.direccion', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Telefono :</b></label>
                        <?php echo $this->Form->text('Persona.telefono'); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Celular :</b></label>
                        <?php echo $this->Form->text('Persona.celular', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Usuario :</b></label>
                        <?php echo $this->Form->text('username', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Ingrese su Password :</b></label>
                        <?php echo $this->Form->password('password', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">
                        <label for="validation-select1" class="label"><b>Tipo de Usuario :</b></label>
                        <select id="validation-select1" name="data[User][group_id]" class="select expandable-list anthracite-gradient glossy validate[required]" style="width: 200px" >

                            <option value="">
                                Seleccione el tipo de Usuario
                            </option>
                            <?php foreach ($groups as $g): ?>
                                <option value="<?php echo $g['Group']['id'] ?>">
                                    <?php echo $g['Group']['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="mostrartienda" style="display: none">
                        <div class="field-block button-height">
                            <label for="validation-select" class="label"><b>Tienda en la que trabaja:</b></label>
                            <select id="validation-select1" name="data[User][sucursal_id]" class="select"  style="width: 200px"  >
                                <option value="" >
                                    Seleccione la tienda
                                </option>
                                <?php foreach ($tiendas as $g): ?>
                                    <option value="<?php echo $g['Sucursal']['id'] ?>">
                                        <?php echo $g['Sucursal']['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                </fieldset>
                <div class="field-block button-height">

                    <button type="submit" class="button glossy mid-margin-right" onClick="javascript:verificar()">
                        <span class="button-icon"><span class="icon-tick"></span></span>
                        Guardar Usuario
                    </button>

                    <button type="submit" class="button glossy">
                        <span class="button-icon red-gradient"><span class="icon-cross-round"></span></span>
                        Cancelar
                    </button>

                </div>


            </div>

        </form>

    </div>

</section>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
<script>
    $(document).ready(function () {
        $("#validation-select1").change(function () {
            if (this.value == 5) {
                $('#mostrartienda').show();
            } else {
                $('#mostrartienda').hide();
            }
        });
    });
</script>
<script>
    $(document).ready(function () {

        $("#formID").validationEngine();


    });
</script>
