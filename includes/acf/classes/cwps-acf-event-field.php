<?php
/**
 * CiviCRM Event Field Class.
 *
 * Handles CiviCRM Event Field functionality.
 *
 * @package CiviCRM_WP_Profile_Sync
 * @since 0.5.4
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * CiviCRM Profile Sync CiviCRM Event Field Class.
 *
 * A class that encapsulates CiviCRM Event Field functionality.
 *
 * @since 0.5.4
 */
class CiviCRM_Profile_Sync_ACF_CiviCRM_Event_Field {

	/**
	 * Plugin object.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var object $plugin The plugin object.
	 */
	public $plugin;

	/**
	 * ACF Loader object.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var object $acf_loader The ACF Loader object.
	 */
	public $acf_loader;

	/**
	 * Parent (calling) object.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var object $civicrm The parent object.
	 */
	public $civicrm;

	/**
	 * Built-in Event Fields.
	 *
	 * These are mapped to their corresponding ACF Field types.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $event_fields The public Event Fields.
	 */
	public $event_fields = [
		'id' => 'number',
		'title' => 'text',
		'summary' => 'textarea',
		'description' => 'wysiwyg',
		'created_date' => 'date_time_picker',
		'start_date' => 'date_time_picker',
		'end_date' => 'date_time_picker',
		'is_map' => 'true_false',
		'is_public' => 'true_false',
		'is_active' => 'true_false',
		'is_share' => 'true_false',
		'max_participants' => 'number',
		'event_full_text' => 'textarea',
		'has_waitlist' => 'true_false',
		'waitlist_text' => 'textarea',
	];

	/**
	 * Event Location Address Fields.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $location_address_fields The Event Location Address Fields.
	 */
	public $location_address_fields = [
		'street_address' => 'text',
		'supplemental_address_1' => 'text',
		'supplemental_address_2' => 'text',
		'supplemental_address_3' => 'text',
		'city' => 'text',
		'county_id' => 'select',
		'state_province_id' => 'select',
		'country_id' => 'select',
		'postal_code' => 'text',
		'geo_code_1' => 'text',
		'geo_code_2' => 'text',
		'name' => 'text',
	];

	/**
	 * Event Location Email Fields.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $location_email_fields The array of Event Location Email Fields.
	 */
	public $location_email_fields = [
		'email' => 'email',
	];

	/**
	 * Event Location Phone Fields.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $location_phone_fields The array of Event Location Phone Fields.
	 */
	public $location_phone_fields = [
		'phone' => 'text',
		'phone_ext' => 'text',
		//'phone_type_id' => 'select',
	];

	/**
	 * Event Fee Fields.
	 *
	 * These are mapped to their corresponding ACF Field types.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $fee_fields The Event Fee Fields.
	 */
	public $fee_fields = [
		'is_monetary' => 'true_false',
		'currency' => 'select',
		//'payment_processor' => 'select',
			// Pay Later.
			'is_pay_later' => 'true_false',
			'pay_later_text' => 'textarea',
			'pay_later_receipt' => 'wysiwyg',
			'is_billing_required' => 'true_false',
		'fee_label' => 'text',
		//'financial_type_id' => 'select',
		//'price_set_id' => 'select',
		// Internal "pseudo" Price Set.
		//'default_fee_id' => 'select',
		//'default_discount_fee_id' => 'select',
			// Partial Payment - CiviCRM admin only.
			//'is_partial_payment' => 'true_false',
			//'initial_amount_label' => 'text',
			//'initial_amount_help_text' => 'textarea',
			//'min_initial_amount' => 'number',
	];

	/**
	 * Online Registration Fields.
	 *
	 * These are mapped to their corresponding ACF Field types.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $registration_fields The Online Registration Fields.
	 */
	public $registration_fields = [
		'is_online_registration' => 'true_false',
		'registration_link_text' => 'text',
		'registration_start_date' => 'date_time_picker',
		'registration_end_date' => 'date_time_picker',
		'is_multiple_registrations' => 'true_false',
		'max_additional_participants' => 'select',
		'allow_same_participant_emails' => 'true_false',
		//'dedupe_rule_group_id' => 'select',

		// Needs Participant Statuses 8, 9 and 11 to be enabled.
		'requires_approval' => 'true_false',
		'approval_req_text' => 'textarea',

		'expiration_time' => 'number',
		'allow_selfcancelxfer' => 'true_false',
		'selfcancelxfer_time' => 'number',

		// Registration Screen.
		'intro_text' => 'wysiwyg',
		'footer_text' => 'wysiwyg',
		//'custom_pre_id' => 'select',
		//'custom_post_id' => 'select',
		//'additional_profile_pre_id' => 'select',
		//'additional_profile_post_id' => 'select',

		// Confirmation Screen.
		'is_confirm_enabled' => 'true_false',
		'confirm_title' => 'text',
		'confirm_text' => 'wysiwyg',
		'confirm_footer_text' => 'wysiwyg',

		// Thank-you Screen.
		'thankyou_title' => 'text',
		'thankyou_text' => 'wysiwyg',
		'thankyou_footer_text' => 'wysiwyg',

		// Confirmation Email.
		'is_email_confirm' => 'true_false',
		'confirm_email_text' => 'textarea',
		'confirm_from_name' => 'text',
		'confirm_from_email' => 'email',
		'cc_confirm' => 'email',
		'bcc_confirm' => 'email',
	];

	/**
	 * Unused Event Fields.
	 *
	 * These are mapped to their corresponding ACF Field types.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $event_fields_unused The unused Event Fields.
	 */
	public $event_fields_unused = [

		// Template.
		//'is_template' => 'true_false',
		//'template_title' => 'text',

		// Repeating Event.
		//'parent_event_id' => 'select',
		//'slot_label_id' => 'select',

	];

	/**
	 * Event Fields to add for the Bypass Location Rule.
	 *
	 * These are not mapped for Post Type Sync, but need to be added for the
	 * Bypass Location Rule.
	 *
	 * @since 0.5.4
	 * @access public
	 * @var array $bypass_fields The Event Fields to add for the Bypass Location Rule.
	 */
	public $bypass_fields = [
		'subject' => 'text',
		'details' => 'wysiwyg',
	];



	/**
	 * Constructor.
	 *
	 * @since 0.5.4
	 *
	 * @param object $parent The parent object reference.
	 */
	public function __construct( $parent ) {

		// Store references to objects.
		$this->plugin = $parent->acf_loader->plugin;
		$this->acf_loader = $parent->acf_loader;
		$this->civicrm = $parent;

		// Init when the ACF CiviCRM object is loaded.
		add_action( 'cwps/acf/civicrm/loaded', [ $this, 'initialise' ] );

	}



	/**
	 * Initialise this object.
	 *
	 * @since 0.5.4
	 */
	public function initialise() {

		// Register hooks.
		//$this->register_hooks();

	}



	/**
	 * Register hooks.
	 *
	 * @since 0.5.4
	 */
	public function register_hooks() {

		// Some Event "Text" Fields need their own validation.
		add_filter( 'acf/validate_value/type=text', [ $this, 'value_validate' ], 10, 4 );

		// Listen for queries from our ACF Field class.
		add_filter( 'cwps/acf/field_group/field/pre_update', [ $this, 'select_settings_modify' ], 20, 2 );
		add_filter( 'cwps/acf/field_group/field/pre_update', [ $this, 'date_time_picker_settings_modify' ], 20, 2 );

	}



	// -------------------------------------------------------------------------



	/**
	 * Validate the content of a Field.
	 *
	 * Some Event Fields require validation.
	 *
	 * @since 0.5.4
	 *
	 * @param bool $valid The existing valid status.
	 * @param mixed $value The value of the Field.
	 * @param array $field The Field data array.
	 * @param string $input The input element's name attribute.
	 * @return string|bool $valid A string to display a custom error message, boolean otherwise.
	 */
	public function value_validate( $valid, $value, $field, $input ) {

		// Bail if it's not required and is empty.
		if ( $field['required'] == '0' && empty( $value ) ) {
			return $valid;
		}

		// Get the mapped Event Field name if present.
		$event_field_name = $this->civicrm->event->event_field_name_get( $field );
		if ( $event_field_name === false ) {
			return $valid;
		}

		// Validate depending on the Field name.
		switch ( $event_field_name ) {

			case 'duration':
				// Must be an integer.
				if ( ! ctype_digit( $value ) ) {
					$valid = __( 'Must be an integer.', 'civicrm-wp-profile-sync' );
				}
				break;

		}

		// --<
		return $valid;

	}



	// -------------------------------------------------------------------------



	/**
	 * Get the value of a Event Field, formatted for ACF.
	 *
	 * @since 0.5.4
	 *
	 * @param mixed $value The Event Field value.
	 * @param array $name The Event Field name.
	 * @param string $selector The ACF Field selector.
	 * @param integer|string $post_id The ACF "Post ID".
	 * @return mixed $value The formatted Field value.
	 */
	public function value_get_for_acf( $value, $name, $selector, $post_id ) {

		// Bail if empty.
		if ( empty( $value ) ) {
			return $value;
		}

		// Bail if value is (string) 'null' which CiviCRM uses for some reason.
		if ( $value == 'null' ) {
			return '';
		}

		// Get the ACF type for this Event Field.
		$type = $this->get_acf_type( $name );

		// Convert CiviCRM value to ACF value by Event Field.
		switch ( $type ) {

			// Unused at present.
			case 'select':
			case 'checkbox':

				// Convert if the value has the special CiviCRM array-like format.
				if ( false !== strpos( $value, CRM_Core_DAO::VALUE_SEPARATOR ) ) {
					$value = CRM_Utils_Array::explodePadded( $value );
				}

				break;

			// Used by "Birth Date" and "Deceased Date".
			case 'date_picker':
			case 'date_time_picker':

				// Get Field setting.
				$acf_setting = get_field_object( $selector, $post_id );

				// Date Picker test.
				if ( $acf_setting['type'] == 'date_picker' ) {

					// Event edit passes a Y-m-d format, so test for that.
					$datetime = DateTime::createFromFormat( 'Y-m-d', $value );

					// Event create passes a different format, so test for that.
					if ( $datetime === false ) {
						$datetime = DateTime::createFromFormat( 'YmdHis', $value );
					}

					// Convert to ACF format.
					$value = $datetime->format( 'Ymd' );

				// Date & Time Picker test.
				} elseif ( $acf_setting['type'] == 'date_time_picker' ) {

					// Event edit passes a YmdHis format, so test for that.
					$datetime = DateTime::createFromFormat( 'YmdHis', $value );

					// Event API passes a different format, so test for that.
					if ( $datetime === false ) {
						$datetime = DateTime::createFromFormat( 'Y-m-d H:i:s', $value );
					}

					// Convert to ACF format.
					$value = $datetime->format( 'Y-m-d H:i:s' );

				}

				break;

		}

		// TODO: Filter here?

		// --<
		return $value;

	}



	// -------------------------------------------------------------------------



	/**
	 * Get the "select" options for a given CiviCRM Event Field.
	 *
	 * @since 0.5.4
	 *
	 * @param string $name The name of the Event Field.
	 * @return array $options The array of Field options.
	 */
	public function options_get( $name ) {

		// Init return.
		$options = [];

		// We only have a few to account for.

		// Event Type ID.
		if ( $name == 'event_type_id' ) {
			$options = $this->civicrm->event_type->choices_get();
		}

		// Participant Listing ID.
		if ( $name == 'participant_listing_id' ) {
			$option_group = $this->plugin->civicrm->option_group_get( 'participant_listing' );
			if ( ! empty( $option_group ) ) {
				$options = CRM_Core_OptionGroup::valuesByID( $option_group['id'] );
			}
		}

		// --<
		return $options;

	}



	// -------------------------------------------------------------------------



	/**
	 * Get the CiviCRM Event Fields for an ACF Field.
	 *
	 * @since 0.5.4
	 *
	 * @param array $field The ACF Field data array.
	 * @return array $event_fields The array of Event Fields.
	 */
	public function get_for_acf_field( $field ) {

		// Init return.
		$event_fields = [];

		// Get Field Group for this Field's parent.
		$field_group = $this->acf_loader->acf->field_group->get_for_field( $field );

		// Bail if there's no Field Group.
		if ( empty( $field_group ) ) {
			return $event_fields;
		}

		// Bail if this is not a Event Field Group.
		$is_event_field_group = $this->civicrm->event->is_event_field_group( $field_group );
		if ( $is_event_field_group === false ) {
			return $event_fields;
		}

		// TODO: Do we need this loop?

		// Loop through the Post Types.
		foreach ( $is_event_field_group as $post_type_name ) {

			// Get public Fields of this type.
			$event_fields_for_type = $this->data_get( $field['type'], 'public' );

			// Merge with return array.
			$event_fields = array_merge( $event_fields, $event_fields_for_type );

		}

		// --<
		return $event_fields;

	}



	// -------------------------------------------------------------------------



	/**
	 * Get the Event Field options for a given Field ID.
	 *
	 * @since 0.5.4
	 *
	 * @param string $name The name of the Field.
	 * @param string $action The name of the Action.
	 * @return array $field The array of Field data.
	 */
	public function get_by_name( $name, $action = 'get' ) {

		// Init return.
		$field = [];

		// Try and init CiviCRM.
		if ( ! $this->civicrm->is_initialised() ) {
			return $field;
		}

		// Construct params.
		$params = [
			'version' => 3,
			'name' => $name,
			'action' => 'get',
		];

		// Add action.
		$params['action'] = $action;

		// Call the API.
		$result = civicrm_api( 'Event', 'getfield', $params );

		// Bail if there's an error.
		if ( ! empty( $result['is_error'] ) && $result['is_error'] == 1 ) {
			return $field;
		}

		// Bail if there are no results.
		if ( empty( $result['values'] ) ) {
			return $field;
		}

		// The result set is the item.
		$field = $result['values'];

		// --<
		return $field;

	}



	/**
	 * Get the core Fields for a CiviCRM Event Type.
	 *
	 * @since 0.5.4
	 *
	 * @param string $field_type The type of ACF Field.
	 * @param string $filter The token by which to filter the array of Fields.
	 * @return array $fields The array of Field names.
	 */
	public function data_get( $field_type = '', $filter = 'none' ) {

		// Only do this once per Field Type and filter.
		static $pseudocache;
		if ( isset( $pseudocache[ $filter ][ $field_type ] ) ) {
			return $pseudocache[ $filter ][ $field_type ];
		}

		// Init return.
		$fields = [];

		// Try and init CiviCRM.
		if ( ! $this->civicrm->is_initialised() ) {
			return $fields;
		}

		// Construct params.
		$params = [
			'version' => 3,
			'api_action' => 'create',
			'options' => [
				'limit' => 0, // No limit.
			],
		];

		// Call the API.
		$result = civicrm_api( 'Event', 'getfields', $params );

		// Override return if we get some.
		if ( $result['is_error'] == 0 && ! empty( $result['values'] ) ) {

			// Check for no filter.
			if ( $filter == 'none' ) {

				// Grab all of them.
				$fields = $result['values'];

			// Check public filter.
			} elseif ( $filter == 'public' ) {

				// Skip all but those defined in our Event Fields array.
				$public_fields = [];
				foreach ( $result['values'] as $key => $value ) {
					if ( array_key_exists( $value['name'], $this->event_fields ) ) {
						$public_fields[] = $value;
					}
				}

				// Skip all but those mapped to the type of ACF Field.
				foreach ( $public_fields as $key => $value ) {
					if ( $field_type == $this->event_fields[ $value['name'] ] ) {
						$fields[] = $value;
					}
				}

			}

		}

		// Maybe add to pseudo-cache.
		if ( ! isset( $pseudocache[ $filter ][ $field_type ] ) ) {
			$pseudocache[ $filter ][ $field_type ] = $fields;
		}

		// --<
		return $fields;

	}



	/**
	 * Get the core Fields for all CiviCRM Event Types.
	 *
	 * @since 0.5.4
	 *
	 * @param string $filter The token by which to filter the array of Fields.
	 * @param string $action The name of the API action, e.g. 'create'.
	 * @return array $fields The array of Field names.
	 */
	public function data_get_filtered( $filter = 'none', $action = '' ) {

		// Maybe set a key for the subtype.
		$index = $action;
		if ( empty( $action ) ) {
			$index = 'all';
		}

		// Only do this once per filter.
		static $pseudocache;
		if ( isset( $pseudocache[ $filter ][ $index ] ) ) {
			return $pseudocache[ $filter ][ $index ];
		}

		// Init return.
		$fields = [];

		// Try and init CiviCRM.
		if ( ! $this->civicrm->is_initialised() ) {
			return $fields;
		}

		// Construct params.
		$params = [
			'version' => 3,
			'options' => [
				'limit' => 0, // No limit.
			],
		];

		// Maybe add action.
		if ( ! empty( $action ) ) {
			$params['api_action'] = $action;
		}

		// Call the API.
		$result = civicrm_api( 'Event', 'getfields', $params );

		// Override return if we get some.
		if ( $result['is_error'] == 0 && ! empty( $result['values'] ) ) {

			// Check for no filter.
			if ( $filter == 'none' ) {

				// Grab all of them.
				$fields = $result['values'];

			// Check "public" filter.
			} elseif ( $filter == 'public' ) {

				// Skip all but those defined in our Event Fields array.
				$filtered = [];
				foreach ( $result['values'] as $key => $value ) {
					if ( array_key_exists( $value['name'], $this->event_fields ) ) {
						$filtered[] = $value;
					}
				}

				// Maybe order them by our Event Fields array.
				if ( ! empty( $filtered ) ) {
					foreach ( $this->event_fields as $key => $field_type ) {
						foreach ( $filtered as $value ) {
							if ( $value['name'] === $key ) {
								$fields[] = $value;
								break;
							}
						}
					}
				}

			// Check Fee filter.
			} elseif ( $filter == 'fee' ) {

				// Skip all but those defined in our Event Fields array.
				$filtered = [];
				foreach ( $result['values'] as $key => $value ) {
					if ( array_key_exists( $value['name'], $this->fee_fields ) ) {
						$filtered[] = $value;
					}
				}

				// Maybe order them by our Event Fee Fields array.
				if ( ! empty( $filtered ) ) {
					foreach ( $this->fee_fields as $key => $field_type ) {
						foreach ( $filtered as $value ) {
							if ( $value['name'] === $key ) {
								$fields[] = $value;
								break;
							}
						}
					}
				}

			// Check Registration filter.
			} elseif ( $filter == 'registration' ) {

				// Skip all but those defined in our Online Registration Fields array.
				$filtered = [];
				foreach ( $result['values'] as $key => $value ) {
					if ( array_key_exists( $value['name'], $this->registration_fields ) ) {
						$filtered[] = $value;
					}
				}

				// Maybe order them by our Online Registration Fields array.
				if ( ! empty( $filtered ) ) {
					foreach ( $this->registration_fields as $key => $field_type ) {
						foreach ( $filtered as $value ) {
							if ( $value['name'] === $key ) {
								$fields[] = $value;
								break;
							}
						}
					}
				}

			}

		}

		// Maybe add to pseudo-cache.
		if ( ! isset( $pseudocache[ $filter ][ $index ] ) ) {
			$pseudocache[ $filter ][ $index ] = $fields;
		}

		// --<
		return $fields;

	}



	/**
	 * Get the public Fields for all CiviCRM Event Types.
	 *
	 * @since 0.5.4
	 *
	 * @param string $action The name of the API action, e.g. 'create'.
	 * @return array $public_fields The array of CiviCRM Fields.
	 */
	public function get_public_fields( $action ) {

		// Init return.
		$public_fields = [];

		// Get the public Fields for all CiviCRM Event Types.
		$public_fields = $this->data_get_filtered( 'public', $action );

		// --<
		return $public_fields;

	}



	/**
	 * Get the Event Location Address Fields.
	 *
	 * @since 0.5.4
	 *
	 * @param string $action The name of the API action, e.g. 'create'.
	 * @return array $location_address_fields The array of CiviCRM Fields.
	 */
	public function get_location_address_fields( $action ) {

		// Init return.
		$location_address_fields = [];

		// Get all Address Fields.
		$address_fields = $this->civicrm->address->civicrm_fields_get();

		// Skip all but those defined in our Location Fields array.
		$filtered = [];
		foreach ( $address_fields as $key => $value ) {
			if ( array_key_exists( $value['name'], $this->location_address_fields ) ) {
				$filtered[] = $value;
			}
		}

		// Maybe order them by our Location Fields array.
		if ( ! empty( $filtered ) ) {
			foreach ( $this->location_address_fields as $key => $field_type ) {
				foreach ( $filtered as $value ) {
					if ( $value['name'] === $key ) {
						$location_address_fields[] = $value;
						break;
					}
				}
			}
		}

		// --<
		return $location_address_fields;

	}



	/**
	 * Get the Event Location Email Fields.
	 *
	 * @since 0.5.4
	 *
	 * @param string $action The name of the API action, e.g. 'create'.
	 * @return array $location_email_fields The array of CiviCRM Fields.
	 */
	public function get_location_email_fields( $action ) {

		// Init return.
		$location_email_fields = [];

		// Get all Email Fields.
		$email_fields = $this->civicrm->email->civicrm_fields_get();

		// Skip all but those defined in our Location Fields array.
		$filtered = [];
		foreach ( $email_fields as $key => $value ) {
			if ( array_key_exists( $value['name'], $this->location_email_fields ) ) {
				$filtered[] = $value;
			}
		}

		// Maybe order them by our Location Fields array.
		if ( ! empty( $filtered ) ) {
			foreach ( $this->location_email_fields as $key => $field_type ) {
				foreach ( $filtered as $value ) {
					if ( $value['name'] === $key ) {
						$location_email_fields[] = $value;
						break;
					}
				}
			}
		}

		// --<
		return $location_email_fields;

	}



	/**
	 * Get the Event Location Phone Fields.
	 *
	 * @since 0.5.4
	 *
	 * @param string $action The name of the API action, e.g. 'create'.
	 * @return array $location_phone_fields The array of CiviCRM Fields.
	 */
	public function get_location_phone_fields( $action ) {

		// Init return.
		$location_phone_fields = [];

		// Get all Phone Fields.
		$phone_fields = $this->civicrm->phone->civicrm_fields_get();

		// Skip all but those defined in our Location Fields array.
		$filtered = [];
		foreach ( $phone_fields as $key => $value ) {
			if ( array_key_exists( $value['name'], $this->location_phone_fields ) ) {
				$filtered[] = $value;
			}
		}

		// Maybe order them by our Location Fields array.
		if ( ! empty( $filtered ) ) {
			foreach ( $this->location_phone_fields as $key => $field_type ) {
				foreach ( $filtered as $value ) {
					if ( $value['name'] === $key ) {
						$location_phone_fields[] = $value;
						break;
					}
				}
			}
		}

		// --<
		return $location_phone_fields;

	}



	/**
	 * Get the Fields for an ACF Field and mapped to a CiviCRM Event Type.
	 *
	 * @since 0.5.4
	 *
	 * @param string $type The type of ACF Field.
	 * @return array $fields The array of Field names.
	 */
	public function get_by_acf_type( $type = '' ) {

		// Init return.
		$event_fields = [];

		// Skip all but those mapped to the type of ACF Field.
		foreach ( $this->event_fields as $key => $value ) {
			if ( $type == $value ) {
				$event_fields[ $key ] = $value;
			}
		}

		// --<
		return $event_fields;

	}



	/**
	 * Get the ACF Field Type for a Event Field.
	 *
	 * @since 0.5.4
	 *
	 * @param string $name The name of the Event Field.
	 * @return array $fields The array of Field names.
	 */
	public function get_acf_type( $name = '' ) {

		// Init return.
		$type = false;

		// If the key exists, return the value - which is the ACF Type.
		if ( array_key_exists( $name, $this->event_fields ) ) {
			$type = $this->event_fields[ $name ];
		}

		// --<
		return $type;

	}



	// -------------------------------------------------------------------------



	/**
	 * Modify the Settings of an ACF "Select" Field.
	 *
	 * @since 0.5.4
	 *
	 * @param array $field The existing ACF Field data array.
	 * @param array $field_group The ACF Field Group data array.
	 * @return array $field The modified ACF Field data array.
	 */
	public function select_settings_modify( $field, $field_group ) {

		// Bail early if not our Field Type.
		if ( 'select' !== $field['type'] ) {
			return $field;
		}

		// Skip if the CiviCRM Field key isn't there or isn't populated.
		$key = $this->civicrm->acf_field_key_get();
		if ( ! array_key_exists( $key, $field ) || empty( $field[ $key ] ) ) {
			return $field;
		}

		// Get the mapped Event Field name if present.
		$event_field_name = $this->civicrm->event->event_field_name_get( $field );
		if ( $event_field_name === false ) {
			return $field;
		}

		// Get keyed array of settings.
		$field['choices'] = $this->options_get( $event_field_name );

		// Set a default for "Event Status".
		if ( $event_field_name == 'status_id' || $event_field_name == 'event_status_id' ) {
			$status_id_default = $this->civicrm->option_value_default_get( 'event_status' );
			if ( $status_id_default !== false ) {
				$field['default_value'] = $status_id_default;
			}
		}

		// Set a default for "Activity Medium".
		if ( $event_field_name == 'medium_id' || $event_field_name == 'event_medium_id' ) {
			$medium_id_default = $this->civicrm->option_value_default_get( 'encounter_medium' );
			if ( $medium_id_default !== false ) {
				$field['default_value'] = $medium_id_default;
			}
		}

		// --<
		return $field;

	}



	/**
	 * Modify the Settings of an ACF "Date Time Picker" Field.
	 *
	 * @since 0.5.4
	 *
	 * @param array $field The existing ACF Field data array.
	 * @param array $field_group The ACF Field Group data array.
	 * @return array $field The modified ACF Field data array.
	 */
	public function date_time_picker_settings_modify( $field, $field_group ) {

		// Bail early if not our Field Type.
		if ( 'date_time_picker' !== $field['type'] ) {
			return $field;
		}

		// Skip if the CiviCRM Field key isn't there or isn't populated.
		$key = $this->civicrm->acf_field_key_get();
		if ( ! array_key_exists( $key, $field ) || empty( $field[ $key ] ) ) {
			return $field;
		}

		// Get the mapped Event Field name if present.
		$event_field_name = $this->civicrm->event->event_field_name_get( $field );
		if ( $event_field_name === false ) {
			return $field;
		}

		// Try and get CiviCRM format.
		//$civicrm_format = $this->date_time_format_get( $event_field_name );

		// Set just the "Display Format" attribute.
		$field['display_format'] = 'Y-m-d H:i:s';

		// --<
		return $field;

	}



	/**
	 * Get the CiviCRM "DateTime format" for a given CiviCRM Event Field.
	 *
	 * There is such a horrible mismatch between CiviCRM datetime formats and
	 * PHP datetime formats that I've given up trying to translate them.
	 *
	 * @since 0.5.4
	 *
	 * @param string $name The name of the Event Field.
	 * @return string $format The DateTime format.
	 */
	public function date_time_format_get( $name ) {

		// Init return.
		$format = '';

		// We only have a few to account for.
		$date_fields = [ 'created_date', 'modified_date', 'event_date_time' ];

		// If it's one of our Fields.
		if ( in_array( $name, $date_fields ) ) {

			// Get the "Event Date Time" preference.
			$format = CRM_Utils_Date::getDateFormat( 'eventDateTime' );

			// Override if we get the default.
			$config = CRM_Core_Config::singleton();
			if ( $config->dateInputFormat == $format ) {
				$format = '';
			}

		}

		// If it's empty, fall back to a sensible CiviCRM-formatted setting.
		if ( empty( $format ) ) {
			$format = 'yy-mm-dd';
		}

		// --<
		return $format;

	}



} // Class ends.



