<?php

class CoursePress_View_Admin_Setting_General {

	public static function init() {

		add_filter( 'coursepress_settings_tabs', array( __CLASS__, 'add_tabs' ) );
		add_action( 'coursepress_settings_process_general', array( __CLASS__, 'process_form' ), 10, 2 );
		add_filter( 'coursepress_settings_render_tab_general', array( __CLASS__, 'return_content' ), 10, 3 );
	}


	public static function add_tabs( $tabs ) {

		$tabs['general'] = array(
			'title' => __( 'General Settings', 'cp' ),
			'description' => __( 'Configure the general settings for CoursePress.', 'cp' ),
			'order' => 0,// first tab
		);

		return $tabs;
	}

	public static function return_content( $content, $slug, $tab ) {
		$my_course_prefix = __( 'my-course', 'cp' );
		$my_course_prefix = sanitize_text_field( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) . '/'. $my_course_prefix;
		$page_dropdowns = array();

		$pages_args = array(
			'selected' => CoursePress_Core::get_setting( 'pages/enrollment', 0 ),
			'echo' => 0,
			'show_option_none' => __( 'Use virtual page', 'cp' ),
			'option_none_value' => 0,
			'name' => 'coursepress_settings[pages][enrollment]',
		);
		$page_dropdowns['enrollment'] = wp_dropdown_pages( $pages_args );

		$pages_args['selected'] = CoursePress_Core::get_setting( 'pages/login', 0 );
		$pages_args['name'] = 'coursepress_settings[pages][login]';
		$page_dropdowns['login'] = wp_dropdown_pages( $pages_args );

		$pages_args['selected'] = CoursePress_Core::get_setting( 'pages/signup', 0 );
		$pages_args['name'] = 'coursepress_settings[pages][signup]';
		$page_dropdowns['signup'] = wp_dropdown_pages( $pages_args );

		$pages_args['selected'] = CoursePress_Core::get_setting( 'pages/student_dashboard', 0 );
		$pages_args['name'] = 'coursepress_settings[pages][student_dashboard]';
		$page_dropdowns['student_dashboard'] = wp_dropdown_pages( $pages_args );

		$pages_args['selected'] = CoursePress_Core::get_setting( 'pages/student_settings', 0 );
		$pages_args['name'] = 'coursepress_settings[pages][student_settings]';
		$page_dropdowns['student_settings'] = wp_dropdown_pages( $pages_args );

		$content = '
			<input type="hidden" name="page" value="' . esc_attr( $slug ) . '"/>
			<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '"/>
			<input type="hidden" name="action" value="updateoptions"/>
		' . wp_nonce_field( 'update-coursepress-options', '_wpnonce', true, false ) . '
				<!-- SLUGS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Slugs', 'cp' ) . '</span></h3>
				<p class="description">' . sprintf( __( 'A slug is a few words that describe a post or a page. Slugs are usually a URL friendly version of the post title ( which has been automatically generated by WordPress ), but a slug can be anything you like. Slugs are meant to be used with %s as they help describe what the content at the URL is. Post slug substitutes the %s placeholder in a custom permalink structure.', 'cp' ), '<a href="options-permalink.php">permalinks</a>', '<strong>"%posttitle%"</strong>' ) . '</p>
				<div class="inside">

					<table class="form-table slug-settings">
						<tbody>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Courses Slug', 'cp' ) . '</th>
								<td>' . esc_html( trailingslashit( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][course]" id="course_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) . '" />&nbsp;/
									<p class="description">' . esc_html( 'Your course URL will look like: ', 'cp' ) . esc_html( trailingslashit( home_url() ) ) . esc_html( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) . esc_html( '/my-course/', 'cp' ) . '</p>
								</td>
							</tr>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Course Category Slug', 'cp' ) . '</th>
								<td>' . esc_html( trailingslashit( home_url() ) . trailingslashit( esc_html( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) ) ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][category]" id="category_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/category', 'course_category' ) ) . '" />&nbsp;/
									<p class="description">' . esc_html__( 'Your course category URL will look like: ', 'cp' ) . trailingslashit( esc_url( home_url() ) ) . esc_html( CoursePress_Core::get_setting( 'slugs/course', 'courses' ) . '/' . CoursePress_Core::get_setting( 'slugs/category', 'course_category' ) ) . esc_html__( '/your-category/', 'cp' ) . '</p>
								</td>
							</tr>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Units Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][units]" id="units_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/units', 'units' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Course Notifications Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][notifications]" id="notifications_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/notifications', 'notifications' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Course Discussions Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][discussions]" id="discussions_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/discussions', 'discussion' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'Course New Discussion Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . trailingslashit( esc_attr( CoursePress_Core::get_setting( 'slugs/discussions', 'discussion' ) ) ) .'
									&nbsp;<input type="text" name="coursepress_settings[slugs][discussions_new]" id="discussions_new_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/discussions_new', 'add_new_discussion' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Course Grades Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][grades]" id="grades_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/grades', 'grades' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Course Workbook Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . trailingslashit( esc_html( $my_course_prefix ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][workbook]" id="workbook_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/workbook', 'workbook' ) ) . '" />&nbsp;/
								</td>
							</tr>

							<tr class="hidden" valign="top" class="break">
								<th scope="row">' . esc_html__( 'Enrollment Process Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][enrollment]" id="enrollment_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/enrollment', 'enrollment_process' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr class="hidden" valign="top">
								<th scope="row">' . esc_html__( 'Enrollment Process Page', 'cp' ) . '</th>
								<td>' .  self::add_page_dropdown_description( $page_dropdowns['enrollment'], 'enrollment_process' ).  '</td>
							</tr>

							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Login Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][login]" id="login_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/login', 'student-login' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'Login Page', 'cp' ) . '</th>
								<td>' .  self::add_page_dropdown_description( $page_dropdowns['login'], 'student_login' ). '</td>
							</tr>

							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Signup Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][signup]" id="signup_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/signup', 'courses-signup' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'Signup Page', 'cp' ) . '</th>
								<td>' .  self::add_page_dropdown_description( $page_dropdowns['signup'], 'student_signup' ).  '</td>
							</tr>

							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Student Dashboard Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][student_dashboard]" id="student_dashboard_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/student_dashboard', 'courses-dashboard' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'Student Dashboard Page', 'cp' ) . '</th>
								<td>' .  self::add_page_dropdown_description( $page_dropdowns['student_dashboard'], 'student_dashboard' ). '</td>
							</tr>

							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Student Settings Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][student_settings]" id="student_settings_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/student_settings', 'student-settings' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'Student Settings Page', 'cp' ) . '</th>
								<td>' .  self::add_page_dropdown_description( $page_dropdowns['student_settings'], 'student_settings' ). '</td>
							</tr>

							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Instructor Profile Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][instructor_profile]" id="instructor_profile_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/instructor_profile', 'instructor' ) ) . '" />&nbsp;/
								</td>
							</tr>';

		if ( function_exists( 'messaging_init' ) ) {

			$content .= '
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Messaging: Inbox Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][inbox]" id="inbox_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/inbox', 'student-inbox' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'Sent Messages Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][sent_messages]" id="sent_messages" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/sent_messages', 'student-sent-messages' ) ) . '" />&nbsp;/
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">' . esc_html__( 'New Messages Slug', 'cp' ) . '</th>
								<td>' . trailingslashit( esc_url( home_url() ) ) . '
									&nbsp;<input type="text" name="coursepress_settings[slugs][new_messages]" id="new_messages_slug" value="' . esc_attr( CoursePress_Core::get_setting( 'slugs/new_messages', 'student-new-message' ) ) . '" />&nbsp;/
								</td>
							</tr>
			';

		}

		$content .= '
						</tbody>
					</table>


				</div>

				<!-- THEME MENU ITEMS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Theme Menu Items', 'cp' ) . '</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Display Menu Items', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( '<div>Attach default CoursePress menu items ( Courses, Student Dashboard, Log Out ) to the <strong>Primary Menu</strong>.</div><div>Items can also be added from Appearance > Menus and the CoursePress panel.</div>', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/show_coursepress_menu', 1 ) ) ? 'checked' : '';
		$content .= '
									<input type="checkbox" name="coursepress_settings[general][show_coursepress_menu]" ' . $checked . ' />
									';

		if ( current_user_can( 'manage_options' ) ) {
			$menu_error = true;
			$locations = get_theme_mod( 'nav_menu_locations' );
			if ( is_array( $locations ) ) {
				foreach ( $locations as $location => $value ) {
					if ( $value > 0 ) {
						$menu_error = false; // at least one is defined
					}
				}
			}
			if ( $menu_error ) {

				$content .= '
									<span class="settings-error">
									' . __( 'Please add at least one menu and select its theme location in order to show CoursePress menu items automatically.', 'cp' ) . '
									</span>
				';

			}
		}

		$content .= '
								</td>
							</tr>
						</tbody>
					</table>

				</div>

				<!-- LOGIN FORM -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Login Form', 'cp' ) . '</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Use Custom Login Form', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( 'Uses a custom Login Form to keep students on the front-end of your site.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/use_custom_login', 1 ) ) ? 'checked' : '';
		$content .= '
									<input type="checkbox" name="coursepress_settings[general][use_custom_login]" ' . $checked . ' />
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- WP LOGING REDIRECTION -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'WordPress Login Redirect', 'cp' ) . '</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Redirect After Login', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( 'Redirect students to their Dashboard upon login via wp-login form.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/redirect_after_login', 1 ) ) ? 'checked' : '';
		$content .= '
									<input type="checkbox" name="coursepress_settings[general][redirect_after_login]" ' . $checked . ' />
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- PRIVACY -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Privacy', 'cp' ) . '</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Show Instructor Username in URL', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( 'If checked, instructors username will be shown in the url. Otherwise, hashed (MD5) version will be shown.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$checked = cp_is_true( CoursePress_Core::get_setting( 'instructor/show_username', 1 ) ) ? 'checked' : '';
		$content .= '
									<input type="checkbox" name="coursepress_settings[instructor][show_username]" ' . $checked . ' />
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- COURSE DETAILS PAGE -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Details Page', 'cp' ) . '</span></h3>
				<p class="description">' . __( 'Media to use when viewing course details.', 'cp' ) . '</p>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Media Type', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( '"Priority" - Use the media type below, with the other type as a fallback.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$selected_type = CoursePress_Core::get_setting( 'course/details_media_type', 'default' );
		$content .= '
									<select name="coursepress_settings[course][details_media_type]" class="widefat" id="course_details_media_type">
										<option value="default" ' . selected( $selected_type, 'default', false ) .'>' . __( 'Priority Mode (default)', 'cp' ) . '</option>
										<option value="video" ' . selected( $selected_type, 'video', false ) .'>' . __( 'Featured Video', 'cp' ) . '</option>
										<option value="image" ' . selected( $selected_type, 'image', false ) .'>' . __( 'List Image', 'cp' ) . '</option>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Priority', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( 'Example: Using "video", the featured video will be used if available. The listing image is a fallback.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$selected_priority = CoursePress_Core::get_setting( 'course/details_media_priority', 'default' );
		$content .= '
									<select name="coursepress_settings[course][details_media_priority]" class="widefat" id="course_details_media_priority">
										<option value="video" ' . selected( $selected_priority, 'video', false ) .'>' . __( 'Featured Video (image fallback)', 'cp' ) . '</option>
										<option value="image" ' . selected( $selected_priority, 'image', false ) .'>' . __( 'List Image (video fallback)', 'cp' ) . '</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- COURSE LISTINGS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Listings', 'cp' ) . '</span></h3>
				<p class="description">' . __( 'Media to use when viewing course listings (e.g. Courses page or Instructor page).', 'cp' ) . '</p>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Media Type', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( '"Priority" - Use the media type below, with the other type as a fallback.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$selected_type = CoursePress_Core::get_setting( 'course/listing_media_type', 'default' );
		$content .= '
									<select name="coursepress_settings[course][listing_media_type]" class="widefat" id="course_listing_media_type">
										<option value="default" ' . selected( $selected_type, 'default', false ) .'>' . __( 'Priority Mode (default)', 'cp' ) . '</option>
										<option value="video" ' . selected( $selected_type, 'video', false ) .'>' . __( 'Featured Video', 'cp' ) . '</option>
										<option value="image" ' . selected( $selected_type, 'image', false ) .'>' . __( 'List Image', 'cp' ) . '</option>
									</select>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Priority', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( 'Example: Using "video", the featured video will be used if available. The listing image is a fallback.', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';

		$selected_priority = CoursePress_Core::get_setting( 'course/listing_media_priority', 'default' );
		$content .= '
									<select name="coursepress_settings[course][listing_media_priority]" class="widefat" id="course_listing_media_priority">
										<option value="video" ' . selected( $selected_priority, 'video', false ) .'>' . __( 'Featured Video (image fallback)', 'cp' ) . '</option>
										<option value="image" ' . selected( $selected_priority, 'image', false ) .'>' . __( 'List Image (video fallback)', 'cp' ) . '</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>

				</div>

				<!-- COURSE IMAGES -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Images', 'cp' ) . '</span></h3>
				<p class="description">' . __( 'Size for (newly uploaded) course images.', 'cp' ) . '</p>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Image Width', 'cp' ) . '
								</th>
								<td>
									<input type="text" name="coursepress_settings[course][image_width]" value="' . esc_attr( CoursePress_Core::get_setting( 'course/image_width', 235 ) ) . '"/>
								</td>
							</tr>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Image Height', 'cp' ) . '
								</th>
								<td>
									<input type="text" name="coursepress_settings[course][image_height]" value="' . esc_attr( CoursePress_Core::get_setting( 'course/image_height', 225 ) ) . '"/>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- COURSE ORDER -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Course Order', 'cp' ) . '</span></h3>
				<p class="description">' . __( 'Order of courses in admin and on front.', 'cp' ) . '</p>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Order by', 'cp' ) . '
								</th>
								<td>';

		$selected_order = CoursePress_Core::get_setting( 'course/order_by', 'course_start_date' );

		$options = array(
			'post_date' => __( 'Post Date', 'cp' ),
			'start_date' => __( 'Course start date', 'cp' ),
			'enrollment_start_date' => __( 'Course enrollment start date', 'cp' ),
		);
		$content .= CoursePress_Helper_UI::select(
			'coursepress_settings[course][order_by]',
			$options,
			$selected_order,
			'widefat',
			'course_order_by'
		);

		$content .= '</td></tr>';
		$content .= '<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Direction', 'cp' ) . '
								</th>
								<td>';

		$selected_dir = CoursePress_Core::get_setting( 'course/order_by_direction', 'DESC' );
		$content .= '
									<select name="coursepress_settings[course][order_by_direction]" class="widefat" id="course_order_by_direction">
										<option value="DESC" ' . selected( $selected_dir, 'DESC', false ) .'>' . __( 'Descending', 'cp' ) . '</option>
										<option value="ASC" ' . selected( $selected_dir, 'ASC', false ) .'>' . __( 'Ascending', 'cp' ) . '</option>
									</select>
								</td>
							</tr>

<!-- Default course Enrollment Restrictions -->
							<tr valign="top" class="break">
								<th scope="row">' . esc_html__( 'Enrollment Restrictions', 'cp' ) . '</th>
								<td>';
		$enrollment_types = CoursePress_Data_Course::get_enrollment_types_array();
		$enrollment_type_default = CoursePress_Data_Course::get_enrollment_type_default();
		$selected = CoursePress_Core::get_setting( 'course/enrollment_type_default', $enrollment_type_default );
		$content .= CoursePress_Helper_UI::select( 'coursepress_settings[course][enrollment_type_default]', $enrollment_types, $selected, 'chosen-select medium' );
		$content .= sprintf( '<p class="description">%s</p>', __( 'Select the default limitations on accessing and enrolling in this course.', 'cp' ) );
		$content .= '
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- REPORTS -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Reports', 'cp' ) . '</span></h3>
				<p class="description">' . __( 'Select font which will be used in the PDF reports.', 'cp' ) . '</p>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Font', 'cp' ) . '
								</th>
								<td>';

		$reports_font = CoursePress_Core::get_setting( 'reports/font', 'helvetica' );
		$reports_font = empty( $reports_font ) ? 'helvetica' : $reports_font;
		$fonts = CoursePress_Helper_PDF::fonts();
		$content .= '
									<select name="coursepress_settings[reports][font]" class="widefat" id="course_order_by_direction">
					';

		foreach ( $fonts as $font_php => $font_name ) {
			if ( ! empty( $font_name ) ) {
				$font = str_replace( '.php', '', $font_php );
				$content .= '
										<option value="' . esc_attr( $font ) . '" ' . selected( $reports_font, $font, false ) . '>' . esc_html( $font_name ) . '</option>
				';
			}
		}
		$content .= '
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>';

		$content .= '<!-- schema.org -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'schema.org', 'cp' ) . '</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Add microdata syntax', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( '', 'cp' ) . '
										</div>
									</div>
								</th>
								<td>';
		$checked = cp_is_true( CoursePress_Core::get_setting( 'general/add_structure_data', 1 ) ) ? 'checked' : '';
		$content .= '
									<input type="checkbox" name="coursepress_settings[general][add_structure_data]" ' . $checked . ' />
				<p class="description">' . esc_html__( 'Add structure data to courses.', 'cp' ) . '</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>';

		/**
		 * Social Sharing
		 */
		$services = CoursePress_Helper_SocialMedia::get_social_sharing_array();
		$content .= '<!-- social-sharing.org -->
				<h3 class="hndle" style="cursor:auto;"><span>' . esc_html__( 'Social Sharing', 'cp' ) . '</span></h3>
				<div class="inside">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row">
								' . esc_html__( 'Social Sharing', 'cp' ) . '
									<a class="help-icon" href="#"></a>
									<div class="tooltip hidden">
										<div class="tooltip-before"></div>
										<div class="tooltip-button">&times;</div>
										<div class="tooltip-content">
											' . __( '', 'cp' ) . '
										</div>
									</div>
								</th>
								<td><ul>';
		foreach ( $services as $key => $label ) {
			$checked = cp_is_true( CoursePress_Core::get_setting( 'general/social_sharing/'.$key, 1 ) );
			$content .= sprintf(
				'<li><label><input type="checkbox" name="coursepress_settings[general][social_sharing][%s]" value="on" /%s /> %s</label></li>',
				esc_attr( $key ),
				checked( $checked, true, false ),
				esc_html( $label )
			);
		}
		$content .= '</ul></td></tr>';
		/**
		 * ebd table settings body
		 */
		$content .= '</tbody></table></div>';
		return $content;
	}

	public static function process_form( $page, $tab ) {

		if ( isset( $_POST['action'] ) && 'updateoptions' === $_POST['action'] && 'general' === $tab && wp_verify_nonce( $_POST['_wpnonce'], 'update-coursepress-options' ) ) {

			$settings = CoursePress_Core::get_setting( false ); // false returns all settings
			$post_settings = (array) $_POST['coursepress_settings'];
			// Now is a good time to make changes to $post_settings, especially to fix up unchecked checkboxes
			$post_settings['general']['show_coursepress_menu'] = isset( $post_settings['general']['show_coursepress_menu'] ) ? $post_settings['general']['show_coursepress_menu'] : 'off';
			$post_settings['general']['use_custom_login'] = isset( $post_settings['general']['use_custom_login'] ) ? $post_settings['general']['use_custom_login'] : 'off';
			$post_settings['general']['redirect_after_login'] = isset( $post_settings['general']['redirect_after_login'] ) ? $post_settings['general']['redirect_after_login'] : 'off';
			$post_settings['instructor']['show_username'] = isset( $post_settings['instructor']['show_username'] ) ? $post_settings['instructor']['show_username'] : false;
			$post_settings['general']['add_structure_data'] = isset( $post_settings['general']['add_structure_data'] ) ? $post_settings['general']['add_structure_data'] : 'off';
			/**
			 * Social Sharing
			 */
			$services = CoursePress_Helper_Socialmedia::get_social_sharing_array();
			foreach ( $services as $key => $label ) {
				if ( isset( $post_settings['general']['social_sharing'][ $key ] ) ) {
					$post_settings['general']['social_sharing'][ $key ] = 'on';
				} else {
					$post_settings['general']['social_sharing'][ $key ] = 'off';
				}
			}
			/**
			 * sanitize
			 */
			$post_settings = CoursePress_Helper_Utility::sanitize_recursive( $post_settings );
			// Don't replace settings if there is nothing to replace
			if ( ! empty( $post_settings ) ) {
				$new_settings = CoursePress_Core::merge_settings( $settings, $post_settings );
				CoursePress_Core::update_setting( false, $new_settings ); // false will replace all settings
				// Flush rewrite rules
				flush_rewrite_rules();
			}
		}
	}

	/**
	 * Small helper to display dropdown for some settings.
	 *
	 * @since 2.0.6
	 *
	 * @param string $dropdown Dropdown with list o pages.
	 * @param string $page Page attr for shortcode cp_pages.
	 * @return string
	 */
	private static function add_page_dropdown_description( $dropdown, $page ) {
		$shortcode = sprintf(
			'<input type="text" readonly="readonly" class="cp-sc-box" value="[cp_pages page=&quot;%s&quot;]" />',
			$page
		);
		if ( empty( $dropdown ) ) {
			return sprintf(
				__( 'Please <a href="%1$s">add new page</a> with shortcode: %2$s.', 'cp' ),
				esc_url( add_query_arg( 'post_type', 'page', admin_url( 'post-new.php' ) ) ),
				$shortcode
			);
		}
		$content = $dropdown;
		$content .= '<p class="description">';
		$text = __( 'Select page where you have %s shortcode or any other set of %s. Please note that slug for the page set above will not be used if "Use virtual page" is not selected.', 'cp' );
		$url = add_query_arg(
			array(
				'post_type' => CoursePress_Data_Course::get_post_type_name(),
				'page' => 'coursepress_settings',
				'tab' => 'shortcodes',
			),
			'edit.php'
		);
		$link_to_help = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $url ),
			esc_html__( 'shortcodes', 'cp' )
		);
		$content .= sprintf( $text, $shortcode, $link_to_help );
		$content .= '</p>';
		return $content;
	}
}