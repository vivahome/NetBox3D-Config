<?php

class language {

   public $data;

   function __construct($language) {
      $data = file_get_contents('includes/'.$language . ".json");
      $this->data = json_decode($data);
   }

   function translate() {
        return $this->data;
   }
}


/**
 * Raspbian WiFi Configuration Portal
 * adopted to Viva Home NetBox 05/2015
 *
 * Enables use of simple web interface rather than SSH to control wifi and hostapd on the Raspberry Pi.
 * Recommended distribution is Raspbian Server Edition. Specific instructions to install the supported software are
 * in the README and original post by @SirLagz. For a quick run through, the packages required for the WebGUI are:
 * lighttpd (I have version 1.4.31-2 installed via apt)
 * php5-cgi (I have version 5.4.4-12 installed via apt)
 * along with their supporting packages, php5 will also need to be enabled.
 * 
 * LICENSE: TODO
 *
 * @author     Lawrence Yau 
 * @author     Bill Zimmerman <billzimmerman@gmail.com>
 * @author     ve <ve@vivahome.de>
 * @license    TBD
 * @version    1.0
 * @link       https://github.com/
 * @see        http://sirlagz.net/2013/02/08/raspap-webgui/
 * @see        http://www.vivahome.eu http://www.3dreprap.net
 */
define('RASPI_VERSION', ' V1.01');
define('RASPI_VERSION_DATE', '  05/2015');
// Constants for configuration file paths.
// These are typical for default RPi installs. Modify if needed.
define('RASPI_DNSMASQ_CONFIG', '/etc/dnsmasq.conf');
define('RASPI_DNSMASQ_LEASES', '/var/lib/misc/dnsmasq.leases');
define('RASPI_HOSTAPD_CONFIG', '/etc/hostapd/hostapd.conf');
define('RASPI_WPA_SUPPLICANT_CONFIG', '/etc/wpa_supplicant/wpa_supplicant.conf');
define('RASPI_HOSTAPD_CTRL_INTERFACE', '/var/run/hostapd');
define('RASPI_WPA_CTRL_INTERFACE', '/var/run/wpa_supplicant');
define('RASPI_OPENVPN_CLIENT_CONFIG', '/etc/openvpn/client.conf');
define('RASPI_OPENVPN_SERVER_CONFIG', '/etc/openvpn/server.conf');
define('RASPI_TORPROXY_CONFIG', '/etc/tor/torrc');

// Optional services, set to true to enable.
define('RASPI_OPENVPN_ENABLED', false );
define('RASPI_TORPROXY_ENABLED', false );


include_once( 'includes/functions.php' );


$output = $return = 0;
$page = $_GET['page'];
//echo $page;
// hier wird die �bergeben Sprache abgefragt. Wenn keine &llang �bergebn wird, wird die Variable $sprache vorbelegt und
// die Seite neu mit den richtigen Parametern geladen.
$sprache = $_GET['llang'];
//var_dump($sprache);
if (empty($sprache)) {
    $sprache = 'de';
    header('Location: index.php?page=NetBox_Start&llang=de');
    //var_dump($sprache);
}



?>

<!DOCTYPE html>
<?php
// Klasse Sprache mu� aufgerufen werdne damit die richtigen Sprachwerte geladen werden
$language = new language($sprache);
$lang = $language->translate();
?>

<html >
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>NetBox WiFi Configuration Portal</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <link href="dist/css/custom.css" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="../img/favicon.png">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

	<div id="wrapper">
		<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="index.php">NetBox Wifi Portal v1.1<br> by Viva Home GmbH 05/2015</a>
			</div>
			<!-- /.navbar-header -->

			<!-- Navigation -->
	        <div class="navbar-default sidebar" role="navigation">
	            <div class="sidebar-nav navbar-collapse">
	                <ul class="nav" id="side-menu">
                        <li>
	                        <a href="index.php?page=NetBox_Start&llang=<?php echo $sprache ?>"><i class="fa fa-dashboard fa-fw"></i> Start</a>
	                    </li>
	                    <li>
	                        <a href="index.php?page=wlan0_info&llang=<?php echo $sprache ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard WLAN</a>
	                    </li>
                          <li>
	                        <a href="index.php?page=lan0_info&llang=<?php echo $sprache ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard LAN</a>
	                    </li>
	                    <li>
	                        <a href="index.php?page=wpa_conf&llang=<?php echo $sprache ?>"><i class="fa fa-signal fa-fw"></i> Configure WLAN client</a>
	                    </li>
                             <li>
	                        <a href="index.php?page=update&llang=<?php echo $sprache ?>"><i class="fa fa-signal fa-fw"></i> Update System</a>
	                    </li>


	                </ul>
	            </div><!-- /.navbar-collapse -->
	        </div><!-- /.navbar-default -->
	    </nav>

	    <div id="page-wrapper">

		    <!-- Page Heading -->

		            <h1 class="page-header">
		                <img class="logo" src="img/raspAP-logo.png" width="45" height="45"/>NetBox <small><?php echo $lang->Home; ?> </small>
		            </h1>

			<?php
			// handle page actions
            // Achtung!! Beim Aufruf der Funktion f�r den Men�punkt mu� die Sprache mit �bergeben werden.
			switch( $page ) {
			    case "NetBox_Start":
					DisplayNetBoxStart($sprache);
					break;
				case "wlan0_info":
					DisplayDashboardWlan($sprache);
					break;
                                case "lan0_info":
					DisplayDashboardLan($sprache);
					break;
				case "wpa_conf":
					DisplayWPAConfig($sprache);
					break;
                                case "update":
					DisplayUPDATE($sprache);
					break;

				default:
					DisplayNetBoxStart($sprache);
				}
			?> 
       
            
	    </div><!-- /#page-wrapper --> 
            <div class="panel-footer" style="font-size: 80%;"><?php echo $lang->Impressum ?></div>
    </div><!-- /#wrapper -->
    


    <!-- RaspAP JavaScript -->
    <script src="dist/js/functions.js"></script>

    <!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <!--script src="bower_components/raphael/raphael-min.js"></script-->
    <!--script src="bower_components/morrisjs/morris.min.js"></script-->
    <!--script src="js/morris-data.js"></script-->

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
</body>
</html>
