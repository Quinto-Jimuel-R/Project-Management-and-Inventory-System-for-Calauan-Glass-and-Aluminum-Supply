<?php 

    use Infobip\Configuration;
    use Infobip\Api\SmsApi;
    use Infobip\Model\SmsDestination;
    use Infobip\Model\SmsTextualMessage;
    use Infobip\Model\SmsAdvancedTextualRequest;

    require 'vendor/autoload.php';
    require './database.php';

    $apiURL = "https://d9k2pv.api.infobip.com";
    $apiKEY = "7b2ad7ce3817c971ad689f60e32de8c6-ed73c164-f2b0-4b1d-9b15-167f5f0f56cb";

    $config = new Configuration(host: $apiURL, apiKey: $apiKEY);
    $api = new SmsApi(config: $config);

    $phoneNumber = '+63' . substr($phoneNumber, -10);

    $destination = new SmsDestination(to: $phoneNumber);
    
    $OTP = new SmsTextualMessage(
        destinations: [$destination],
        text: "Request Verification Code: " . $otp,
        from: "CGAS"
    );

    $request = new SmsAdvancedTextualRequest(messages: [$OTP]);

    try {
        
        $response = $api->sendSmsMessage($request);

    } catch (Exception $e) {

        echo "An error occurred: " . $e->getMessage();

    }
    
?>