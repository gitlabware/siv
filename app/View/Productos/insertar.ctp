
<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Nuevo Producto</h1>
    </hgroup>
    <div class="with-padding"> 
    <?php echo $this->Form->create('Producto', array('id'=>'formID')); ?>

        <form method="post" action="" class="columns" onsubmit="return false">                               
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <h3 class="thin underline">&nbsp;</h3>                                          

                <fieldset class="fieldset fields-list">

                    <legend class="legend">Formulario </legend>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Nombre :</b></label>
                        <?php echo $this->Form->text('nombre', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Precio compra :</b></label>
                        <?php echo $this->Form->text('precio_compra', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Proveedor :</b></label>
                        <?php echo $this->Form->text('proveedor', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>									
                    
                    <div class="field-block button-height">
                   <label for="validation-select" class="label"><b>Producto :</b></label>
                        <select id="validation-select1" name="data[Producto][tiposproducto_id]" class="select validate[required]" style="width: 200px">
					
                            <option value="">
						Seleccione tipo				</option>
					<?php foreach($tiposproductos as $t): ?>
                    <option value="<?php echo $t['Tiposproducto']['id'] ?>">
                    <?php echo $t['Tiposproducto']['nombre'] ?>
                    </option>
                    <?php endforeach; ?>
				</select>
                    </div>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Observaciones :</b></label>
                        <?php echo $this->Form->text('observaciones', array('class' => 'span12')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                    
                    

                    <div class="field-block button-height">

                    <button type="submit" class="button glossy mid-margin-right">
                    <span class="button-icon"><span class="icon-tick"></span></span>
                    GUARDAR
                    </button>
                    <?php echo $this->Html->link('ATRAS',array('action'=>'index'),array('class'=>'button glossy mid-margin-right'));?>

                    </div>

                </fieldset>
            </div>

        </form>

    </div>

</section>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
<script>
    $(document).ready(function() {

        $("#formID").validationEngine();

        
    });
</script>