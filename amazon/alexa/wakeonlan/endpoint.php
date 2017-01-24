<?php
/**
 * Created by PhpStorm.
 * User: Sese
 * Date: 24-Jan-17
 * Time: 17:37
 */
function wol($broadcast, $port, $mac)
{
    $mac_array = explode(':', $mac);

    $hwaddr = '';

    foreach ($mac_array AS $octet) {
        $hwaddr .= chr(hexdec($octet));
    }

    // Create Magic Packet

    $packet = '';
    for ($i = 1; $i <= 6; $i++) {
        $packet .= chr(255);
    }

    for ($i = 1; $i <= 16; $i++) {
        $packet .= $hwaddr;
    }

    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if ($sock) {
        $options = socket_set_option($sock, 1, 6, true);

        if ($options >= 0) {
            $e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, $port);
            socket_close($sock);
        }
    }
}

# https://developer.amazon.com/public/solutions/alexa/alexa-skills-kit/docs/alexa-skills-kit-interface-reference
header("HTTP/1.1 200 OK");
header("Content-Type: application/json;charset=UTF-8");

$appId = "amzn1.ask.skill.0d283b5a-507c-4b30-9e55-92dc0e8aa0eb";

$success_responses = [
    "wird gemacht",
    "wird erledigt",
    "okay",
    "zu befehl",
    "wie du wünscht",
    "glaubst du wirklich dieser schrott wird noch starten? wir werden ja sehen",
    "computer wird gestartet"
];

$request = $entityBody = file_get_contents('php://input');
#exit($request);
$request = json_decode($request, true);

$validRequest = ($appId == $request["session"]["application"]["applicationId"]);

$output = (object)null;
$response = (object)null;
$outputSpeech = (object)null;
$card = (object)null;

if ($validRequest && $request["request"]["type"] == "IntentRequest") {
    $accessToken = $request["session"]["user"]["accessToken"];
    if (!isset($accessToken) || $accessToken == null) {
        $outputSpeech->type = "PlainText";
        $outputSpeech->text = "Bitte verknüpfe deinen Computer mithilfe der Alexa App";
        $card->type = "LinkAccount";
        $response->card = $card;
    } else {
        $dbpw = "HbJri8d0%6bg481uV8mo2#2g*Wuc300";
        $dbuser = "alexa";
        $dbhost = "localhost";

        $mysqli = new mysqli($dbhost, $dbuser, $dbpw, 'amazon_alexa');

        $query = $mysqli->query("SELECT * FROM wakeonlan WHERE token = \"$accessToken\"");
        $row = $query->fetch_assoc();

        $ip = $row["ip"];
        $port = $row["port"];
        $mac = $row["mac"];

        if(empty($ip) || empty($port) || empty($mac)){
            $outputSpeech->type = "PlainText";
            $outputSpeech->text = "Etwas hat nicht richtig funktioniert. bitte konfiguriere den skill in deiner alexa app neu";
            $card->type = "LinkAccount";
            $response->card = $card;
        }
        else {
            wol($ip, $port, $mac);
            $outputSpeech->type = "PlainText";
            $outputSpeech->text = $success_responses[array_rand($success_responses)];
        }
    }
    $response->outputSpeech = $outputSpeech;
} else {
    header("HTTP/1.1 500");
}

$output->authorized = $validRequest;
$output->version = "0.1";
$output->response = $response;
echo json_encode($output);