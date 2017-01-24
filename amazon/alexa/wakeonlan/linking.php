<?php
if (empty($_POST["ip"]) || empty($_POST["mac"]) || empty($_POST["port"])) {
    ?>
    <html>
    <head>
        <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1,user-scalable=yes">
        <style>
            html, body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                background: #efefef;
            }

            input {
                margin: 8px;
            }

            #container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-top: -10%;
                height: 100%;
            }

            #inner {
                background: #fff;
                margin: 16px;
                padding: 16px;
                border-radius: 5px;
            }

            .centercontent {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            input[type=text], input[type=password], textarea {
                -webkit-transition: all 0.30s ease-in-out;
                -moz-transition: all 0.30s ease-in-out;
                -ms-transition: all 0.30s ease-in-out;
                -o-transition: all 0.30s ease-in-out;
                outline: none;
                padding: 3px 0px 3px 3px;
                margin: 5px 1px 3px 0px;
                border: 1px solid #DDDDDD;
            }

            button {
                background: #3498db;
                background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
                background-image: -moz-linear-gradient(top, #3498db, #2980b9);
                background-image: -ms-linear-gradient(top, #3498db, #2980b9);
                background-image: -o-linear-gradient(top, #3498db, #2980b9);
                background-image: linear-gradient(to bottom, #3498db, #2980b9);
                border-radius: 5px;
                color: #ffffff;
                padding: 10px 20px 10px 20px;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
    <div id="container">
        <div id="inner" class="centercontent">
            <center>Please provide all neccessary informations to wake your computer.</center>
            <br/><br/>
            <form class="centercontent" action="linking" method="POST" style="max-width: 75%;">
                <input name="state" type="hidden" value="<?php echo $_GET["state"] . $_POST["state"]; ?>">
                <input type="text" name="ip" placeholder="IP address" value="<?php
                echo (isset($_GET["ip"]) || isset($_POST["ip"]))
                    ? $_GET["ip"] . $_POST["ip"]
                    : $_SERVER['REMOTE_ADDR'];
                ?>"><br/>
                <input type="text" name="port" placeholder="WOL port"
                       value="<?php echo $_GET["port"] . $_POST["port"]; ?>"><br/>
                <input type="text" name="mac" placeholder="Computer Mac address"
                       value="<?php echo $_GET["mac"] . $_POST["mac"]; ?>"><br/>
                <button>Submit</button>
            </form>
            <span style="font-size: 10px">&copy; 2017 DeepSpace Development</span>
        </div>
    </div>
    </body>
    </html>
    <?php
    exit();
}

$dbpw = "HbJri8d0%6bg481uV8mo2#2g*Wuc300";
$dbuser = "alexa";
$dbhost = "localhost";

$mysqli = new mysqli($dbhost, $dbuser, $dbpw, 'amazon_alexa');

$state = $_POST["state"];
$ip = $_POST["ip"];
$port = $_POST["port"];
$mac = $_POST["mac"];

//Generate a random string.
$token = openssl_random_pseudo_bytes(16);
//Convert the binary data into hexadecimal representation.
$token = bin2hex($token);

$mysqli->query("INSERT INTO wakeonlan (token, ip, mac, port) VALUES (\"$token\",\"$ip\",\"$mac\",\"$port\")");

$redirectUrl = "https://pitangui.amazon.com/spa/skill/account-linking-status.html?vendorId=M2N8YS0X9LL1R6";
$tokenType = "&token_type=Bearer";
$accessToken = "&access_token=" . $token;

header("Location: " . $redirectUrl . $tokenType . $accessToken);
?>
