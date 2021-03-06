
<?php 
App::Import('Model','Movimiento');
App::Import('Model','Ventastienda');
App::Import('Model','Productosprecio');
$venta = new Ventastienda();
$mov = new Movimiento();
$valores = new Productosprecio();
?>
<script>
    function sacatotal() {
        var totalpago = 0;
        //console.log($("#grandTotal").text());
        totalpago = Number($("#grandTotal").text()) + Number($("#totalrecargas").text());
        //console.log('suma total ' + totalpago);
        $("#total").html(totalpago);
    }
</script>

<section role="main" id="main">

    <div class="with-padding">
        <div class="grid-4">
            <div class="title-grid">
                <h6>Reporte de venta por producto</h6>
            </div>
            <div class="content-gird">
            <!--*************************************************************************************************************************************
                     //botoneria-->
            <div style="position: absolute; padding: 6px 0px 4px 893px;">
            
            <ul style="list-style-type:none; ">
                <li style="float: left; padding: 1px 10px 5px 1px;"><a href="algun_sitio_1">
                <a href="#" id="btnImprimir"><?php echo $this->Html->image('print.png')?></a>
                
                </li>
                
                <li style="float:left;">
                <?php 
                echo $this->Html->image("back.png", array(
                "alt" => "Volver",
                'title'=>'Retornar',
                'onclick'=>'javascript:history.go(-1)',
                 'url'=>array('action'=>'#')
                )); ?>
                
                </li>
   
            </ul>
            </div>
            <br />
            <!--/*************************************************************************************************************************************-->
              
              <div id="areaImprimir">
                    <?php $id = $precios['0']['Producto']['id']; ?>
                   
                    <table class="titulos" >
                        <tr>
                            <th class="txt">Distribuidor:</th>
                            <td class="txt"><?php echo $nombre; ?></td>
                            <th>Fecha: </th>
                            <td><?php echo $fecha; ?></td>
                            <th>Hora: </th>
                            <td><?php echo $hora; ?></td>
                        </tr>
                       
                    </table>
                    
                    <?php 
                    $totalgeneral =0;
                    $totalventabs = 0;
                    foreach ($rows as $r): 
                    $idProducto = $r['Producto']['id'];
                    $i=1;
                    ?>
                    
                    <table width="200" class="vertical">
                    <thead>
                     <tr>
                        <th rowspan="2">PRODUCTO</th>
                        <th rowspan="2">SALDO</th>
                        <th rowspan="2">INGRESO</th>
                        <th colspan="3" style="text-align: center;">VENTAS</th>
                        <th rowspan="2">SALDO</th>
                        <th rowspan="2">TOTAL</th>
                      </tr>
                       <tr>
                       <?php $precios = $valores->find('all', array(
                       'conditions'=>array('Productosprecio.producto_id'=>$idProducto, 'Productosprecio.tipousuario_id'=>2),
                       'recursive'=>-1)) ?>
                        <?php 
                        $cantidadcolumnas = 0;
                        foreach($precios as $val):
                        
                        ?>
                        <th>
                        
                        <?php echo $val['Productosprecio']['precio']?>
                        </th>
                        <?php 
                        $cantidadcolumnas++;
                        endforeach;
                        //debug($cantidadcolumnas);
                        if($cantidadcolumnas != 3){
                            $columnas = 3 - $cantidadcolumnas;
                            for($i=0;$i<$columnas;$i++){
                                echo "<th>&nbsp;</th>";
                            }
                        }
                            
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo $r['Producto']['nombre']?></td>
                        <?php
                        $saldo = $mov->find('first', array(
                                                   'conditions'=>array(
                                                       'Movimiento.producto_id'=>$idProducto,
                                                       'Movimiento.almacene_id'=>$idAlmacen, 
                                                       'Movimiento.ingreso !='=>'0',
                                                       ),
                                                       
                                                   'fields'=>array(
                                                       'MAX(Movimiento.id) as id', 
                                                       )));
                        //debug($saldo);
                            $dato = $mov->find('first', array(
                                                    'conditions'=>array('Movimiento.id'=>$saldo['0']['id']),
                                                    'recursive'=>-1));
                            //debug($dato);
                            if(!empty($dato)){
                                $ingreso = $dato['Movimiento']['ingreso'];
                                //$saldo = $dato['Movimiento']['ingreso'] - $dato['Movimiento']['saldo'];
                                $saldo =  $dato['Movimiento']['saldo'];
                                $total = $dato['Movimiento']['total'];
                            }else{
                               $saldo = 0;
                               $ingreso = 0;
                               $total = 0;
                            }
                        ?>
                        <td><?php echo $saldo?></td>
                        <td><?php echo $ingreso?></td>
                        <?php 
                        $totalcantidad=0;
                        $totalbs = 0;
                        $cantidadcolumnas =0;
                        foreach($precios as $val):?>
                        <td>
                        <?php 
                        $ventad = $venta->find('all', array(
                                                        'conditions'=>array(
                                                            'Ventastienda.producto_id'=>$idProducto,
                                                            'Ventastienda.precio'=>$val['Productosprecio']['precio'], 
                                                            'Ventastienda.created'=>$fecha),
                                                        'fields'=>array('sum(Ventastienda.cantidad) as cantidad', 'sum(Ventastienda.total) as total'),
                                                        'group'=>array('Ventastienda.producto_id')
                                                        ));
                            
                            $cantidad = $ventad['0']['0']['cantidad'];
                            
                            $totalcantidad += $cantidad;
                            $totalbs += $ventad['0']['0']['total'];
                            echo $cantidad;
                        ?>
                        </td>
                        <?php 
                        $cantidadcolumnas++;
                        endforeach;
                        //debug($cantidadcolumnas);
                        ?>
                        <?php 
                        if($cantidadcolumnas != 3){
                            $columnas = 3 - $cantidadcolumnas;
                            //debug($columnas);
                            for($i=0;$i<$columnas;$i++){
                                echo "<td>&nbsp;</td>";
                            }
                        }
                            
                        ?>
                        <td>
                        <?php
                        $saldoactual = $saldo  - $totalcantidad;
                        echo $total;
                        ?>
                        </td>
                        <td style="text-align: right; padding: 0 5px 0 0;">
                        <?php echo number_format($totalbs,2,',', '.'); 
                        $totalventabs += $totalbs;?></td>
                      </tr>
                      
                    </tbody>
                   
                    </table>
                    
                   <?php endforeach;?>
                   <table class="vertical">
                     <tfoot>
                    <tr>
                       <th style="width: 72%; text-align: right; padding: 2px 5px; 2px 0">
                       Total venta
                       </th>
                       <th style="text-align: right; padding: 0 5px 0 0;">
                       <?php echo number_format($totalventabs, 2, ',', '.')?>
                       </th>
                    </tr>
                    </tfoot>
                    </table>
                     <?php if (!empty($recargas)): ?>
                <h6 style="margin-bottom: 10px;
                margin-top: 11px;">Detalle recargas</h6>
                <table class="vertical" id="sorting-example4">
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
                                <?php echo $r['Cliente']['num_registro']; ?><br/>
                                <small><?php echo $r['Cliente']['nombre']; ?></small>
                                <small>
                                Hrs:
                                <?php
                                $hora = explode(" ", $r['Recarga']['created']); 
                                echo $hora[1]?>
                                </small>
                                </th>

                                <td>
                                    <?php echo $r['Recarga']['numero'] ?>
                                </td>
                                <td style="text-align: right; padding-right: 10px;">
                                    <?php echo number_format($r['Recarga']['monto'],2,',', '.') ?>
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
                            <th style="text-align: right; padding-right: 10px;">
                            <?php echo number_format($totalrecargas,2,',', '.') ?>
                            </th>
                            <th>
                                &nbsp;
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </tbody>
                </table>    
                <?php endif; ?>
<?php
                //debug($totalrecargas);
                //debug($totalventa);
                $ventahoy = 0;
                $ventahoy = $totalrecargas + $totalventabs;
                //debug($ventahoy);

?>
            <table class="vertical">
                <th style="width: 72%;" colspan="4">
                    Total Bs ventas y recargas
                </th>
                <th>
                    <?php echo number_format($ventahoy, '2', '.', ',') ?>
                </th>
            </table>
            <?php if(!empty($deposito)):?>
            <h6 style="margin-bottom: 10px;
                margin-top: 11px;">Deposito</h6>
            <table class="vertical">
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
                <tr>
                    <th colspan="2">
                        Total depositado entregado 
                    </th>
                    <th>
                    <?php echo number_format($deposito['Deposito']['total'], '2', '.', ',') ?>
                    </th>    
                    
                    
                </tr>
            </table>
            <?php endif;?>
                </div>

            </div>

                     
        </div>
    </div>

</section>
<!-- Sidebar/drop-down menu -->
<?php //echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu -->
<script>
    $(document).ready(function() {
       
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