<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Nueva tienda </h1>
    </hgroup>
    <div class="with-padding"> 

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    
         <?php echo $this->Form->create('Sucursal'); ?>
        <form method="post" action="" class="columns" onsubmit="return false">                               
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <h3 class="thin underline">&nbsp;</h3>                                          

                <fieldset class="fieldset fields-list">

                    <legend class="legend">formulario </legend>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Nombre tienda</b></label>
                        <?php echo $this->Form->text('nombre',array('class'=>'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>									

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Direccion</b></label>
                        <?php echo $this->Form->text('direccion', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Telefono</b></label>
                        <?php echo $this->Form->text('telefono', array('class' => 'input')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Numero de Cabinas</b></label>
                        <?php echo $this->Form->text('ncabinas', array('class' => 'input')); ?>
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
$(document).ready(function(){$("#formID").validationEngine();});
</script>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 