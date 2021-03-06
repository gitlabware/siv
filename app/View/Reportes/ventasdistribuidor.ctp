<?php
App::Import('Model', 'Ventasdistribuidore');
$venta = new Ventasdistribuidore();
$totalventa = 0;
$totalrecargas = 0;
$ventashoy = 0;
?>
<div class="section">

    <hgroup id="main-title" class="thin">
        <h1>Reporte <?php echo date('d-m-Y') ?></h1>
    </hgroup>
    <div id="areaImprimir">

        <div class="title-grid"><span>ventas del dia reporte por 149</span></div>
        <div class="content-gird">
            <table>
                <thead>
                <th scope="col" width="15%">Distribuidor</th>
                <th scope="col" width="15%"><?php echo $distribuidor; ?></th>
                <th scope="col" width="15%">fecha</th>
                <th scope="col" width="15%"><?php echo $desde; ?> - <?php echo $hasta?></th>
                </thead>
            </table>

            <table  id="sorting-example2">
                <thead>
                    <tr style="border: 1px black">
                        <th scope="col" style="text-align: center; border: 1px black">
                            Producto
                        </th>
                        <th scope="col" style="text-align: center; border: 1px black">Cantidad</th>
                        <th scope="col" style="text-align: center; border: 1px black">Subtotal</th>
                    </tr>
                </thead>

                <?php if (!empty($ventas)): ?>

                    <?php
                    $totalventa = 0.00;
                    ?>
                    <?php //debug($ventas)?>
                        
                        <?php foreach ($ventas as $r): ?>
                <tr style="border: 1px black">
                            <th scope="col" style="text-align: center; border: 1px black">
                                <?php echo $r['Producto']['nombre'] ?>
                            </th>
                            <td>
                                <?php echo $r[0]['cantidad']?>
                            </td>
                            <td>
                                <?php echo $r[0]['total']?>
                            </td>
                            
                    </tr>
                        <?php 
                        $totalventa += $r[0]['total'];
                        endforeach; ?>
                        

                    <tr>
                        <th colspan="2">
                            Total en ventas
                        </th>
                        <th>
                        <?php echo $totalventa ?>
                        </th>
                    </tr>
                </table>
               
                <div id='vertical'>
                    <table class="simple-table responsive-table" id="sorting-example2">
                        <thead>
                            <tr>                      

                                <th rowspan="2" scope="col" width="15%">149</th>
                                <th scope="col" width="5%">Producto</th>
                                <th rowspan="2" scope="col" width="5%">Cantidad</th>
                                <th rowspan="2" scope="col" width="5%">Escala</th>
                                <th scope="col" width="2%" style="text-align: right">precio</th>
                                <th scope="col" width="2%"style="text-align: right">Tot Bs</th>
                            </tr>

                        </thead>  
                        <tbody>

                            <?php
                            $totalventa2 = 0;
                            $totalparcial = 0;
                            ?>
                            <?php
                            $totalventa2 = 0;
                            foreach ($ventas as $v):

                                $totalparcial += $v['Ventasdistribuidore']['total'];
                                $totalventa2 += $totalparcial;
                                ?>
                                <tr>
                                    <!--impresion de las ventas-->

                                    <th scope="row">
                                    <?php echo $v['Cliente']['num_registro']; ?><br>
                                        <small><?php echo $v['Cliente']['nombre']; ?></small>
                                    </th>

                                    <td><?php echo $v['Producto']['nombre']; ?></td>
                                    <td><?php echo $v['Ventasdistribuidore']['cantidad']; ?></td>
                                    <td><?php echo $v['Ventasdistribuidore']['escala']; ?></td>
                                    <td scope="col" width="5%" style="text-align: right">
                                    <?php echo number_format($v['Ventasdistribuidore']['precio'], 2, '.', ','); ?></td>
                                    <td style="text-align: right">
                                    <?php echo number_format($v['Ventasdistribuidore']['total'], 2, '.', ','); ?>
                                    </td>
                                <?php $a = 1; ?>
                                </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    
            <?php endif; ?>            

            </div>
            <?php if (!empty($recargas)): ?>
                <h4>Detalle recargas</h4>
                <table class="simple-table responsive-table" id="sorting-example4">
                    <thead>
                        <tr>
                            <th scope="col" width="12%">
                                cliente
                            </th>
                            <th scope="col" width="5%">
                                numero
                            </th>
                            <th scope="col" width="5%">
                                monto
                            </th>
                            <th scope="col" width="2%">
                                porcentaje
                            </th>
                            <th scope="col" width="5%">
                                recarga real
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalrecargas = 0;
                        foreach ($recargas as $r):
                            $totalrecargas += $r['Recarga']['monto'];
                            ?>
                            <tr>
                                <th scope="row">
                                <?php echo $r['Cliente']['num_registro']; ?><br>
                                    <small><?php echo $r['Cliente']['nombre']; ?></small>
                                </th>

                                <td>
                                    <?php echo $r['Recarga']['numero'] ?>
                                </td>
                                <td>
                                    <?php echo $r['Recarga']['monto'] ?>
                                </td>
                                <td>
                                    <?php echo $r['Recarga']['porcentaje'] ?>
                                </td>
                                <td>
                            <?php echo $r['Recarga']['total'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th colspan="2">
                                Total en recargas 
                            </th>
                            <td>
                            <?php echo $totalrecargas ?>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                    </tbody>
                </table>    
                <?php endif; ?>
<?php 
//debug($totalrecargas);
//debug($totalventa);
$ventahoy = 0;
$ventahoy = $totalrecargas + $totalventa;
//debug($ventahoy);
        ?>
            <table>
                <th>
                    Total monto en Bs
                </th>
                <th>
<?php echo number_format($ventahoy, '2', '.', ',') ?>
                </th>
            </table>
        </div>
<?php if (!empty($deposito)): ?>
<h4>
Deposito
</h4>
            <table>
                <tr>
                    <th>
                        Deposito Banco
                    </th>
                    <td>
    <?php echo number_format($deposito['Deposito']['banco'], '2', '.', ',') ?>
                    </td>
                    <td>
                        Comprobante <?php echo $deposito['Deposito']['comprobante'] ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        Deposito Efectivo
                    </th>
                    <td>
    <?php echo number_format($deposito['Deposito']['efectivo'], '2', '.', ',') ?>
                    </td>    
                    <td>
                        Recibo <?php echo $deposito['Deposito']['recibo'] ?>
                    </td>
                </tr>

            </table>

<?php endif; ?>
    </div>
    <div>
        <a href="#" id="btnImprimir">IMPRIMIR</a>
        <a href="<?php echo $this->Html->url(array('action' => 'form')) ?>" style="text-align: left; padding: 0 0 0 5px">ATRAS</a>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#vertical").hide();

        $("#btnVertical").click(function() {
            $("#vertical").toggle('slow');
        });
        $("#btnImprimir").click(function() {
            console.log('<?php echo $this->webroot ?>');
            //printElem({leaveOpen: true, printMode: 'popup'});
            printElem({overrideElementCSS: ["<?php echo $this->webroot ?>/css/imprimetabla.css"]});
        });

    });
    function printElem(options) {
        $('#areaImprimir').printElement(options);
    }
</script>

