<?php
use SendGrid\Mail\Mail;


/**
 * Clase 'CronJobCampaignsEmailOptimove'
 *
 *
 *
 *
 * Ejemplo de uso:
 *
 *
 *
 * @package ninguno
 * @author Daniel Tamayo <it@virtualsoft.tech>
 * @version ninguna
 * @access public
 * @see no
 *
 */
class CronJobCampaignsEmailOptimove
{


    public function __construct()
    {
    }

    public function execute()
    {
        include_once(__DIR__ . "/../src/imports/SendGridClient/Client.php");
        include_once(__DIR__ . "/../src/imports/SendGridClient/Response.php");
        include_once(__DIR__ . "/../src/imports/SendGrid/SendGrid.php");
        include_once(__DIR__ . "/../src/imports/SendGrid/loader.php");


// Function to make API requests
        function makeApiRequest($url, $headers)
        {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers
            ]);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                echo 'Error:' . curl_error($curl);
            }
            curl_close($curl);
            return json_decode($response, true);
        }

        ini_set('memory_limit', '-1');

// Get yesterday's date
        $date = date('Y-m-d', strtotime('-1 day'));


        $keys = [
            'DoradoBet Peru' =>
                array(
                    "email" => "contacto@doradobet.com",
                    "key" => "20145af9840fe2cd1f4576dcbf45a9d4cd46d932957a7583f092"
                ), // DoradoBet Peru
            'DoradoBet Costa Rica' =>
                array(
                    "email" => "servicioalcliente@doradobet.cr",
                    "key" => "8a5d14cb0355b3ac875cbbc605c3b84f98dd825b91b3a89bc7ff"
                ),  // DoradoBet Costa Rica
            'DoradoBet Ecuador' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "65ea90e0072382db05848e5e9c9702718c6c6160c313c297814f"
                ),  // DoradoBet Ecuador
            'DoradoBet Chile' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "154ce95634179c7b633c0d175e4881bda7ce9df8cfc58f646c75"
                ),  // DoradoBet Chile
            'DoradoBet Guatemala' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "07c8436574d1d4bfb8d3a48706df34adf2c218743641f389acbf"
                ),  // DoradoBet Guatemala
            'DoradoBet Nicaragua' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "0b620f7bbe572bc61241317728891c3d3990f50a236070c756d8"
                ),  // DoradoBet Nicaragua
            'DoradoBet Salvador' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "d763e0de6a98c5e8c7e32ea2b9aebc8dd1c5dde4c3e6e437fde7"
                ),   // DoradoBet Salvador
            'Ecuabet Ecuador' =>
                array(
                    "email" => "nelson.bermudez@ecuabet.com",
                    "key" => "cc1f28850a061709814fedbcfcaad24c7b0dcaada0ab8c1542c0"
                ), // Ecuabet Ecuador
            'Lotosport Brasil' =>
                array(
                    "email" => "gilson.filardi@lotosports.bet",
                    "key" => "1be70527b25aa89951b054a3478c348696c7b9c50151609dca24"
                ), // Lotosport Brasil
            'Paniplay Honduras' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "beb3d10efde193294a2c36430866e79d8462383facb425fa8b24"
                ), // Paniplay Honduras
            'GangaBet' =>
                array(
                    "email" => "manuela.espinosa@virtualsoft.tech",
                    "key" => "89af96ff812a3b74f747929455030cf3d1cab064d7f536fa8dce"
                ), // GangaBet
        ];
        foreach ($keys as $name => $apiKey2) {
            $email2 = $apiKey2['email'];
            $apiKey = $apiKey2['key'];
            $headers = [
                'x-api-key: ' . $apiKey,
                'Cookie: incap_ses_8224_2753802=2zz+Iliwb2k868JnEIUhcrRU3mUAAAAAL5ACgeBLnNIqnUsvMV8mug==; visid_incap_2753802=DKUIMw3jTWS7oJePGPMuQ4BG3mUAAAAAQUIPAAAAAADaR4KtonUR8H82F6X6p4L0'
            ];

// Step 1: Get Executed Campaign Details
            $campaignsUrl = "https://api4.optimove.net/current/actions/GetExecutedCampaignDetails?Date=$date";
            $campaigns = makeApiRequest($campaignsUrl, $headers);
            if (!is_array($campaigns)) {
                die("Failed to retrieve campaign details.");
            }

            $campaignIds = array_column($campaigns, 'CampaignID');
            $campaignIdsString = array_unique($campaignIds);
            $allCampaignDetails = array();

            foreach ($campaignIdsString as $item) {
                // Step 2: Get Campaign Details for all campaigns in a single request
                $campaignDetailsUrl = "https://api4.optimove.net/Actions/GetCampaignDetails?CampaignID=$item";
                $arrayCampaing = makeApiRequest($campaignDetailsUrl, $headers);
                $arrayCampaing['CampaignID'] = $item;
                array_push($allCampaignDetails, $arrayCampaing);
            }


            $customerExecutionDetails = [];
            $allActionIds = [];
            $allChannelIds = [];

            foreach ($allCampaignDetails as $campaignDetails) {

                $campaignId = $campaignDetails['CampaignID'];
                $channelId = $campaignDetails['Channels'][0]['Id'];
                $customerExecutionUrl = "https://api4.optimove.net/current/customers/GetCustomerExecutionDetailsByCampaign?CampaignID=$campaignId&ChannelID=$channelId&top=2000000";
                $customerExecutionDetails[$campaignId] = makeApiRequest($customerExecutionUrl, $headers);
                foreach ($customerExecutionDetails[$campaignId] as $customerExecution) {
                    $allActionIds[] = $customerExecution['ActionID'];
                    $allChannelIds[] = $channelId;
                }
            }

            $allActionIds = array_unique($allActionIds);
            $allChannelIds = array_unique($allChannelIds);

            $actionNames = [];
            $channelTemplates = [];

// Get all action names
            foreach ($allActionIds as $actionId) {
                $actionNameUrl = "https://api4.optimove.net/Actions/GetActionName?ActionID=$actionId";
                $actionNameResponse = makeApiRequest($actionNameUrl, $headers);
                if (is_array($actionNameResponse) && isset($actionNameResponse['ActionName'])) {
                    $actionNames[$actionId] = $actionNameResponse['ActionName'];
                } else {
                    $actionNames[$actionId] = 'Unknown';
                }
            }

// Get execution channels
            $executionChannelsUrl = "https://api4.optimove.net/Actions/GetExecutionChannels";
            $executionChannels = makeApiRequest($executionChannelsUrl, $headers);
            if (!is_array($executionChannels)) {
                die("Failed to retrieve execution channels.");
            }

            $channelNames = [];
            foreach ($executionChannels as $channel) {
                $channelNames[$channel['ChannelId']] = $channel['ChannelName'];
            }

// Get all channel templates
            foreach ($allChannelIds as $channelId) {
                $channelTemplatesUrl = "https://api4.optimove.net/Integrations/GetChannelTemplates?ChannelID=$channelId";
                $templates = makeApiRequest($channelTemplatesUrl, $headers);
                if (is_array($templates)) {
                    foreach ($templates as $template) {
                        $channelTemplates[$channelId][$template['TemplateID']] = $template['TemplateName'];
                    }
                }
            }

            $allData = [];
            foreach ($allCampaignDetails as $campaignDetails) {
                $campaignId = $campaignDetails['CampaignID'];
                $channelId = $campaignDetails['Channels'][0]['Id'];
                $channelName = $channelNames[$channelId] ?? 'Unknown';

                foreach ($customerExecutionDetails[$campaignId] as $customerExecution) {
                    $actionId = $customerExecution['ActionID'];
                    $actionName = $actionNames[$actionId] ?? 'Unknown';
                    $templateId = $customerExecution['TemplateID'];
                    $templateName = $channelTemplates[$channelId][$templateId] ?? 'Unknown';

                    $allData[] = [
                        'CUSTOMER_ID' => $customerExecution['CustomerID'],
                        'Action ID' => $actionName,
                        'Channel_Name' => $channelName,
                        'TEMPLATE_NAME' => $templateName,
                        'Promo Code' => $customerExecution['PromoCode']
                    ];
                }
            }

// Create CSV file
            $csvFile = fopen('campaign_details.csv', 'w');
            fputcsv($csvFile, ['CUSTOMER_ID', 'Action ID', 'Channel_Name', 'TEMPLATE_NAME', 'Promo Code']);
            foreach ($allData as $row) {
                fputcsv($csvFile, $row);
            }
            fclose($csvFile);

// Send email using SendGrid
            $sendGridApiKey = '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';
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom("noreply@virtualsoft.tech", "Virtualsoft");
            $email->setSubject("Campaign Optimove Details CSV - " . $name);
//$email->addTo("julian.munoz@virtualsoft.tech", "Julian");
            $email->addTo($email2, $email2);
            $email->addContent("text/plain", "Please find the attached campaign details.");
            $email->addAttachment(
                base64_encode(file_get_contents('campaign_details.csv')),
                "text/csv",
                "campaign_details.csv",
                "attachment"
            );

            $sendgrid = new \SendGrid($sendGridApiKey);
            try {
                $response = $sendgrid->send($email);
                echo "Email sent successfully!";
            } catch (Exception $e) {
                print_r($e);
                echo 'Caught exception: ' . $e->getMessage() . "\n";
            }
        }

    }
}