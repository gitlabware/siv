<!-- Main content -->
<div id="main" class="contenedor">
    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>
    <hgroup id="main-title" class="thin">
        <h1>Tienda <?php echo $this->Session->read("Auth.User.Sucursal.nombre") ?></h1>
    </hgroup>
    <div class="with-padding">
        <div class="columns">
            <div class="eight-columns twelve-columns-mobile twelve-columns-tablet">
                <h4>Productos</h4>

                <div class="standard-tabs margin-bottom tabs-active">

                    <ul class="tabs">
                        <?php foreach ($categorias as $c): ?>
                          <!--<li class="active"><a href="#tab-1">Selected tab</a></li>-->
                          <li>
                              <a href="#tab-<?php echo $c['Tiposproducto']['nombre']; ?>">
                                  <?php echo $c['Tiposproducto']['nombre']; ?>
                              </a>
                          </li>
                          <!--                        <li><a href="#tab-3">Another tab</a></li>   -->
                        <?php endforeach; ?>
                    </ul>

                    <div class="tabs-content">
                        <?php foreach ($categorias as $c): ?>
                          <?php
                          $color = 'white';
                          if (!empty($c['Tiposproducto']['desc'])) {
                            $color = $c['Tiposproducto']['desc'];
                          }
                          ?>
                          <div id="tab-<?php echo $c['Tiposproducto']['nombre']; ?>" class="with-padding" style="display: none;">
                              <?php foreach ($productos as $p): ?>
                                <?php if ($c['Producto']['tiposproducto_id'] == $p['Producto']['tiposproducto_id']): ?> 

                                  <?php
                                  $nombre = $p['Producto']['nombre'];
                                  echo $this->Ajax->link(
                                    $nombre, array(
                                    'controller' => 'Tiendas',
                                    'action' => 'ajaxpidetienda', $p['Productosprecio']['id'], $p['Producto']['id'], $p['Productosprecio']['precio']), array(
                                    'update' => 'cargaDatos',
                                    'escape' => false,
                                    'class' => "button $color-gradient glossy",
                                    'style' => 'margin-bottom: 10px; text-transform: uppercase; font-size: 150%; padding: 10px 15px 10px 15px'
                                    )
                                  );
                                  ?>
                                <?php endif; ?>
                              <?php endforeach; ?>
                          </div>
                        <?php endforeach; ?>
                    </div>
                </div>	
            </div>
            <div class="four-columns new-row-mobile new-row-tablet twelve-columns-mobile twelve-columns-tablet">
                <div class="simpler">                     
                    <div id="cargaDatos">
                        <p>Seleccione un Producto</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $this->webroot; ?>js/developr.tabs.js"></script>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/tienda'); ?>
<!-- End sidebar/drop-down menu --> 