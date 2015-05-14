<section role="main" id="main">
    <hgroup id="main-title" class="thin">
        <h1>VENTA A <?php echo strtoupper($this->request->data['Tienda']['cliente']); ?></h1>
    </hgroup>
    <div class="with-padding"> 
        <div class="columns">
            <div class="ten-columns">
                <?php foreach ($celulares as $cel): ?>
                  <p class="block-label button-height">
                  <div class="columns">
                      <div class="six-columns">
                          <p class="block-label button-height">
                              <img src="<?php echo '../'.$cel['Producto']['url_imagen']?>" alt="Smiley face" height="200" width="200">
                          </p>
                      </div>
                      <div class="six-columns">
                          <p class="block-label button-height">
                          <h4><?php echo strtoupper($cel['Producto']['nombre']);?></h4>
                          <span><?php echo $cel['Marca']['nombre']?></span>
                          </p>
                      </div>
                  </div>
                  </p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>	

<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/administrador'); ?>
<!-- End sidebar/drop-down menu --> 
