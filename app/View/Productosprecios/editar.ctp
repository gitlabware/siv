<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>editar Precio</h1>
    </hgroup>
    <div class="with-padding"> 
    <?php echo $this->Form->create('Productosprecio'); ?>

        <form method="post" action="" class="columns" onsubmit="return false">                               
            <!--<div class="new-row-desktop four-columns six-columns-tablet twelve-columns-mobile">-->
            <div class="new-row twelve-columns">                
                <h3 class="thin underline">&nbsp;</h3>                                          

                <fieldset class="fieldset fields-list">

                    <legend class="legend">Formulario </legend>

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Escala</b></label>
                        <?php echo $this->Form->text('escala', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Para quien</b></label>
                        <?php echo $this->Form->select('tipousuario_id',$tipos, array('class' => 'select', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>									

                    <div class="field-block button-height">							
                        <label for="login" class="label"><b>Precio</b></label>
                        <?php echo $this->Form->text('precio', array('class' => 'span12', 'required')); ?>
                        <!--<input type="text" name="login" id="login" value="" class="input">-->
                    </div>
                    
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

                </fieldset>
            </div>

        </form>

    </div>

</section>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 