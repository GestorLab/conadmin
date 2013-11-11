<?php
require_once("classes/image-qrcode/Image/QRCode.php");
  $qr = new Image_QRCode();
    
    $options = array(
      "output_type" => "jpeg"
    );
    $gd_object = $qr->makeCode("http://intranet.cntsistemas.com.br/modulos/administrativo/index.php?url=http://intranet.cntsistemas.com.br/modulos/administrativo/cadastro_help_desk.php?IdTicket=1734", $options);
  ?>