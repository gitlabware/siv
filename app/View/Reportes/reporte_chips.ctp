<div id="main" class="contenedor">
    <noscript class="message black-gradient simpler">Your browser does not support JavaScript! Some features won't work as expected...</noscript>
    <hgroup id="main-title" class="thin">
        <h1>REPORTE DE CHIP'S</h1>
    </hgroup>
    <div class="with-padding">
        <?php echo $this->Form->create(NULL, array('url' => array('controller' => 'Reportes','action' => 'reporte_chips'))); ?>
        
        <div class="columns ocultar_impresion">
            <div class="four-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Fecha Inicial</label>
                    <?php echo $this->Form->date('Dato.fecha_ini', array('class' => 'full-width input')); ?>
                </p>
            </div>
            <div class="four-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">Fecha Final</label>
                    <?php echo $this->Form->date('Dato.fecha_fin', array('class' => 'full-width input')); ?>
                </p>
            </div>
            <div class="four-columns">
                <p class="block-label button-height">
                    <label for="block-label-1" class="label">&nbsp;</label>
                    <button class="button green-gradient full-width" type="submit">GENERAR</button>
                </p>
            </div>
        </div>
        <br>
        <?php echo $this->Form->end(); ?>
        <table class="table responsive-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Distribuidor</th>
                    <th >#Entregados</th>
                    <th >#Activos</th>
                    <th >#Quedan</th>
                </tr>
            </thead>          

            <tbody>

            </tbody>
        </table> 
    </div>
</div>

