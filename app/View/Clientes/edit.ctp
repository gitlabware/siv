<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Editar Cliente</h1>
    </hgroup>

    <div class="with-padding"> 
        <?php echo $this->Form->create('Cliente', array('id' => 'formID')) ?>
        <div class="columns">

            <div class="new-row six-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Nombre <small>(requerido)</small></label>                    
                    <?php echo $this->Form->text('nombre', array('class' => 'input full-width')); ?>
                </p>
            </div>
            <div class="six-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Direccion<small>(requerido)</small></label>
                    <?php echo $this->Form->text('direccion', array('class' => 'input full-width')); ?>
                </p>  
            </div>

            <div class="new three-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Numero de Registro<small>(requerido)</small></label>
                    <?php echo $this->Form->text('num_registro', array('class' => 'input full-width')); ?>
                </p>  
            </div>

            <div class="three-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Celular<small>(requerido)</small></label>
                    <?php echo $this->Form->text('celular', array('class' => 'input full-width')); ?>
                </p>
            </div>

            <div class="six-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Zona<small>(requerido)</small></label>
                    <?php echo $this->Form->text('zona', array('class' => 'input full-width')); ?>                       
                </p>
            </div>

            <div class="new-row six-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Inspector<small>(requerido)</small></label>
                    <?php echo $this->Form->text('inspector', array('class' => 'input full-width')) ?>

                </p>
            </div>

            <div class="three-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Mercado<small>(requerido)</small></label>
                    <?php echo $this->Form->text('mercado', array('class' => 'input full-width')); ?>
                </p>
            </div>

            <div class="three-columns">
                <p class="block-label button-height">
                    <label for="validation-select" class="label">Lugar<small>(Requerido)</small></label>
                    <select id="validation-select" name="data[Cliente][lugare_id]" class="select validate[required]" class="input full-width" style="width: 223px">
                        <?php foreach ($lugares as $lu): ?>
                            <?php if ($lu['Lugare']['id'] == $lugar): ?>
                                <option value="<?php echo $lu['Lugar']['id'] ?>" selected="selected">
                                    <?php echo $lu['Lugar']['nombre'] ?>
                                </option>
                            <?php else: ?>
                                <option value="<?php echo $lu['Lugare']['id'] ?>">
                                    <?php echo $lu['Lugare']['nombre'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </p>
            </div>

            <div class="new-row twelve-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Observaciones<small>(Requerido)</small></label>
                    <?php echo $this->Form->text('observaciones', array('class' => 'input full-width')); ?>
                </p>
            </div>

            <div class="new-row six-columns">

                <button type="submit" class="button glossy mid-margin-right" onClick="javascript:verificar()">
                    <span class="button-icon"><span class="icon-tick"></span></span>
                    Guardar
                </button>

                <button type="submit" class="button glossy">
                    <span class="button-icon red-gradient"><span class="icon-cross-round"></span></span>
                    Cancelar
                </button>

            </div>
        </div>
    </div>
</section>


<script>
    $(document).ready(function () {
        $("#formID").validationEngine();
    });
</script>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 