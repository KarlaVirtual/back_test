<?php include("../demo/MasterPage.php") ?>
  <div class="bootstrap pspagoefectivo">
      <div class="page-head">
          <ul class="breadcrumb page-breadcrumb">
              <li class="breadcrumb-current">
                  <i class="icon-AdminParentModules"></i>
              </li>
          </ul>
          <h2 class="page-title">
              Generar Cip
          </h2>

      </div>
  </div>
  <div class="bootstrap pspagoefectivo">
<?php require_once('../lib_pagoefectivo/code/configpe.php'); ?>
<!-- PagoEfectivo Estilos -->
    <link rel="stylesheet" type="text/css" href="../lib_pagoefectivo/js/fancybox/jquery.fancybox.css?v=2.0.6" media="screen" />
    <link href="../lib_pagoefectivo/css/estilos.css?" type="text/css" rel="stylesheet" media="screen" />
<!-- Add jQuery library -->
    <script type="text/javascript" src="../lib_pagoefectivo/js/jquery.min.js"></script>
<!-- PagoEfectivo Librerías -->
	<script type="text/javascript" src="../lib_pagoefectivo/js/fancybox/jquery.fancybox.js"></script>
	<script type="text/javascript" src="../lib_pagoefectivo/js/swfobject/swfobject.js"></script>
    <script type="text/javascript" src="../lib_pagoefectivo/js/JScript.js"></script>
<br /><br /><br />
<div class="pe_pago">
    <form action="cip.php" id="frmPago" name="frmPago" method="post">
        <div class="pe_detail">
            <h3>
                Detalle de compra
                <a class="pe_inline_mdl cboxElement" href="#mira-tus-avisos"></a>
            </h3>
            <div class="pe_content" style="text-align: left;">
                <ul>
                    <li>Detalle del producto</li>
                    <li>comentarios adicionales</li>
                </ul>
                 <p class="pe_total">Total: <span>S/. <span id="pe_s-total"><?=number_format(2, 2, '.', '');?></span></span></p>
                <input type="hidden" name="t-total" id="pe_t-total" value="<?=number_format(2, 2, '.', '');?>">
                <p class="pe_obs">El precio incluye IGV. La compra, en la fecha indicada, est&aacute; sujeta al pago antes de la fecha de cierre.</p>
            </div>
        </div>
        <div class="pe_payment">
            <h3>M&eacute;todo de pago</h3>
            <div class="pe_option_pago">
                    <div class="pe_title">
                        <span class="pe_inline"><strong>Pagar sin Tarjeta de Cr&eacute;dito</strong></span>
                        <a class="pe_pregunta fancybox" title="&iquest;Qu&eacute; es PagoEfectivo?" href="#pagoef1"></a> <br/><br/>
                    </div>
                    <input type="radio" style="float:left;"  value="2" name="metodopago" checked="checked" id="form-validation-field-1">
                    <div style="float: left; width: 160px;"><img width="140" height="45" src="../lib_pagoefectivo/images/uno.png"></div>
                    <div style="float:left;text-align: justify;width: 350px;">
					<strong>Tranferencia bancaria (PagoEfectivo)</strong><br/>
                        Paga a través de tu banca por internet en BBVA, BCP, INTERBANK, SCOTIABANK y BANBIF. Debítalo de tu cuenta o cárgalo a tu tarjeta de crédito asociada. <br />
                            <span style="font-size:10px;">
                			    <a class="iframe" id="fancy" title="&iquest;C&oacute;mo functiona PagoEfectivo?"  style="color:#006999; text-decoration:none;" href="<?php echo PE_URL_POPUP;?>">&iquest;C&oacute;mo functiona PagoEfectivo?</a>
                            </span>
                        </div>
                    <div class="clear"></div> <br />
                    
            </div>
			  <div class="pe_option_pago">
                    <div class="pe_title">
                     
                    </div>
                    <input type="radio" style="float:left;"  value="2" name="metodopago"  id="form-validation-field-1">
                    <div style="float: left; width: 160px;"><img width="140" height="45" src="../lib_pagoefectivo/images/dos.png"></div>
                    <div style="float:left;text-align: justify;width: 350px;">
					<strong>Depósito en efectivo (PagoEfectivo)</strong> <br/>
                        Acércate a cualquier punto del BBVA, BCP, INTERBANK, SCOTIABANK y BANBIF. Agentes corresponsales KASNET, WESTERUNION – Pago de Servicios y FULLCARGA. <br />
                            <span style="font-size:10px;">
                			    <a class="iframe" id="fancy" title="&iquest;C&oacute;mo functiona PagoEfectivo?"  style="color:#006999; text-decoration:none;" href="<?php echo PE_URL_POPUP;?>">&iquest;C&oacute;mo functiona PagoEfectivo?</a>
                            </span>
                        </div>
                    <div class="clear"></div> <br />
                    <div class="pe_total">
                        <input type="submit" id="Send" value="Pagar" class="pe_continuar_btn1" name="Send">
                     </div>
            </div>
          </div>
    </form>
</div>
<?php include("../demo/FooterPage.php") ?>