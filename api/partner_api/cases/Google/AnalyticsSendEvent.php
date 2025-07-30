<?php

use Backend\imports\AnalyticsEvent\AnalyticsEvent;

header('Content-Type: text/html');

if(false){
$body = file_get_contents('php://input');
if($body == ''){

}
$data = json_decode($body);


$UACode = $data->UACode;
$campaignSource = $data->campaignSource;
$campaignName = $data->campaignName;
$campaignMedium = $data->campaignMedium;
$campaignContent = $data->campaignContent;

$eventCategory = $data->eventCategory;
$eventAction = $data->eventAction;
$eventLabel = $data->eventLabel;

if ($eventLabel != "") {
    $eventLabel = ', "' . $eventLabel . '"';
}


$AnalyticsEvent = new AnalyticsEvent();

if ($eventCategory != '' && $eventAction != '') {
    try{

    $SitioTracking = new \Backend\dto\SitioTracking();

    $SitioTracking->setTabla('transaccion_producto');
    $SitioTracking->setTablaId('1');
    $SitioTracking->setTipo('1');
    $SitioTracking->setTvalue('{}');
    $SitioTracking->setUsucreaId('0');
    $SitioTracking->setUsumodifId('0');



    $SitioTrackingMySqlDAO = new \Backend\mysql\SitioTrackingMySqlDAO();
    $SitioTrackingMySqlDAO->insert($SitioTracking);
    $SitioTrackingMySqlDAO->getTransaction()->commit();

    }catch (Exception $e){

        print_r($e) ;
    }
    $events = new AnalyticsEvent($UACode, 'doradobet.com');
    $events->trackEvent($eventCategory, $eventAction, $eventLabel, 1, $campaignName, $campaignSource, $campaignContent);
}

exit();
?>

<!DOCTYPE html>
<html>
<head>
    <script async src='https://www.google-analytics.com/analytics.js'></script>

    <?

    if ($eventCategory != '' && $eventAction != '') {

        ?>

        <!-- Google Analytics Snippet-->
        <script>
            window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;

            ga('create', '<?=$UACode?>', 'auto');

            ga('set', 'campaignSource', '<?=$campaignSource?>');
            ga('set', 'campaignName', '<?=$campaignName?>');
            ga('set', 'campaignMedium', '<?=$campaignMedium?>');
            ga('set', 'campaignContent', '<?=$campaignContent?>');

            setTimeout(function () {
                var tracker = ga.getAll()[0];
                //tracker.send('pageview');

                tracker.send("event", "<?=$eventCategory?>", "<?=$eventAction?>"<?=$eventLabel?>);
            }, 3000);


        </script>


        <?php
    }
    exit();
    ?>


</head>

</html>
<?php
}
    ?>
