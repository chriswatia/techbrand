<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( ! function_exists( 'modal_theme_jugaad' ) ) {
	/**
	 * Function Name: modal_theme_youtube.
	 *
	 * @param  array  $atts    array parameters.
	 * @param  string $content string parameter.
	 * @return mixed          string parameter.
	 */
	function modal_theme_jugaad( $atts, $content = null ) {
		/**
		 * Define Variables.
		 */
		global $cp_form_vars;

		$style_id         = '';
		$settings_encoded = '';
		shortcode_atts(
			array(
				'style_id'         => '',
				'settings_encoded' => '',
			),
			$atts
		);
		$style_id         = isset( $atts['style_id'] ) ? $atts['style_id'] : '';
		$settings_encoded = $atts['settings_encoded'];

		$settings       = base64_decode( $settings_encoded );
		$style_settings = json_decode( $settings, true );

		foreach ( $style_settings as $key => $setting ) {
			$style_settings[ $key ] = apply_filters( 'smile_render_setting', $setting );
		}

		unset( $style_settings['style_id'] );

		// Generate UID.
		$uid       = uniqid();
		$uid_class = 'content-' . $uid;

		$individual_vars = array(
			'uid'         => $uid,
			'uid_class'   => $uid_class,
			'style_class' => 'cp-jugaad',
		);

		/**
		 * Merge short code variables arrays.
		 *
		 * @array   $individual_vars        Individual style EXTRA short-code variables.
		 * @array   $cp_form_vars           CP Form global short-code variables.
		 * @array   $style_settings         Individual style short-code variables.
		 * @array   $atts                   short-code attributes.
		 */
		$all = array_merge(
			$individual_vars,
			$cp_form_vars,
			$style_settings,
			$atts
		);

		/**
		 *  Extract short-code variables.
		 *
		 *  @array      $all         All merged arrays.
		 *  @array      array()      Its required as per WP. Merged $style_settings in $all.
		 */

		$a = shortcode_atts( $all, array() );

		$imagestyle  = cp_add_css( 'left', $a['image_horizontal_position'], 'px' );
		$imagestyle .= cp_add_css( 'top', $a['image_vertical_position'], 'px' );
		$imagestyle .= cp_add_css( 'max-width', $a['image_size'], 'px' );

		$cp_modal_img_custom_url = isset( $a['modal_img_custom_url'] ) ? $a['modal_img_custom_url'] : '';
		$cp_modal_img_src        = isset( $a['modal_img_src'] ) ? $a['modal_img_src'] : '';
		$cp_modal_image          = isset( $a['modal_image'] ) ? $a['modal_image'] : '';

		// Filters & Actions.
		$modal_image = cp_get_module_image_url_init( 'modal', $cp_modal_img_custom_url, $cp_modal_img_src, $cp_modal_image );

		// Filters & Actions for modal_image_alt.
		$modal_image_alt = cp_get_module_image_alt_init( 'modal', $cp_modal_img_src, $cp_modal_image );

		// Before filter.
		apply_filters_ref_array( 'cp_modal_global_before', array( $a ) );

		if ( ! isset( $a['form_bg_image_src'] ) ) {
			$a['form_bg_image_src'] = 'upload_img';
		}

		if ( ! isset( $a['content_bg_image_src'] ) ) {
			$a['content_bg_image_src'] = 'upload_img';
		}

		// Form background color and image.
		$form_bg_repeat  = '';
		$form_bg_pos     = '';
		$form_bg_size    = '';
		$form_bg_setting = '';
		$form_css        = '';
		if ( false !== strpos( $a['form_opt_bg'], '|' ) ) {
			$form_opt_bg    = explode( '|', $a['form_opt_bg'] );
			$form_bg_repeat = $form_opt_bg[0];
			$form_bg_pos    = $form_opt_bg[1];
			$form_bg_size   = $form_opt_bg[2];

			$form_bg_setting .= 'background-repeat: ' . $form_bg_repeat . ';';
			$form_bg_setting .= 'background-position: ' . $form_bg_pos . ';';
			$form_bg_setting .= 'background-size: ' . $form_bg_size . ';';
		}

		$form_bg_image = '';
		$form_bg_color = ( isset( $a['form_bg_color'] ) ) ? $a['form_bg_color'] : '';
		$row_classes   = '';
		$cp_equalized  = '';

		if ( 'upload_img' === $a['form_bg_image_src'] ) {
			$form_bg_image = apply_filters( 'cp_get_wp_image_url', $a['form_bg_image'] );
		} elseif ( 'custom_url' === $a['form_bg_image_src'] ) {
			$form_bg_image = $a['form_bg_image_custom_url'];
		} else {
			$form_bg_image = '';
		}

		// Form gradient color.
		$form_type_set    = false;
		$old_form_user    = true;
		$is_gradient_form = isset( $a['module_bg1_color_type'] ) ? $a['module_bg1_color_type'] : 'image';

		if ( '' !== $is_gradient_form ) {
			$form_bg_gradient = ( isset( $a['module_bg_gradient_one'] ) ) ? $a['module_bg_gradient_one'] : '';
			$form_type_set    = true;
			$old_form_user    = false;
		}

		// Content background color.
		$content_type_set    = false;
		$old_content_user    = true;
		$form_overaly_css    = '';
		$content_overaly_css = '';
		$is_gradient_content = isset( $a['module_bg2_color_type'] ) ? $a['module_bg2_color_type'] : 'image';

		if ( '' !== $is_gradient_content ) {
			$content_bg_gradient = ( isset( $a['module_bg_gradient_sec'] ) ) ? $a['module_bg_gradient_sec'] : '';
			$content_type_set    = true;
			$old_content_user    = false;
		}

		if ( ! $old_form_user && 'gradient' === $is_gradient_form && $form_type_set && 'jugaad' === $a['style'] ) {
			$form_overaly_css = generate_back_gradient( $form_bg_gradient );
		} else {
			$form_overaly_css = cp_add_css( 'background-color', $form_bg_color );
		}

		if ( '' !== $form_bg_image && 'image' === $is_gradient_form ) {
			$form_css .= 'background-image:url(' . $form_bg_image . ');' . $form_bg_setting . ';';
		}

		// Content background color and image.
		$content_bg_repeat  = '';
		$content_bg_pos     = '';
		$content_bg_size    = '';
		$content_bg_setting = '';
		$content_css        = '';
		if ( false !== strpos( $a['content_opt_bg'], '|' ) ) {
			$content_opt_bg    = explode( '|', $a['content_opt_bg'] );
			$content_bg_repeat = $content_opt_bg[0];
			$content_bg_pos    = $content_opt_bg[1];
			$content_bg_size   = $content_opt_bg[2];

			$content_bg_setting .= 'background-repeat: ' . $content_bg_repeat . ';';
			$content_bg_setting .= 'background-position: ' . $content_bg_pos . ';';
			$content_bg_setting .= 'background-size: ' . $content_bg_size . ';';
		}

		$content_bg_image = '';
		$content_bg_color = ( isset( $a['content_bg_color'] ) ) ? $a['content_bg_color'] : '';

		if ( 'upload_img' === $a['content_bg_image_src'] ) {
			$content_bg_image = apply_filters( 'cp_get_wp_image_url', $a['content_bg_image'] );
		} elseif ( 'custom_url' === $a['content_bg_image_src'] ) {
			$content_bg_image = $a['content_bg_image_custom_url'];
		} else {
			$content_bg_image = '';
		}

		if ( ! $old_content_user && 'gradient' === $is_gradient_content && $form_type_set && 'jugaad' === $a['style'] ) {
			$content_overaly_css = generate_back_gradient( $content_bg_gradient );
		} else {
			$content_overaly_css = cp_add_css( 'background-color', $content_bg_color );
		}

		if ( '' !== $content_bg_image && 'image' === $is_gradient_content ) {
			$content_css .= 'background-image:url(' . $content_bg_image . ');' . $content_bg_setting . ';';
		}

		if ( 'none' !== $a['form_separator'] ) {
			if ( '0' === $a['form_sep_part_of'] ) {
				$form_sep_part_of = 'part-of-content';
			} else {
				$form_sep_part_of = 'part-of-form';
			}

			$form_sep_position  = 'vertical';
			$form_sep_direction = 'upward';

			switch ( $a['modal_layout'] ) {

				case 'form_left':
					if ( '1' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'form_right':
					if ( '0' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'form_left_img_top':
					if ( '1' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'form_right_img_top':
					if ( '0' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'img_left_form_bottom':
					$form_sep_position = 'horizontal';
					if ( '0' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'img_right_form_bottom':
					$form_sep_position = 'horizontal';
					if ( '0' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'form_bottom_img_top':
					$form_sep_position = 'horizontal';
					if ( '0' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
				case 'form_bottom':
					$form_sep_position = 'horizontal';
					if ( '0' === $a['form_sep_part_of'] ) {
						$form_sep_direction = 'downward';
					}
					break;
			}

			$classes = $a['form_separator'] . ' ' . $a['modal_layout'] . ' ' . $form_sep_part_of . ' cp-fs-' . $form_sep_position . ' cp-fs-' . $form_sep_position . '-content ' . $form_sep_direction;

			// Start output buffer for form separator.
			ob_start();

			?>
			<div class="cp-form-separator <?php echo esc_attr( $classes ); ?>" data-form-sep-part="<?php echo esc_attr( $form_sep_part_of ); ?>"
				data-form-sep-pos="<?php echo esc_attr( $form_sep_position ); ?>" data-form-sep-direction="<?php echo esc_attr( $form_sep_direction ); ?>" data-form-sep="<?php echo esc_attr( $a['form_separator'] ); ?>">
			</div>
			<?php
			$form_separator = ob_get_clean();
		}

		$row_classes .= $a['modal_layout'];

		if ( 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] ) {
			$cp_equalized = 'cp-columns-equalized';
		}

		// Start output buffer for form section variable.
		ob_start();

		$text_container_class = 'cp-text-center';
		if ( 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] ) {
			$text_container_class = '';
		}
		?>
		<?php
		if ( 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] || 'form_bottom_img_top' === $a['modal_layout'] || 'form_bottom' === $a['modal_layout'] ) {
			?>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-form-section form-pos-bottom"  style="<?php echo esc_attr( $form_css ); ?>">
				<?php
		} else {
			if ( '0' === $a['modal_col_width'] ) {
				$row_classes .= ' form-one-by-two';
				$form_classes = 'col-md-6 col-sm-6 col-lg-6';
			} else {
				$row_classes .= ' form-one-third';
				$form_classes = 'col-md-5 col-sm-5 col-lg-5';
			}
			?>
			<div class="<?php echo esc_attr( $form_classes ); ?> col-xs-12 cp-form-section form-pos-right form-pos-<?php echo esc_attr( $a['modal_layout'] ); ?>"  style="<?php echo esc_attr( $form_css ); ?>">
				<?php } ?>
					<div class="cp-form-section-overlay" style="<?php echo esc_attr( $form_overaly_css ); ?>"></div>
					<div class="cp-short-title-container 
					<?php
					if ( '' === trim( $a['modal_short_title'] ) ) {
						echo 'cp-empty'; }
					?>
						">
						<div class="cp-short-title cp_responsive"><?php echo do_shortcode( html_entity_decode( $a['modal_short_title'] ) ); ?></div>
					</div>
					<div class="cp-form-container">
						<?php
						// Embed CP Form.
						apply_filters_ref_array( 'cp_get_form', array( $a ) );
						?>
					</div><!-- cp-form-container -->
					<div class="modal-note-container 
					<?php
					if ( '' === trim( $a['modal_note_1'] ) ) {
						echo 'cp-empty'; }
					?>
						">
						<div class="cp-modal-note cp_responsive"><?php echo do_shortcode( html_entity_decode( $a['modal_note_1'] ) ); ?></div>
					</div>
				</div><!-- cp-form-section -->
				<?php
				$form_section = ob_get_clean();

				// Start output buffer for image container variable.
				ob_start();
				if ( isset( $a['modal_img_src'] ) && 'none' !== $a['modal_img_src'] ) {
					if ( 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] ) {
						?>
						<div class="cp-image-container col-md-4 col-sm-12 col-xs-12 col-lg-4 cp-column-equalized-center" >
							<?php } else { ?>
							<div class="cp-image-container col-md-12 col-sm-12 col-xs-12 col-lg-12" >
								<?php
							}
				}
				if ( isset( $a['modal_img_src'] ) && 'none' !== $a['modal_img_src'] ) {
					?>

					<img style="<?php echo esc_attr( $imagestyle ); ?>" src="<?php echo esc_attr( $modal_image ); ?>" class="cp-image" <?php echo esc_attr( str_replace( "'", '', $modal_image_alt ) ); ?> >
						</div>
						<?php } ?>
						<?php
						$img_container = ob_get_clean();

						// Start output buffer for text container variable.
						ob_start();
						?>
						<?php
						$text_container_class = 'cp-text-center';
						if ( 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] ) {
							$text_container_class = '';
						}
						if ( 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] ) {
							?>
							<div class="cp-jugaad-text-container col-md-8 col-sm-12 col-xs-12 col-lg-8 cp-column-equalized-center" >
								<?php } else { ?>
								<div class="cp-jugaad-text-container cp-text-center col-md-12 col-sm-12 col-xs-12 col-lg-12 txt-pos-bottom <?php echo esc_attr( $text_container_class ); ?>" >
									<?php } ?>
									<div class="cp-title-container 
									<?php
									if ( '' === trim( $a['modal_title1'] ) ) {
										echo 'cp-empty'; }
									?>
										" >
										<h2 class="cp-title cp_responsive" style="color: <?php echo esc_attr( $a['modal_title_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( $a['modal_title1'] ) ); ?></h2>
									</div>
									<div class="cp-desc-container 
									<?php
									if ( '' === trim( $a['modal_short_desc1'] ) ) {
										echo 'cp-empty'; }
									?>
										">
										<div class="cp-description cp_responsive" style="color: <?php echo esc_attr( $a['modal_desc_color'] ); ?>;"><?php echo do_shortcode( html_entity_decode( $a['modal_short_desc1'] ) ); ?></div>
									</div>
									<div class="modal-note-container-2 
									<?php
									if ( '' === trim( $a['modal_note_2'] ) ) {
										echo 'cp-empty'; }
									?>
										">
										<div class="cp-modal-note-2 cp_responsive"><?php echo do_shortcode( html_entity_decode( $a['modal_note_2'] ) ); ?></div>
									</div>
								</div>
								<?php
								$txt_container = ob_get_clean();

								// Start output buffer for content section variable.
								ob_start();
								?>

								<?php
								if ( 'form_left' === $a['modal_layout'] || 'form_right' === $a['modal_layout']
									|| 'form_right_img_top' === $a['modal_layout'] || 'form_left_img_top' === $a['modal_layout'] ) {

									if ( '0' === $a['modal_col_width'] ) {
										$content_classes = 'col-md-6 col-sm-6 col-lg-6';
									} else {
										$content_classes = 'col-md-7 col-sm-7 col-lg-7';
									}
									?>
									<div class="<?php echo esc_attr( $content_classes ); ?> col-xs-12 cp-content-section" style="<?php echo esc_attr( $content_css ); ?>" >
										<?php } else { ?>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cp-content-section <?php echo esc_attr( $cp_equalized ); ?>" style="<?php echo esc_attr( $content_css ); ?>" >
											<?php } ?>
											<div class="cp-content-section-overlay" style="<?php echo esc_attr( $content_overaly_css ); ?>"></div>
											<?php
											if ( 'form_left_img_botttom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] || 'form_right_img_bottom' === $a['modal_layout'] ) {
												echo wp_kses_post( $txt_container );
												if ( 'form_bottom' !== $a['modal_layout'] && 'form_left' !== $a['modal_layout'] && 'form_right' !== $a['modal_layout'] ) {
													echo wp_kses_post( $img_container );
												}
											} else {
												if ( 'form_right_img_top' === $a['modal_layout'] || 'img_left_form_bottom' === $a['modal_layout'] || 'form_left_img_top' === $a['modal_layout'] || 'form_bottom_img_top' === $a['modal_layout'] ) {
													echo wp_kses_post( $img_container );
												}
												echo wp_kses_post( $txt_container );
											}
											?>
										</div>
										<?php $content_section = ob_get_clean(); ?>

										<!-- BEFORE CONTENTS -->
										<?php
										if ( 'form_bottom' === $a['modal_layout'] || 'img_left_form_bottom' === $a['modal_layout'] || 'img_right_form_bottom' === $a['modal_layout'] || 'form_bottom_img_top' === $a['modal_layout'] ) {
											?>
											<div class="cp-row cp-block <?php echo esc_attr( $row_classes ); ?>">
												<?php } else { ?>
												<div class="cp-row cp-table <?php echo esc_attr( $row_classes ); ?>">
													<?php
												}
												if ( 'form_left' === $a['modal_layout'] || 'form_left_img_botttom' === $a['modal_layout'] || 'form_left_img_top' === $a['modal_layout'] ) {
													echo $form_section; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo $content_section; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												} else {
													echo $content_section; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													echo $form_section; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												}

												if ( 'none' !== $a['form_separator'] ) {
													echo wp_kses_post( $form_separator );
												}
												?>
											</div>
											<!-- AFTER CONTENTS -->
											<?php
											// After filter.
											apply_filters_ref_array( 'cp_modal_global_after', array( $a ) );

											return ob_get_clean();
	}
}
