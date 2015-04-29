<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Nuevo Producto</h1>
    </hgroup>

    <?php echo $this->Form->create('Producto', array('id' => 'formID')); ?>
    <div class="with-padding"> 

        <div class="columns">

            <div class="new-row four-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Nombre <small>(requerido)</small></label>                    
                    <?php echo $this->Form->text('nombre', array('class' => 'input full-width', 'placeholder' => 'Ingrese el nombre del producto.')); ?>
                </p>
            </div>
            <div class="four-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Precio<small>(requerido)</small></label>
                    <?php echo $this->Form->text('precio_compra', array('class' => 'input full-width', 'placeholder' => 'Ingrese el precio.')); ?>
                </p>  
            </div>

            <div class="four-columns">                
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Proveedor<small>(requerido)</small></label>
                    <?php echo $this->Form->text('proveedor', array('class' => 'input full-width', 'placeholder' => 'Ingrese el nombre del proveedor.')); ?>
                </p>  
            </div>

            <div class="four-columns">
                <p class="block-label button-height">
                    <label for="validation-select" class="label">Producto <small>(requerido)</small></label>

                    <select id="validation-select" name="data[Producto][tiposproducto_id]" class="select" style="width: 310px">
                        <?php foreach ($tiposproductos as $tip): ?>
                            <option value="<?php echo $tip['Tiposproducto']['id'] ?>">
                                <?php echo $tip['Tiposproducto']['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p>  
            </div>

            <div class="eight-columns">
                <p class="block-label button-height">
                    <label for="block-label-2" class="label">Observaciones<small>(Requerido)</small></label>
                    <?php echo $this->Form->text('observaciones', array('class' => 'input full-width')); ?>
                </p>

            </div>

            <div class="six-columns">

                <button type="submit" class="button glossy mid-margin-right">
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
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
<script>
    $(document).ready(function () {

        $("#formID").validationEngine();


    });
</script>