<?php

/**
*
* @param string $input
* @param string $string
* @param int $offset
* @param string $separator
* @return $string
* 
*/
function GetDistString( $input,$string,$offset,$separator ) {
	$string = substr( $input,strpos( $input,$string )+$offset,strpos( substr( $input,strpos( $input,$string )+$offset ), $separator ) );
	return $string;
}

/**
*
* @param array $arrConfig
* @return $config
*/
function ParseConfig( $arrConfig ) {
	$config = array();
	foreach( $arrConfig as $line ) {
		if( $line[0] != "#" ) {
			$arrLine = explode( "=",$line );
			$config[$arrLine[0]] = $arrLine[1];
		}
	}
	return $config;
}

/**
*
* @param string $freq
* @return $channel
*/
function ConvertToChannel( $freq ) {

	$base = 2412;
	$channel = 1;
	for( $x = 0; $x < 13; $x++ ) {
		if( $freq != $base ) {
			$base = $base + 5;
			$channel++;
		} else {
			return $channel;
		}
	}
	return "Invalid Channel";
}

/**
* Converts WPA security string to readable format
* @param string $security
* @return string
*/
function ConvertToSecurity( $security ) {
	
	switch( $security ) {
		case "[WPA2-PSK-CCMP][ESS]":
			return "WPA2-PSK (AES)";
			break;
		case "[WPA2-PSK-TKIP][ESS]":
			return "WPA2-PSK (TKIP)";
			break;
		case "[WPA2-PSK-CCMP][WPS][ESS]":
			return "WPA/WPA2-PSK (TKIP/AES)";
			break;
		case "[WPA2-PSK-TKIP+CCMP][WPS][ESS]":
			return "WPA2-PSK (TKIP/AES) with WPS";
			break;
		case "[WPA-PSK-TKIP+CCMP][WPS][ESS]":
			return "WPA-PSK (TKIP/AES) with WPS";
			break;
		case "[WPA-PSK-TKIP][WPA2-PSK-CCMP][WPS][ESS]":
			return "WPA/WPA2-PSK (TKIP/AES)";
			break;
		case "[WPA-PSK-TKIP+CCMP][WPA2-PSK-TKIP+CCMP][ESS]":
			return "WPA/WPA2-PSK (TKIP/AES)";
			break;
		case "[WPA-PSK-TKIP][ESS]":
			return "WPA-PSK (TKIP)";
			break;
		case "[WEP][ESS]":
			return "WEP";
			break;
	}
}

/**
*    // Willkommensseite
* //Einstellung Sprache, Systeminfos HW und SW, Versionsanzeige
* //Kontaktinfos
*
*/
function DisplayNetBoxStart($sprache){
    
// Klasse Sprache muß aufgerufen werdne damit die richtigen Sprachwerte geladen werden
$language = new language($sprache);
$lang = $language->translate();

//var_dump($sprache);
//var_dump ($lang);
// Willkommen, Sprache einstellen, Systeminformationen, HW und SW Status
//
$strwaehlen = $strsprachewaehlen;
$strHostname = exec( 'hostname', $return);
$strOS = exec('uname -o', $return);
$strOSRelease = exec('uname -r', $return);
$strUser = exec('whoami', $return);
 echo'<h4>'.$lang->Welcome.'</h4>'.
 '<b>'.$lang->AusgewählteSprache.'</b>'.$lang->Sprache.'<br>'.
'<h3>'.$lang->FirstStep.'</h3> ';
?>

<form>
  <select onchange="location.href=this.options[this.selectedIndex].value">
    <option value=""><?php echo $lang->Selectlanguage ?></option>
    <option value="index.php?page=NetBox_Start&llang=de">Deutsch</option>
    <option value="index.php?page=NetBox_Start&llang=en">Englisch</option>
  </select>
</form>
<br /><br />
<!--	<div class="row">
	    <div class="col-lg-12">
	        <div class="panel panel-primary">
	        	<div class="panel-heading"><i class="fa fa-dashboard fa-fw"></i> LAN Dashboard <img src="img/network-ethernet-icon.png" alt="" />	</div>
	            <div class="panel-body">
	            	<p><?php echo $ETHstatus; ?></p>
                    -->
         <?php
         //suche Octorpint Version
         exec( 'cat /var/opt/NetBoxPrint/venv/bin/octoprint',$return);
        // var_dump($return);
         	$strVersion = implode( " ", $return );
	       $strVersion = preg_replace( '/\s\s+/', ' ', $strVersion );
            //var_dump($return);
         	preg_match( '/OctoPrint==([a-zA-Z0-9-.\s]+)/i',$strVersion,$result );
	       $strOctoprintVersion = $result[1];
           // Free memory
           $free = shell_exec('free');
	       $free = (string)trim($free);
	       $free_arr = explode("\n", $free);
	       $mem = explode(" ", $free_arr[1]);
	       $mem = array_filter($mem);
           $mem = array_merge($mem);
           $UsedMemory = $mem[2]/1024;
           $TotalMemory = $mem[1]/1024;
	       $memory_usage = $mem[2]/$mem[1]*100;
           $load = sys_getloadavg();
           $df = disk_free_space("/");
           $externalIP = exec('./find_exIP.sh',$return);
           
// wlan0 Number           
        exec( 'ifconfig wlan0', $return );
//	exec( 'iwconfig wlan0', $return );
	$strWlan0 = implode( " ", $return );
	$strWlan0 = preg_replace( '/\s\s+/', ' ', $strWlan0 );
        preg_match( '/inet addr:([0-9.]+)/i',$strWlan0,$result );
	$wlanIP = $result[1];
        
//  ETH Number
   //     exec( 'ifconfig eth0', $return );
	exec( 'ifconfig eth0', $return1 );
	$strEth0 = implode( " ", $return1 );
	$strEth0 = preg_replace( '/\s\s+/', ' ', $strEth0 );
        preg_match( '/inet addr:([0-9.]+)/i',$strEth0,$result );
	$lanIP = $result[1];
        $routerIP = exec("/sbin/ip route | awk '/default/ { print $3 }'");
        $CpuID = exec('cat /proc/cpuinfo | grep Serial | tr -d " " | cut -d ":" -f 2');
        $CpuTemp = exec ('cat /sys/class/thermal/thermal_zone*/temp')
         ?>
	                <div class="row">
	                    <div class="col-md-6">
	                        <div class="panel panel-default">
		                        <div class="panel-body">
		                        <h4><?php echo $lang->Systeminformationen ?></h4>
                                <?php
                                echo    '<h6>' .$lang->Basicinfos . RASPI_VERSION. RASPI_VERSION_DATE.'</h6><br />'.
                                        '<b>Hostname: </b>'.$strHostname.'<br>'.
                                        '<b>'.$lang->OSSystem.'</b> '.$strOS.' Release: '.$strOSRelease.'<br>'.
                                        '<b>User:</b> '.$strUser.'<br>'.
                                        '<b>Web Server: </b>' . $_SERVER[SERVER_SOFTWARE].'<br>'.
                                        '<b>HTTP User Agent: </b>' . $_SERVER[HTTP_USER_AGENT].'<br>'.
                                        '<b>3D Printer SW Version: </b>NetBox3D ' . $strOctoprintVersion. '<br>'.
                                        '<b>' .$lang->UsedMemory . ': </b> ' . round($UsedMemory,2) .' MByte    =  '. round($memory_usage,2) .' %<br>'.
                                        '<b>' .$lang->FreeMemory . ': </b> ' . round($TotalMemory-$UsedMemory,2) . ' MByte <br>'.
                                        '<b>CPU Load: </b> ' . $load[0] . ' %<br>'.
                                        '<b> '.$lang->freesdcard . ': </b>' .round($df/1024,2) .'  MByte <br><br>'.
                                        '<b>'. $lang->externalip. ': </b> '.$externalIP . '<br>'.
                                        '<b>'. $lang->routerIP . ' : </b>' . $routerIP . '<br>'.
                                        '<b>'. $lang->localIP . '</b><br><b> WIFI: </b>' . $wlanIP .'<br><b> ETH0 :</b> '.$lanIP . '<br>'.
                                        '<b> CPU ID: </b>'.md5($CpuID).'<br>'.
                                        '<b>'. $lang->CPUTemp . ': </b>' . round($CpuTemp/1000,1) . '	&deg C<br>'
                                        
                                        
		                          ?>
								</div><!-- /.panel-body -->
							</div><!-- /.panel-default -->
	                    </div><!-- /.col-md-6 -->

	                </div>
<!--
		            </div>
		            <div class="panel-footer"><?php echo $lang->ifconfig ?></div>
		        </div>
		    </div>
		</div>
      -->
<?php

 }
/**
*
*
*/
function DisplayDashboardWlan($sprache){
    
    // Klasse Sprache muß aufgerufen werdne damit die richtigen Sprachwerte geladen werden
$language = new language($sprache);
$lang = $language->translate();

	exec( 'ifconfig wlan0', $return );
	exec( 'iwconfig wlan0', $return );
//var_dump($return);
	$strWlan0 = implode( " ", $return );
	$strWlan0 = preg_replace( '/\s\s+/', ' ', $strWlan0 );

//var_dump($strWlan0);
	// Parse results from ifconfig/iwconfig
	preg_match( '/HWaddr ([0-9a-f:]+)/i',$strWlan0,$result );
	$strHWAddress = $result[1];
	preg_match( '/inet addr:([0-9.]+)/i',$strWlan0,$result );
	$strIPAddress = $result[1];
	preg_match( '/Mask:([0-9.]+)/i',$strWlan0,$result );
	$strNetMask = $result[1];
	preg_match( '/RX packets:(\d+)/',$strWlan0,$result );
	$strRxPackets = $result[1];
	preg_match( '/TX packets:(\d+)/',$strWlan0,$result );
	$strTxPackets = $result[1];
	preg_match( '//RX bytes:(\d+)/i',$strWlan0,$result );
	$strRxBytes = $result[1];
	preg_match( '/TX Bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$strWlan0,$result );
	$strTxBytes = $result[1];
	preg_match( '/ESSID:\"([a-zA-Z0-9-\s]+)\"/i',$strWlan0,$result );
	$strSSID = str_replace( '"','',$result[1] );
	preg_match( '/Access Point: ([0-9a-f:]+)/i',$strWlan0,$result );
	$strBSSID = $result[1];
	preg_match( '/Bit Rate=([0-9]+ Mb\/s)/i',$strWlan0,$result );
	$strBitrate = $result[1];
	preg_match( '/Tx-Power=([0-9]+ dBm)/i',$strWlan0,$result );
	$strTxPower = $result[1];
	preg_match( '/Link Quality=([0-9]+)/i',$strWlan0,$result );
	$strLinkQuality = $result[1];
	preg_match( '/Signal Level=([0-9]+)/i',$strWlan0,$result );
	$strSignalLevel = $result[1];
	preg_match('/Frequency:(\d+.\d+ GHz)/i',$strWlan0,$result);
	$strFrequency = $result[1];

	if(strpos( $strWlan0, "UP" ) !== false && strpos( $strWlan0, "RUNNING" ) !== false ) {
		$status = '<div class="alert alert-success alert-dismissable">Interface is up
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
		$wlan0up = true;
	} else {
		$status =  '<div class="alert alert-warning alert-dismissable">Interface is down
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
	}

	if( isset($_POST['ifdown_wlan0']) ) {
		exec( 'ifconfig wlan0 | grep -i running | wc -l',$test );
		if($test[0] == 1) {
			exec( 'sudo ifdown wlan0',$return );
		} else {
			echo 'WLAN0 Interface already down';
		}
	} elseif( isset($_POST['ifup_wlan0']) ) {
		exec( 'ifconfig wlan0 | grep -i running | wc -l',$test );
		if($test[0] == 0) {
			exec( 'sudo ifup wlan0',$return );
		} else {
			echo 'WLAN0 Interface already up';
		}
	}
	?>
	<div class="row">
	    <div class="col-lg-12">
	        <div class="panel panel-primary">
	        	<div class="panel-heading"><i class="fa fa-dashboard fa-fw"></i> WiFi Dashboard  <img src="img/Devices-network-wireless-connected-24-icon.png" alt="WiFi Verbindung" />	</div>
	            <div class="panel-body">
	            	<p><?php echo $status; ?></p>
	                <div class="row">
	                    <div class="col-md-6">
	                        <div class="panel panel-default">
		                        <div class="panel-body">
		                        <h4>Interface Information</h4>
		                        Interface Name : wlan0<br />
								IP Address : <?php echo $strIPAddress ?><br />
								Subnet Mask : <?php echo $strNetMask ?><br />
								Mac Address : <?php echo $strHWAddress ?><br />

		                        <h4>Interface Statistics</h4>
		                        Received Packets : <?php echo $strRxPackets ?><br />
								Received Bytes : <?php echo $strRxBytes ?><br /><br />
								Transferred Packets : <?php echo $strTxPackets ?><br />
								Transferred Bytes : <?php echo $strTxBytes ?><br />
								</div><!-- /.panel-body -->
							</div><!-- /.panel-default -->
	                    </div><!-- /.col-md-6 -->

	                    <div class="col-md-6">
	                        <div class="panel panel-default">
		                        <div class="panel-body wireless">
	                        	<h4>Wireless Information</h4>
	                        	Connected To : <?php echo $strSSID ?><br />
								AP Mac Address : <?php echo $strBSSID ?><br />
								Bitrate : <?php echo $strBitrate ?><br />
								Transmit Power : <?php echo $strTxPower ?><br />
								Frequency : <?php echo $strFrequency ?><br />
								Link Quality :
								<div class="progress">
								  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $strLinkQuality ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $strLinkQuality ?>%;">
								    <?php echo $strLinkQuality ?>%
								  </div>
								</div>
								Signal Level :
								<div class="progress">
								  <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo $strSignalLevel ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $strSignalLevel ?>%;">
								    <?php echo $strSignalLevel ?>%
								  </div>
								</div>
	                        	</div><!-- /.panel-body -->
							</div><!-- /.panel-default -->

	                    </div><!-- /.col-md-6 -->
	                </div>

	                <div class="col-lg-12">
			        	 <div class="row">
				            <form action="?page=wlan0_info" method="POST">
				            <?php if ( !$wlan0up ) {
				            	echo '<input type="submit" class="btn btn-success" value="Start wlan0" name="ifup_wlan0" />';
				            } else {
								echo '<input type="submit" class="btn btn-warning" value="Stop wlan0" name="ifdown_wlan0" />';
							}
							?>
							<input type="button" class="btn btn-outline btn-primary" value="Refresh" onclick="document.location.reload(true)" />
							</form>
						</div>
			        </div>

		            </div><!-- /.panel-body -->
		            <div class="panel-footer"><?php echo $lang->ifconfig ?></div>
		        </div><!-- /.panel-default -->
		    </div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
	<?php
}

/**
*
*
*/
function DisplayDashboardLan($sprache){

// Klasse Sprache muß aufgerufen werdne damit die richtigen Sprachwerte geladen werden
$language = new language($sprache);
$lang = $language->translate();

	exec( 'ifconfig eth0', $return );
   //	exec( 'iwconfig wlan0', $return );

	$strlan0 = implode( " ", $return );
	$strlan0 = preg_replace( '/\s\s+/', ' ', $strlan0 );

	// Parse results from ifconfig/iwconfig
	preg_match( '/HWaddr ([0-9a-f:]+)/i',$strlan0,$result );
	$strHWAddress = $result[1];
	preg_match( '/inet addr:([0-9.]+)/i',$strlan0,$result );
	$strIPAddress = $result[1];
	preg_match( '/Mask:([0-9.]+)/i',$strlan0,$result );
	$strNetMask = $result[1];
	preg_match( '/RX packets:(\d+)/',$strlan0,$result );
	$strRxPackets = $result[1];
	preg_match( '/TX packets:(\d+)/',$strlan0,$result );
	$strTxPackets = $result[1];
	preg_match( '//RX bytes:(\d+)/i',$strlan0,$result );
	$strRxBytes = $result[1];
	preg_match( '/TX Bytes:(\d+ \(\d+.\d+ [K|M|G]iB\))/i',$strlan0,$result );
	$strTxBytes = $result[1];
	preg_match( '/ESSID:\"([a-zA-Z0-9\s]+)\"/i',$strlan0,$result );
	$strSSID = str_replace( '"','',$result[1] );
	preg_match( '/Access Point: ([0-9a-f:]+)/i',$strlan0,$result );
	$strBSSID = $result[1];
	preg_match( '/Bit Rate=([0-9]+ Mb\/s)/i',$strlan0,$result );
	$strBitrate = $result[1];
	preg_match( '/Tx-Power=([0-9]+ dBm)/i',$strlan0,$result );
	$strTxPower = $result[1];
	preg_match( '/Link Quality=([0-9]+)/i',$strlan0,$result );
	$strLinkQuality = $result[1];
	preg_match( '/Signal Level=([0-9]+)/i',$strlan0,$result );
	$strSignalLevel = $result[1];
	preg_match('/Frequency:(\d+.\d+ GHz)/i',$strlan0,$result);
	$strFrequency = $result[1];

     if(strpos( $strlan0, "UP" ) !== false && strpos( $strlan0, "RUNNING" ) !== false ) {
		$ETHstatus = '<div class="alert alert-success alert-dismissable">Interface is up
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
		$lan0up = true;
	} else {
		$ETHstatus =  '<div class="alert alert-warning alert-dismissable">Interface is down
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
	}

	if( isset($_POST['ifdown_lan0']) ) {
		exec( 'ifconfig eth0 | grep -i running | wc -l',$test );
		if($test[0] == 1) {
			exec( 'sudo ifdown eth0',$return );
		} else {
			echo 'ETH0 Interface already down';
		}
	} elseif( isset($_POST['ifup_lan0']) ) {
		exec( 'ifconfig eth0 | grep -i running | wc -l',$test );
		if($test[0] == 0) {
			exec( 'sudo ifup eth0',$return );
		} else {
			echo 'ETH0 Interface already up';
		}
	}
	?>
	<div class="row">
	    <div class="col-lg-12">
	        <div class="panel panel-primary">
	        	<div class="panel-heading"><i class="fa fa-dashboard fa-fw"></i> LAN Dashboard <img src="img/network-ethernet-icon.png" alt="" />	</div>
	            <div class="panel-body">
	            	<p><?php echo $ETHstatus; ?></p>
	                <div class="row">
	                    <div class="col-md-6">
	                        <div class="panel panel-default">
		                        <div class="panel-body">
		                        <h4>Interface Information</h4>
		                        Interface Name : lan0<br />
								IP Address : <?php echo $strIPAddress ?><br />
								Subnet Mask : <?php echo $strNetMask ?><br />
								Mac Address : <?php echo $strHWAddress ?><br />

		                        <h4>Interface Statistics</h4>
		                        Received Packets : <?php echo $strRxPackets ?><br />
								Received Bytes : <?php echo $strRxBytes ?><br /><br />
								Transferred Packets : <?php echo $strTxPackets ?><br />
								Transferred Bytes : <?php echo $strTxBytes ?><br />
								</div><!-- /.panel-body -->
							</div><!-- /.panel-default -->
	                    </div><!-- /.col-md-6 -->

	                </div>

	                <div class="col-lg-12">
			        	 <div class="row">
				            <form action="?page=lan0_info" method="POST">
				            <?php if ( !$lan0up ) {
				            	echo '<input type="submit" class="btn btn-success" value="Start lan0" name="ifup_lan0" />';
				            } else {
								echo '<input type="submit" class="btn btn-warning" value="Stop lan0" name="ifdown_lan0" />';
							}
							?>
							<input type="button" class="btn btn-outline btn-primary" value="Refresh" onclick="document.location.reload(true)" />
							</form>
						</div>
			        </div>

		            </div><!-- /.panel-body -->
		            <div class="panel-footer"><?php echo $lang->ifconfig ?></div>
		        </div><!-- /.panel-default -->
		    </div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
	<?php
}

/**
*
*
*/
function DisplayWPAConfig($sprache){

// Klasse Sprache muß aufgerufen werdne damit die richtigen Sprachwerte geladen werden
$language = new language($sprache);
$lang = $language->translate();

	?>
	<div class="row">
		<div class="col-lg-12">
	    	<div class="panel panel-primary">
				<div class="panel-heading"><i class="fa fa-signal fa-fw"></i> Configure WiFi client   <img src="img/Devices-network-wireless-connected-24-icon.png" alt="WiFi Verbindung" />
	            </div>
		        <!-- /.panel-heading -->
		        <div class="panel-body">
		        	<?php echo $status; ?>
            		<h4>Client settings</h4>
					<div class="row">
						<div class="col-lg-12">

					<?php
					// save WPA settings
					if( isset($_POST['SaveWPAPSKSettings']) ) {

						$config = 'ctrl_interface=DIR='. RASPI_WPA_CTRL_INTERFACE .' GROUP=netdev
update_config=1
';
						$networks = $_POST['Networks'];
						for( $x = 0; $x < $networks; $x++ ) {
							$network = '';
							$ssid = escapeshellarg( $_POST['ssid'.$x] );
							$psk = escapeshellarg( $_POST['psk'.$x] );

							if ( strlen($psk) >2 ) {
								exec( 'wpa_passphrase '.$ssid. ' ' . $psk,$network );
								foreach($network as $b) {
				$config .= "$b
";
								}
							}
						}
						exec( "echo '$config' > /tmp/wifidata", $return );
						system( 'sudo cp /tmp/wifidata ' . RASPI_WPA_SUPPLICANT_CONFIG, $returnval );
						if( $returnval == 0 ) {
							echo '<div class="alert alert-success alert-dismissable">Wifi settings updated successfully
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
						} else {
							echo '<div class="alert alert-danger alert-dismissable">Wifi settings failed to be updated
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
						}

					// scan networks
					} elseif( isset($_POST['Scan']) ) {
						$return = '';
						exec( 'sudo wpa_cli scan',$return );
						sleep(3);
						exec( 'sudo wpa_cli scan_results',$return );
						for( $shift = 0; $shift < 4; $shift++ ) {
							array_shift($return);
						}
						// display output
                        // Achtung ?page=wpa_conf mit der Sprache erweitern, sonst kein detailscreen der vorhandenen Netzwerke
                       	echo '<form method="POST" action="?page=wpa_conf&llang='.$sprache.'" id="wpa_conf_form"><input type="hidden" id="Networks" name="Networks" /><div class="network" id="networkbox"></div>';
						echo '<div class="row"><div class="col-lg-6"><input type="submit" class="btn btn-primary" value="Scan for networks" name="Scan" /> <input type="button" class="btn btn-primary" value="Add network" onClick="AddNetwork();" /> <input type="submit" class="btn btn-primary" value="Save" name="SaveWPAPSKSettings" onmouseover="UpdateNetworks(this)" id="Save" disabled /></div></div>';
						echo '<h4>Networks found</h4><div class="table-responsive"><table class="table table-hover">';
						echo '<thead><tr><th></th><th>SSID</th><th>Channel</th><th>Signal</th><th>Security</th></tr></thead><tbody>';
						foreach( $return as $network ) {
							$arrNetwork = preg_split("/[\t]+/",$network);
							$bssid = $arrNetwork[0];
							$channel = ConvertToChannel($arrNetwork[1]);
							$signal = $arrNetwork[2] . " dBm";
							$security = $arrNetwork[3];
							$ssid = $arrNetwork[4];
							echo '<tr><td><input type="button" class="btn btn-outline btn-primary" value="Connect" onClick="AddScanned(\''.$ssid.'\')" /></td> <td><strong>' . $ssid . "</strong></td> <td>" . $channel . "</td><td>" . $signal . "</td><td>". ConvertToSecurity($security) ."</td></tr>";
						}
						echo '</tbody></table>';

					} else {

						// default action, output configured network(s)
						exec(' sudo cat ' . RASPI_WPA_SUPPLICANT_CONFIG, $return);
						$ssid = array();
						$psk = array();

						foreach($return as $a) {
							if(preg_match('/SSID/i',$a)) {
								$arrssid = explode("=",$a);
								$ssid[] = str_replace('"','',$arrssid[1]);
							}
							if(preg_match('/psk/i',$a)) {
								$arrpsk = explode("=",$a);
								$psk[] = str_replace('"','',$arrpsk[1]);
							}
						}

						$numSSIDs = count($ssid);
                        // Achtung ?page=wpa_conf mit der Sprache erweitern, sonst kein detailscreen der vorhandenen Netzwerke
						$output = '<form method="POST" action="?page=wpa_conf&llang='.$sprache.'" id="wpa_conf_form"><input type="hidden" id="Networks" name="Networks" /><div class="network" id="networkbox">';
						
						if ( $numSSIDs > 0 ) {
							for( $ssids = 0; $ssids < $numSSIDs; $ssids++ ) {
								$output .= '<div id="Networkbox'.$ssids.'" class="NetworkBoxes">
									<div class="row"><div class="form-group col-md-4"><label for="code">Network '.$ssids.'</label></div></div>
									<div class="row"><div class="form-group col-md-4"><label for="code" id="lssid0">SSID</label><input type="text" class="form-control" id="ssid0" name="ssid'.$ssids.'" value="'.$ssid[$ssids].'" onkeyup="CheckSSID(this)" /></div></div>
									<div class="row"><div class="form-group col-md-4"><label for="code" id="lpsk0">PSK</label><input type="password" class="form-control" id="psk0" name="psk'.$ssids.'" value="'.$psk[$ssids].'" onkeyup="CheckPSK(this)" /></div></div>
									<div class="row"><div class="form-group col-md-4"><input type="button" class="btn btn-outline btn-primary" value="Delete" onClick="DeleteNetwork('.$ssids.')" /></div></div>';
						}
							$output .= '</div><!-- /#Networkbox -->';
						} else {
							$status = '<div class="alert alert-warning alert-dismissable">Not connected
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>';
						}
						$output .= '<div class="row"><div class="col-lg-6"><input type="submit" class="btn btn-primary" value="Scan for networks" name="Scan" /> <input type="button" class="btn btn-primary" value="Add network" onClick="AddNetwork();" /> <input type="submit" class="btn btn-primary" value="Save" name="SaveWPAPSKSettings" onmouseover="UpdateNetworks(this)" id="Save" disabled />';
						$output .= '</form>'; 
						echo $output;
					}
					?>
					<script type="text/Javascript">UpdateNetworks(this)</script>
				</form>
				</div><!-- ./ Panel body -->
		    </div><!-- /.panel-primary -->
		</div><!-- /.col-lg-12 -->
	</div><!-- /.row -->
<?php
}
function DisplayUPDATE($sprache) {
// Klasse Sprache muß aufgerufen werdne damit die richtigen Sprachwerte geladen werden
$language = new language($sprache);
$lang = $language->translate();

	if( isset($_POST['update_1']) ) {
             exec( 'cd /var/www/&&sudo /usr/bin/git pull', $return );
             
               var_dump($return);
            
            popupwindow($return);
		
	} elseif( isset($_POST['update_2']) ) {
	exec("cat /proc/cpuinfo", $return);
        popupwindow($return);
            
	}
  
	?>
	<div class="row">
	    <div class="col-lg-12">
	        <div class="panel panel-primary">
	        	<div class="panel-heading"><i class="fa fa-dashboard fa-fw"></i> System Update <img src="img/network-ethernet-icon.png" alt="" />	</div>
	            <div class="panel-body">
	            	
	                <div class="row">
	                    <div class="col-md-6">
	                        <div class="panel panel-default">
		                        <div class="panel-body" style="color: red">
                                            <h4><?php echo $lang->Update ?></h4> <br>
                                            <?php echo $lang->Updateinfo ?>
                                            	                    <div class="col-md-6">


	                    </div><!-- /.col-md-6 -->
		                        </div><!-- /.panel-body -->
                                </div><!-- /.panel-default -->
	                    </div><!-- /.col-md-6 -->

	                </div>

	                <div class="col-lg-12">
			        	 <div class="row">
                                             <form action="?page=update&llang=<?php echo $sprache; ?>" method="POST">
				            <?php 
				            	echo '<input type="submit" class="btn btn-success" value="Update System" name="update_1" />';
				            	echo '<input type="submit" class="btn btn-success" value="Check Version" name="update_2" />';
							
                                            ?>
                                            </form>
					</div>
			</div>

		            </div><!-- /.panel-body -->
		            <div class="panel-footer"><?php echo $lang->systemupdatefunctions ?></div>
		        </div><!-- /.panel-default -->
		    </div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
	<?php
    
}

function popupwindow($content) {
    $inhalt = "";
    for($i=0; $i < count($content); $i++)
   {
   //echo $content[$i]."<br>";
   $inhalt = $inhalt.$content[$i].'\n';
   
   }
   //echo $inhalt;
 

    
      echo "<script language=\"Javascript\">"; 
  echo "alert('$inhalt');"; 
  echo "</script>"; 
    
    
    
    
    
    
}


