<?php
	/*
	  Plugin Name: Bazo
	  Version: 1.4
	  Description: Automatically adds Bazo.io tracker to site
	  Author: Bazo
	  Author URI: https://bazo.io/
	  */

	/* Version check */
	global $wp_version;

	$exit_msg = ' 
  Bazo requires WordPress 3.5 or newer. 
  <a href="http://codex.wordpress.org/Upgrading_WordPress"> 
  Please update!</a>';

	if ( version_compare( $wp_version, "3.5", "<" ) ) {
		exit( $exit_msg );
	}

	class BazoSettingsPage {
		/**
		 * Holds the values to be used in the fields callbacks
		 */
		private $options;

		/**
		 * Start up
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}

		/**
		 * Add options page
		 */
		public function add_plugin_page() {
			// This page will be under "Settings"
			add_options_page(
				'Bazo Settings',
				'Bazo',
				'manage_options',
				'bazo-setting-admin',
				array( $this, 'create_admin_page' )
			);
		}

		/**
		 * Options page callback
		 */
		public function create_admin_page() {

			// Set class property
			$this->options = get_option( 'bazo_options' );
			?>

            <style>
                input.valid, input.valid:focus{
                    border-color: #0cd046;
                    box-shadow: 0 0 0 1px #0cd046 !important;
                    background: #0cd04682 !important;
                }
                input.notvalid, input.notvalid:focus{
                    border-color: #e15858 !important;
                    box-shadow: 0 0 0 1px #e15858 !important;
                }
                input.unset, input.unset:focus{
                    border-color: #5c68ff !important;
                    box-shadow: 0 0 0 1px #5c68ff !important;
                }
            </style>

            <div class="wrap">

                <div id="total"></div>
                <!--<h1>Bazo Settings</h1>-->
				<?php if ( is_plugin_active( 'wp-super-cache/wp-cache.php' ) || is_plugin_active( 'w3-total-cache/w3-total-cache.php' ) || is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) { ?>
                    <div style="border: 2px solid #ff1212;border-radius: 10px;padding: 10px; background: #f7ff88;float: left;">
                        <span style="font-weight: bold;height: 20px;font-size: 1.5em;vertical-align: sub;margin-right: 5px;">⚠</span>️<b>Uwaga!</b>
                        Wyglda na to, że posiadasz plugin do obsługi cache.
                        Pamiętaj aby po zapisaniu kodu <b>wyczyścić cache strony</b> w ustawieniach pluginu.
                    </div>
				<?php } ?>

                <form style="float:left;display: block;clear: both;" method="post" action="options.php">
					<?php
						// This prints out all hidden setting fields
						settings_fields( 'bazo_options_group' );
						do_settings_sections( 'bazo-setting-admin' );
						submit_button();
					?>
                </form>
            </div>

            <h2 class="clear">Shortcode</h2>
            <ul>
                <li><code><b>[bazo-company]</b></code> - do użycia w edytorze wizualnym.</li>

                <li>
                    <code><b><?php echo htmlspecialchars( "<?php echo do_shortcode('[bazo-company]'); ?>" ); ?></b></code>
                    - do użycia w kodzie szablonu strony.
                </li>
            </ul>
            <hr>
            <h3 class="clear">Parametry do użycia w shortcode</h3>
            <div class="clear">
                <ul>
                    <li><b>name</b> - Treść jaka pojawi się jeśli nie zostawnie wykryta firma <i>[Domyślnie = puste]</i>
                    </li>
                    <li><b>lowernext</b> - Wartość ustawiona na "1" = następna litera po nazwie firmy zmieniana jest na
                        małą <i>[Domyślnie = "0"]</i></li>
                    <li><b>maxlength</b> - Maksymalna długość nazwy firmy, jeśli nazwa przekroczy zadaną wartość
                        dodawane jest "..." <i>[Domyślnie = "9999"]</i></li>
                    <li><b>color</b> - Color fonta wykrytej firmy <i>[Domyślnie = "inherit"]</i></li>
                    <li><b>prefix</b> - Tekst jaki pojawi się przed wykrytą firmą <i>[Domyślnie = puste]</i></li>
                    <li><b>removestatut</b> - Wartość ustawiona na "1" = usuwa statut firm np. "Sp. z. o.o.", "S.A."
                        itp. <i>[Domyślnie = "0"]</i></li>
                </ul>
            </div>
            <hr>
            <h3>Przykład użycia:</h3>
            <div><code>[bazo-company"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Nazwa-Firmy Sp. z o.o. Świetnie dziś wyglądasz! Miłego dnia.</li>
                <li><b>Nierozpoznana Firma:</b> Świetnie dziś wyglądasz! Miłego dnia.</li>
            </ul>

            <div><code>[bazo-company name ="Czytelniku"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Nazwa-Firmy Sp. z o.o. Świetnie dziś wyglądasz! Miłego dnia.</li>
                <li><b>Nierozpoznana Firma:</b> Czytelniku Świetnie dziś wyglądasz! Miłego dnia.</li>
            </ul>

            <div><code>[bazo-company lowernext="1"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Nazwa-Firmy Sp. z o.o. świetnie dziś wyglądasz! Miłego dnia.</li>
            </ul>

            <div><code>[bazo-company maxlength ="8"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Nazwa-Fi... Świetnie dziś wyglądasz! Miłego dnia.</li>
            </ul>

            <div><code>[bazo-company color="#00F"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> <span style="color:#00F">Nazwa-Firmy Sp. z o.o.</span> Świetnie dziś
                    wyglądasz! Miłego dnia.
                </li>
            </ul>

            <div><code>[bazo-company prefix ="Przyjacielu z"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Przyjacielu z Nazwa-Firmy Sp. z o.o. Świetnie dziś wyglądasz! Miłego dnia.
                </li>
            </ul>

            <div><code>[bazo-company suffix =","] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Nazwa-Firmy Sp. z o.o., Świetnie dziś wyglądasz! Miłego dnia.
                </li>
            </ul>

            <div><code>[bazo-company removestatut ="1"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>
            <ul>
                <li><b>Rozpoznana Firma:</b> Nazwa-Firmy Świetnie dziś wyglądasz! Miłego dnia.</li>
            </ul>
            <hr>
            <h4>Parametry można łączyć ze sobą np.:</h4>
            <div><code>[bazo-company lowernext="1" name="Przyjacielu" prefix="Pracowniku" suffix ="," color="#73b20b"
                    removestatut="1"] Świetnie dziś wyglądasz! Miłego dnia.</code></div>

            <h4>W przypadku wykrycia firmy (np. "Nazwa-Firmy Sp. z o.o.") otrzymamy:</h4>
            <i>Pracowniku <b style="color:#73b20b">Nazwa-Firmy</b>, świetnie dziś wyglądasz! Miłego dnia.</i>
            <script>
                function updateValue() {
                    var iv = document.getElementById("ind-valid");
                    var inv = document.getElementById("ind-nvalid");
                    var input = document.getElementById("bazo_tracker");
                    var total = document.getElementById("bazo_tracker").value;
                    total = total.replace(/\s/g, '');

                    if ((total.length === 0)){
                        iv.classList.add("hidden");
                        inv.classList.add("hidden");
                        input.classList.remove("valid");
                        input.classList.remove("notvalid");
                        input.classList.add("unset");
                    }
                    else if (total.length === 9) {
                        input.classList.remove("unset");
                        input.classList.add("valid");
                        input.classList.remove("notvalid");
                        iv.classList.remove("hidden");
                        inv.classList.add("hidden");
                    } else {
                        input.classList.remove("unset");
                        input.classList.add("notvalid");
                        input.classList.remove("valid");
                        inv.classList.remove("hidden");
                        iv.classList.add("hidden");
                    }
                }
                // Register event handlers.
                updateValue();
                var inputelem = document.getElementById("bazo_tracker");
                inputelem.addEventListener('keypress', updateValue);
                inputelem.addEventListener('keyup', updateValue);
                inputelem.addEventListener('input', updateValue);
                inputelem.addEventListener('change', updateValue);
            </script>
			<?php

		}

		/**
		 * Register and add settings
		 */
		public function page_init() {
			register_setting(
				'bazo_options_group', // Option group
				'bazo_options' // Option name
			);

			add_settings_section(
				'setting_section_id', // ID
				'Bazo Tracker', // Title
				array( $this, 'print_section_info' ), // Callback
				'bazo-setting-admin' // Page
			);

			add_settings_field(
				'bazo_tracker', // ID
				'Bazo Tracking Code', // Title
				array( $this, 'id_number_callback' ), // Callback
				'bazo-setting-admin', // Page
				'setting_section_id' // Section
			);
		}

		/**
		 * Print the Section text
		 */
		public function print_section_info() {
			print 'Wprowadź kod Bazo poniżej:';
		}

		/**
		 * Get the settings option array and print one of its values
		 */
		public function id_number_callback() {
			printf(
				'BI-<input type="text" id="bazo_tracker" name="bazo_options[bazo_tracker]" value="%s" />',
				isset( $this->options['bazo_tracker'] ) ? esc_attr( $this->options['bazo_tracker'] ) : ''
			);
			$bazo_options = get_option( 'bazo_options' );
			$bazo_tracker = $bazo_options['bazo_tracker'];
/*			if ( is_valid( $bazo_tracker ) ) {
				printf( '<span class="indicator-valid" style="margin-left: 2px;border: 1px solid green;border-radius: 25px;padding: 0px 4px;background: green;color: #fff;">&#x2713;</span>' );
			} else {
				printf( '<span class="indicator-notvalid" style="margin-left: 2px;border: 1px solid red;border-radius: 25px;padding: 0px 5px;background: red;color: #fff;">X</span>' );
			}*/
			printf( '<span id="ind-valid" class="indicator-valid hidden" style="margin-left: 2px;border: 1px solid green;border-radius: 25px;padding: 0px 4px;background: green;color: #fff;">&#x2713;</span>');
            printf( '<span id="ind-nvalid" class="indicator-notvalid hidden" style="margin-left: 2px;border: 1px solid red;border-radius: 25px;padding: 0px 5px;background: red;color: #fff;">X</span>');
		}
	}

	function is_valid( $bazo_tracker ) {
		// scenario 1: empty
		if ( empty( $bazo_tracker ) ) {
			return false;
		}

		// scenario 2: incorrect format
		if ( ! preg_match( '/^\d{9}$/', $bazo_tracker ) ) {
			return false;
		}

		// passed successfully
		return true;
	}


	// Add scripts to wp_head()
	function bazo_header_script() {
		$bazo_options = get_option( 'bazo_options' );
		$bazo_tracker = $bazo_options['bazo_tracker'];
		if ( is_array( $bazo_options ) && array_key_exists( 'bazo_tracker', $bazo_options ) && ( $bazo_options['bazo_tracker'] != '' ) ) {
			if ( is_valid( $bazo_tracker ) ) {
				?>
                <!-- Bazo Tracker -->
                <script>
                    var _bazoid = 'BI-<?php echo $bazo_options['bazo_tracker']; ?>';
                    (function (d, o, u) {
                        a = d.createElement(o),
                            m = d.getElementsByTagName(o)[0];
                        a.async = 1;
                        a.src = u;
                        m.parentNode.insertBefore(a, m);
                    })(document, 'script', '//c.bazo.io/t.min.js');
                </script>
                <!-- END Bazo Tracker v.1.4 -->
			<?php }
		}
	}

	if ( is_admin() ) {
		$bazo_settings_page = new BazoSettingsPage();
	}

	add_action( 'wp_head', 'bazo_header_script' );


	/*	Dodanie shortcode*/


	function company_shortcode( $atts ) {
		$bazoAtts = shortcode_atts( array(
			'name'         => '',
			'lowernext'    => '0',
			'maxlength'    => '99999',
			'color'        => 'inherit',
			'prefix'       => '',
			'removestatut' => '0',
			'suffix'       => '',
		), $atts );

		$AltName      = esc_attr( $bazoAtts['name'] );
		$length       = esc_attr( $bazoAtts['maxlength'] );
		$lowerNext    = esc_attr( $bazoAtts['lowernext'] );
		$color        = esc_attr( $bazoAtts['color'] );
		$prefix       = esc_attr( $bazoAtts['prefix'] );
		$removeStatut = esc_attr( $bazoAtts['removestatut'] );
		$suffix       = esc_attr( $bazoAtts['suffix'] );

		return '<span class="bazo-company" color="' . $color . ';" maxlenght="' . $length . '" lowernext="' . $lowerNext . '" prefix="' . $prefix . '" suffix="' . $suffix . '" removestatut="' . $removeStatut . '">' . $AltName . '</span>';
	}

	add_shortcode( 'bazo-company', 'company_shortcode' );

	// Add scripts to wp_footer()
	function bazo_footer_script() { ?>
        <script>
            window.addEventListener('load', function () {
                let bazoInterval = setInterval(bazoReplacer, 300);

                function stopTimer() {
                    window.clearInterval(bazoInterval);
                }

                setTimeout(stopTimer, 30000);

                function bazoReplacer() {
                    let getBody = document.getElementsByTagName("body")[0];
                    let company = getBody.getAttribute("bazo-company");
                    if (!!company) {
                        stopTimer();
                        let elems = document.getElementsByClassName('bazo-company');
                        for (let i = 0; i < elems.length; i++) {
                            company = getBody.getAttribute("bazo-company");
                            let length = elems[i].attributes.maxlenght.value;
                            let color = elems[i].attributes.color.value;
                            let prefix = elems[i].attributes.prefix.value;
                            let suffix = elems[i].attributes.suffix.value;
                            let lowerNext = elems[i].attributes.lowerNext.value;
                            let removestatut = elems[i].attributes.removestatut.value;
                            if (removestatut == 1) {
                                let statutName = ['Sp. z o.o.', 'sp. z o.o.', 'spółka z o.o.', 'SP. Z O.O.', 'Spółka z o.o.', 'Sp. Z O.o.', 'S.A.', 's.a.', 'SA', 'sp. j.', 'Sp. j.', 'Sp.j.', 'Sp. J.', 'sp.p.', 'Ltd.', 'Ltd', 'L.L.C.',
                                    'LLC', ' Inc.', ' Inc', 'Corp.', 'Corp', 'GmbH', 'sp.k.', 'Sp.k.', 'Sp. k.', 'sp. k.', 'Sp. K.', 'S.K.A.', 's.k.a.', 's.r.o.', 'S.R.O', 'S.K.', 'S. K.', 'GmbH & Co.', 'Co.', 'KG', 's.c.', 'S.C.',
                                    'S.A.S', 'a.s.', 'a. s.', 'BV', 'B.V.', 'PPHU', 'AG', 'LLP', 's.r.o.', 'S.p.A.', 'j.v', 'e.V.', 'A / S', 'A/S', 'AB', 'B.V.', 's.r.l.', 'S.R.L', 'SL', 'oHG', 'Sp. k.wa'];
                                statutName.forEach(function (item) {
                                    company = company.replace(' ' + item, '');
                                });
                            }
                            if (length < company.length) {
                                elems[i].innerHTML = company.substring(0, length) + '...</span>';
                                company = company.substring(0, length) + '...</span>';
                            } else {
                                elems[i].innerHTML = company + '</span>';
                                company = elems[i].innerHTML + '</span>';
                            }
                            let parentNode = elems[i].parentNode.innerHTML;
                            let toChange = parentNode.split(company).pop();
                            toChange = toChange.trim();
                            let changedString = parentNode;
                            if (lowerNext == 1) {
                                let toChangelow = toChange.charAt(0).toLowerCase() + toChange.slice(1);
                                changedString = parentNode.replace(toChange, toChangelow);
                            }
                            if (prefix.length > 0) {
                                var companySpan = prefix + ' <span style="color:' + color + '">' + company;
                            } else {
                                companySpan = '<span style="color:' + color + '">' + company;
                            }
                            if (suffix.length > 0){
                                companySpan = companySpan + suffix;
                            }
                            changedString = changedString.replace(company, companySpan);
                            elems[i].parentNode.innerHTML = changedString;
                        }
                    }
                }
            });

        </script>

	<?php }

	add_action( 'wp_footer', 'bazo_footer_script' );


	function enqueue_plugin_scripts( $plugin_array ) {
		//enqueue TinyMCE plugin script with its ID.
		$plugin_array["bazo_shortcode_button_plugin"] = plugin_dir_url( __FILE__ ) . "shortcode-editor.js";

		return $plugin_array;
	}

	add_filter( "mce_external_plugins", "enqueue_plugin_scripts" );

	function register_buttons_editor( $buttons ) {
		//register buttons with their id.
		array_push( $buttons, "bazo" );

		return $buttons;
	}

	add_filter( "mce_buttons", "register_buttons_editor" );


?>
