<section role="main" id="main">

    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>

    <hgroup id="main-title" class="thin">
        <h1>FORMULARIO DE DEVUELTO <small> HASTA HOY <?php echo date('d-m-Y') ?></small></h1>
    </hgroup>

    <div class="with-padding"> 
        <div class="columns">
            <div class="twelve-columns">
                <h2>DISTRIBUIDOR <?php echo $distribuidor['Persona']['nombre'] . ' ' . $distribuidor['Persona']['ap_paterno'] . ' ' . $distribuidor['Persona']['ap_materno'] ?></h2>
            </div>
        </div>
        <?php echo $this->Form->create('Almacene', array('action' => 'registra_devuelto/'.$distribuidor['Persona']['id'])); ?>
        <table class="simple-table responsive-table">
            <thead>
                <tr>
                    <th>Desde</th>
                    <th>Producto</th>
                    <th>Entregado</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ult_movimientos as $key => $ul): ?>
                  <tr>
                      <td><?php echo $ul['Movimiento']['created']; ?></td>
                      <td><?php echo $ul['Producto']['nombre']; ?></td>
                      <td><?php echo $ul[0]['entregado']; ?></td>
                      <?php echo $this->Form->hidden("Devuelto.$key.producto_id", array('value' => $ul['Producto']['id'])) ?>
                      <?php echo $this->Form->hidden("Devuelto.$key.persona_id", array('value' => $distribuidor['Persona']['id'])) ?>
                      <?php echo $this->Form->hidden("Devuelto.$key.encargado_id", array('value' => $this->Session->read('Auth.User.id'))); ?>
                      <?php echo $this->Form->hidden("Devuelto.$key.total", array('value' => $ul['Movimiento']['total_s'])); ?>
                      <td><?php echo $this->Form->text("Devuelto.$key.cantidad", array('value' => $ul['Movimiento']['total_s'], 'class' => 'input full-width')); ?></td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
        </table><br>
        <div class="columns">
            <div class="six-columns">
                <?php echo $this->Html->link('REGRESAR', $this->request->referer(), array('class' => 'button orange-gradient glossy full-width')); ?>
            </div>
            <div class="six-columns">
                <button type="submit" class="button blue-gradient glossy full-width">REGISTRAR</button>
            </div>
        </div>
        <h3>Listado de ultimos devueltos</h3>
        <table class="simple-table responsive-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($devueltos as $dev): ?>
                <tr>
                    <td><?php echo $dev['Devuelto']['created']?></td>
                    <td>
                        <a href="javascript:" onclick="cargarmodal('<?php echo $this->Html->url(array('action' => 'detalle_devuelto',));?>')">Detalle</a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</section>
<?php echo $this->element('sidebar/administrador'); ?>
