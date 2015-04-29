<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Nuevo Almacen</h1>
    </hgroup>

    <div class="with-padding"> 
        <?php echo $this->Form->create('Cliente', array('id' => 'formID')) ?>
        <div class="columns">

            <div class="new six-columns">

                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Nombre <small>(requerido)</small></label>                    
                    <?php echo $this->Form->text('nombre', array('class' => 'input full-width')); ?>
                </p>
            </div>
            <div class="three-columns">
                <p class="block-label button-height">
                    <label for="validation-select" class="label">Tienda<small>(Requerido)</small></label>
                    <select id="validation-select" name="data[Almacene][sucursal_id]" class="select" style="width: 222px">
                        <?php foreach ($sucursales as $suc): ?>
                            <option value="<?php echo $suc['Sucursal']['id'] ?>">
                                <?php echo $suc['Sucursal']['nombre'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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

            <div class="new six-columns">

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



            <div class="six-columns">

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
<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Nuevo Almacen</h1>
    </hgroup>
    <div class="with-padding"> 

        <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>


        <?php echo $this->Form->create('Almacene'); ?>
        <form method="post" action="" class="columns" onsubmit="return false">                               
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <h3 class="thin underline">&nbsp;</h3>                                          

                <fieldset class="fieldset fields-list">

                    <legend class="legend">formulario </legend>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Nombre Almacen</b></label>
                        <?php echo $this->Form->text('nombre', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>									

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Tienda</b></label>
                        <?php echo $this->Form->select('sucursal_id', $sucursals, array('class' => 'select', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Telefono</b></label>
                        <?php echo $this->Form->text('descripcion', array('class' => 'input')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>




                    <div class="field-block button-height">

                        <button type="submit" class="button glossy mid-margin-right">
                            <span class="button-icon"><span class="icon-tick"></span></span>
                            Guardar
                        </button>

                        <button type="submit" class="button glossy">
                            <span class="button-icon red-gradient"><span class="icon-cross-round"></span></span>
                            Cancelar
                        </button>

                    </div>

                </fieldset>
            </div>

        </form>

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