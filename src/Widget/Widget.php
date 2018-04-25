<?php

/**
 * Advanced_Sidebar_Menu__Widget__Widget
 *
 * @author Mat Lipe
 *
 * @since  7.2.0
 *
 */
abstract class Advanced_Sidebar_Menu__Widget__Widget extends WP_Widget {
	protected $_instance;


	/**
	 * Store the instance to this class.
	 * We do this manually because there are filters etc which
	 * hit the instance before we get in in self::form() and self::widget()
	 *
	 * @see   WP_Widget::form_callback()
	 *
	 * @param array $instance
	 * @param array $defaults
	 *
	 * @since 7.2.0
	 *
	 * @return array
	 */
	protected function set_instance( array $instance, array $defaults ) {
		$instance        = wp_parse_args( $instance, $defaults );
		$this->_instance = $instance;

		return $instance;

	}


	/**
	 * Checks if a widgets checkbox is checked.
	 *
	 * Checks first for a value then verifies the value = checked
	 *
	 * @param string $name - name of checkbox.
	 *
	 * @since 7.2.0
	 *
	 * @return bool
	 */
	public function checked( $name ) {
		return isset( $this->_instance[ $name ] ) && 'checked' === $this->_instance[ $name ];
	}


	/**
	 * Hide an element if a checkbox is checked.
	 *
	 * @param string $checkbox - name of checkbox element which controls this one
	 * @param bool   $reverse  - hide on check instead of show on check
	 *
	 * @since 7.2.0
	 *
	 * @return void
	 */
	public function hide_element( $checkbox, $reverse = false ) {
		if ( $reverse ) {
			if ( $this->checked( $checkbox ) ) {
				?>style="display:none"<?php
			}
		} else {
			if ( ! $this->checked( $checkbox ) ) {
				?>style="display:none"<?php
			}
		}
	}


	/**
	 * Outputs a <input type="checkbox" with id and name filled
	 *
	 * @param string      $name
	 * @param string|null $reveal_element
	 *
	 * @since 7.2.0
	 *
	 */
	public function checkbox( $name, $reveal_element = null ) {
		if ( empty( $this->_instance[ $name ] ) ) {
			$this->_instance[ $name ] = null;
		}

		?>
		<input
			id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"
			name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"
			type="checkbox"
			value="checked"
			data-js="advanced-sidebar-menu/widget/<?php echo esc_attr( $this->id_base ); ?>/<?php echo esc_attr( $name ); ?>"
			<?php echo ( null !== $reveal_element ) ? 'onclick="asm_reveal_element( \'' . esc_attr( $this->get_field_id( $reveal_element ) ) . '\')"' : ''; ?>
			<?php echo esc_html( $this->_instance[ $name ] ); ?>
		/>
		<?php

	}
}
