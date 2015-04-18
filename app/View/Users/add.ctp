
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
                    <?php echo $this->Form->text('Persona.nombre', array('class' => 'input full-width', 'placeholder' => 'Ingrese el nombre', 'value' => "")); ?>
                </p>
            </div>
            <div class="four-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Apellido Paterno <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona-ap_paterno', array('class' => 'input full-width', 'placeholder' => 'Ingrese el apellido paterno', 'value' => "")); ?>
                </p>  
            </div>

            <div class="four-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Apellido Materno <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona.ap_materno', array('class' => 'input full-width', 'placeholder' => 'Ingrese el apellido materno')); ?>
                </p>  
            </div>

            <div class="new-row two-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">C.I. <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona.ci', array('class' => 'input full-width', 'placeholder' => 'Ingrese la cedula de identidad')); ?>
                </p>
            </div>

            <div class="six-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Direccion <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona.direccion', array('class' => 'input full-width', 'placeholder' => 'Direccion')); ?>                       
                </p>
            </div>

            <div class="two-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Telefono <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona.telefono', array('class' => 'input full-width', 'placeholder' => 'Numero telefonico', 'type' => 'number')) ?>

                </p>
            </div>

            <div class="two-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Celular <small>(requerido)</small></label>
                    <?php echo $this->Form->text('Persona.celular', array('class' => 'input full-width', 'placeholder' => 'numero de celular')); ?>
                </p>
            </div>

            <div class="three-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Usuario <small>(requerido)</small></label>
                    <?php echo $this->Form->text('username', array('class' => 'input full-width', 'placeholder' => 'Nombre de Usuario')); ?>
                </p>
            </div>

            <div class="three-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Password <small>(requerido)</small></label>
                    <?php echo $this->Form->password('password', array('class' => 'input full-width', 'placeholder' => 'Password')) ?>
                </p>
            </div>
<<<<<<< HEAD
            <div class="two-columns">
                <p class="block-label button-height">
                    <label for="validation-select" class="label">Tipo de usuario <small>(requerido)</small></label>
=======
            <div class="four-columns">
                <p class="button-height inline-label">
                    <label for="validation-select" class="label">Tipo de usuario <small>(requerido)</small></label>
                    <select id="validation-select" name="validation-select" class="select validate[required]" class="input full-width" >
                        <option value="">Please select</option>

                    </select>
                </p>  
            </div>


        </div>
>>>>>>> dfcaafb19f967b516e82cc68b6b720857db40ff6

                    <select id="validation-select1" name="data[User][group_id]" class="select validate[required]" class="input full-width" >
                        <?php foreach ($groups as $g): ?>
                            <option value="<?php echo $g['Group']['id'] ?>">
                                <?php echo $g['Group']['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>  
            </div>
            <div class="two-columns">
                <p class="block-label button-height" id="mostrartienda" style="display: none">
                    <label for="validation-select" class="label"><b>Tienda en la que trabaja:</b></label>
                    <select id="validation-select1" name="data[User][sucursal_id]" class="select"  style="width: 200px"  >
                        
                        <?php foreach ($tiendas as $g): ?>
                            <option value="<?php echo $g['Sucursal']['id'] ?>">
                                <?php echo $g['Sucursal']['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>
        
            <div class="six-columns">

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
