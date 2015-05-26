<section role="main" id="main">
    <hgroup id="main-title" class="thin" align="center">
        <h1>VENTA A</h1>
    </hgroup>
    <div class="with-padding"> 
        <div class="columns">
            <div class="three-columns hidden-phone">
              
            </div>
            <div class="six-columns twelve-columns-mobile">
                <?php echo $this->Form->create('Tienda', array('action' => 'registra_venta_celu_2')); ?>
                  <p class="block-label button-height">
                  <fieldset class="fieldset">
                      <p class="block-label button-height">
                      <div class="columns">
                          <div class="twelve-columns">
                              <p class="button-height inline-label">
                              <h4>dfsfdsf</h4>
                              </p>
                              <p class="button-height inline-label">
                                  <label  class="label">gggggg</label>
                              </p>
                              <p class="button-height inline-label">
                                  <label class="label">Precio: yyyyyy</label>
                              </p>
                              
                              <p class="button-height inline-label">
                                  <label class="label">Numero Serie</label>
                                  <?php echo $this->Form->text("Ventascelulare.num_serie", array('class' => 'input')); ?>
                              </p>
                              <p class="button-height inline-label">
                                  <label class="label">Imei</label>
                                  <?php echo $this->Form->text("Ventascelulare.imei", array('class' => 'input')); ?>
                              </p>
                          </div>
                      </div>
                      </p>
                  </fieldset>
                  </p>
                <p class="block-label button-height">
                    <button type="submit" class="button anthracite-gradient glossy full-width">REGISTRAR VENTA</button>
                </p>
            </div>
            <div class="three-columns hidden-phone">
              
            </div>
        </div>
    </div>
</section>	

<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/distribuidor'); ?>
<!-- End sidebar/drop-down menu --> 
