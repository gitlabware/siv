

<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>Nuevo Cliente</h1>
    </hgroup>
    <div class="with-padding">
         <?php echo $this->Form->create('Cliente',array('class'=>'columns', 'id'=>'formID')) ?>
        
        <form method="post" action="" class="columns" onsubmit="return false">                               
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <h3 class="thin underline">&nbsp;</h3>                                          

                <fieldset class="fieldset fields-list">

                    <legend class="legend">Formulario </legend>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Nombre</b></label>
                        <?php echo $this->Form->text('nombre',array('class'=>'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>									

                     <div class="field-block button-height">							
                        <label for="login" class="label"><b>Numero de registro</b></label>
                        <?php echo $this->Form->text('num_registro', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>


                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Direccion</b></label>
                        <?php echo $this->Form->text('direccion', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Celular</b></label>
                        <?php echo $this->Form->text('celular', array('class' => 'input')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                   
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Zona</b></label>
                        <?php echo $this->Form->text('zona', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Inspector</b></label>
                        <?php echo $this->Form->text('inspector', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                     <div class="field-block button-height">							
                        <label for="login" class="label"><b>Mercado</b></label>
                        <?php echo $this->Form->text('mercado', array('class' => 'input validate[required]')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
                     
                    
                                          
                                   
               
<!--                        <label for="validation-select" class="label"><b>grupo</b></label>
                        <span class="select  replacement" style="width: 117px;" tabindex="0">
                            <span class="select-value">First option</span><span class="select-arrow"></span><span class="drop-down custom-scroll" style=""><span class="">Please select</span><span class="selected">First option</span><span>Second option</span><span>Third option</span><div class="custom-vscrollbar" style="display: none;"><div></div></div></span><select id="validation-select" name="validation-select" class="validate[required] withClearFunctions" tabindex="-1" style="display: none;">
                                <option value="">Please select</option>
                                <option value="1">First option</option>
                                <option value="2">Second option</option>
                                <option value="3">Third option</option>
                            </select>
                        </span>-->
                        
                    </div>
                    
                   </fieldset>
                    <div class="field-block button-height">

                        <button type="submit" class="button glossy mid-margin-right">
                            <span class="button-icon"><span class="icon-tick"></span></span>
                            Save
                        </button>

                        <button type="submit" class="button glossy">
                            <span class="button-icon red-gradient"><span class="icon-cross-round"></span></span>
                            Cancel
                        </button>

                    </div>

                
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