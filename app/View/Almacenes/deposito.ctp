<section role="main" id="main">
    
    <div class="with-padding">
<h3>
    Fomulario de deposito 
</h3>
<?php 
echo $this->Form->create('Almacenes', array('action' => 'deposito', 'class' => 'columns', 'id' => 'formID'));
echo $this->Form->hidden('Deposito.user_id', array('value'=>$this->Session->read('Auth.User.id')));
?>

<div class="six-columns six-columns-tablet twelve-columns-mobile">
    <h3 class="thin underline">
        Ingresar datos de registro
    </h3>
    <fieldset class="fieldset">
        <legend class="legend">
            Deposito
        </legend>
        <p class="button-height inline-label">
            <label for="validation-select" class="label">
                Quien entrega
            </label>
            <select id="validation-select1" name="data[Deposito][persona_id]" class="select" style="width: 200px">
                <option value="">
                    Seleccione el distribuidor...
                </option>
                <?php foreach ($distribuidores as $d): ?>
                    <option value="<?php echo $d['User']['persona_id'] ?>">
                        <?php echo $d['Persona']['nombre'] . ' ' . $d['Persona']['ap_paterno'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p class="button-height inline-label">
            <label for="input-text" class="label">
                Deposito banco
            </label>
            <?php echo $this->Form->text('Deposito.banco', array('value'=>'0.0', 'class' => 'input validate[custom[number]]')) ?>
        </p>
        <p class="button-height inline-label">
            <label for="input-text" class="label">
                Comprobante deposito
            </label>
            <?php echo $this->Form->text('Deposito.comprobante', array('class' => 'input')) ?>
        </p>
        
       <p class="button-height inline-label">
            <label for="input-text" class="label">
                Monto en efectivo
            </label>
            <?php echo $this->Form->text('Deposito.efectivo', array('value'=>'0.0','class' => 'validate[custom[number]] input')) ?>
        </p>
        <p class="button-height inline-label">
            <label for="input-text" class="label">
                Recibo
            </label>
            <?php echo $this->Form->text('Deposito.recibo', array('class' => 'input')) ?>
        </p>

    </fieldset>
    <div class="button-height">
        <button class="button blue-gradient" type="submit">Registrar</button>
    </div>
</div>
        </div>
</section>
<!-- Sidebar/drop-down menu -->
<?php echo $this->element('sidebar/almacenero'); ?>
<!-- End sidebar/drop-down menu --> 
<script>
    $(document).ready(function() {
        $("#formID").validationEngine();
    });
</script>