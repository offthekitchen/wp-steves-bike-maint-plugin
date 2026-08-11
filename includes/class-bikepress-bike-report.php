<?php
/**
 * Build a printable bike PDF report (summary/specs + maintenance log).
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once __DIR__ . '/class-bikepress-pdf.php';

/**
 * Bike report PDF generator.
 */
class BikePress_Bike_Report {

	/** @var object */
	private $bike;

	/** @var object[] */
	private $specs;

	/** @var object[] */
	private $maintenance;

	/** @var BikePress_Pdf */
	private $pdf;

	/** @var string */
	private $generated_date;

	/** @var int */
	private $page_number = 1;

	/** @var string Absolute path to plugin main file directory parent for assets. */
	private $plugin_root;

	/**
	 * @param object   $bike        Bike row with status/type labels.
	 * @param object[] $specs       Spec rows.
	 * @param object[] $maintenance Maintenance rows.
	 */
	public function __construct( $bike, $specs, $maintenance ) {
		$this->bike           = $bike;
		$this->specs          = is_array( $specs ) ? $specs : array();
		$this->maintenance    = is_array( $maintenance ) ? $maintenance : array();
		$this->generated_date = wp_date( 'm/d/Y' );
		$this->pdf            = new BikePress_Pdf();
		$this->plugin_root    = dirname( __DIR__ );
	}

	/**
	 * @return string PDF binary.
	 */
	public function render() {
		$this->draw_summary_pages();
		$this->draw_maintenance_pages();
		$this->draw_footer();
		return $this->pdf->output();
	}

	/**
	 * Footer on the current page.
	 */
	private function draw_footer() {
		$pdf   = $this->pdf;
		$y     = 32.0;
		$left  = $pdf->margin_left();
		$right = $pdf->page_width() - 54.0;
		$mid   = $left + ( $pdf->content_width() / 2.0 );

		$saved_y = $pdf->get_y();
		$pdf->set_text_color( 0, 0, 0 );
		$pdf->set_font( 9, false );
		$pdf->set_y( $y + 9 );
		$pdf->text_at( $left, $this->generated_date );

		$label = sprintf( 'Page %d', $this->page_number );
		$pdf->text_at( $mid - ( $pdf->text_width( $label, 9 ) / 2.0 ), $label );

		$credit = 'Created by BikePress';
		$pdf->text_at( $right - $pdf->text_width( $credit, 9 ), $credit );

		$pdf->set_y( $saved_y );
	}

	/**
	 * Close current page with footer and open a new one.
	 */
	private function new_page() {
		$this->draw_footer();
		$this->pdf->start_page();
		++$this->page_number;
	}

	/**
	 * Resolve filesystem path for the bike image (attachment or default).
	 *
	 * @return string
	 */
	private function bike_image_path() {
		$image_id = isset( $this->bike->bike_image_id ) ? abs( (int) $this->bike->bike_image_id ) : 0;
		if ( $image_id > 0 && function_exists( 'get_attached_file' ) ) {
			$path = get_attached_file( $image_id );
			if ( $path && is_readable( $path ) ) {
				return $path;
			}
		}
		$default = $this->plugin_root . '/img/default-bike.jpg';
		return is_readable( $default ) ? $default : '';
	}

	/**
	 * Summary section with photo, fields, description, specs.
	 */
	private function draw_summary_pages() {
		$bike = $this->bike;
		$name = isset( $bike->bike_name ) ? (string) $bike->bike_name : '';

		$this->pdf->set_text_color( 0, 0, 0 );
		$this->pdf->set_font( 28, true );
		$this->pdf->write_line( $name, 12 );
		$this->pdf->set_font( 12, false );
		$this->pdf->set_y( $this->pdf->get_y() - 6 );

		$img_w   = 180.0;
		$img_h   = 180.0;
		$gap     = 18.0;
		$left_x  = $this->pdf->margin_left();
		$right_x = $left_x + $img_w + $gap;
		$top_y   = $this->pdf->get_y();
		$img_bottom = $top_y - $img_h;

		$path = $this->bike_image_path();
		if ( $path ) {
			$this->pdf->image( $path, $left_x, $img_bottom, $img_w, $img_h );
		} else {
			$this->pdf->set_fill_color( 230, 230, 230 );
			$this->pdf->filled_rect( $left_x, $img_bottom, $img_w, $img_h );
			$this->pdf->rect( $left_x, $img_bottom, $img_w, $img_h );
		}

		$fields = array(
			'Make'     => isset( $bike->bike_make ) ? (string) $bike->bike_make : '',
			'Model'    => isset( $bike->bike_model ) ? (string) $bike->bike_model : '',
			'Serial #' => isset( $bike->serial_number ) ? (string) $bike->serial_number : '',
			'Type'     => ! empty( $bike->bike_type ) ? (string) $bike->bike_type : 'Unknown',
			'Status'   => ! empty( $bike->bike_status ) ? (string) $bike->bike_status : '',
		);

		$field_y = $top_y;
		foreach ( $fields as $label => $value ) {
			$this->pdf->set_y( $field_y );
			$this->pdf->set_font( 12, true );
			$this->pdf->set_text_color( 0, 0, 0 );
			$this->pdf->text_at( $right_x, $label . ':' );
			$lw = $this->pdf->text_width( $label . ': ', 12 );
			$this->pdf->set_font( 12, false );
			$this->pdf->text_at( $right_x + $lw + 6, $value );
			$field_y -= 22;
		}

		// Continue below the taller of image vs fields.
		$below = min( $img_bottom, $field_y ) - 14;
		$this->pdf->set_y( $below );

		// Description (no header), above Specs.
		$desc = isset( $bike->bike_desc ) ? (string) $bike->bike_desc : '';
		if ( '' !== trim( $desc ) ) {
			$wrapped = $this->pdf->wrap_text( $desc, $this->pdf->content_width(), 11 );
			$needed  = count( $wrapped ) * 15 + 8;
			$this->ensure_space( $needed, true );
			$this->pdf->set_font( 11, false );
			$this->pdf->set_text_color( 0, 0, 0 );
			foreach ( $wrapped as $wline ) {
				$this->ensure_space( 16, true );
				$this->pdf->write_line( $wline, 3 );
			}
			$this->pdf->set_y( $this->pdf->get_y() - 8 );
		}

		$this->ensure_space( 30, true );
		$this->pdf->set_font( 16, true );
		$this->pdf->write_line( 'Specs', 10 );
		$this->pdf->set_font( 12, false );

		if ( empty( $this->specs ) ) {
			$this->ensure_space( 18, true );
			$this->pdf->write_line( __( 'No specifications recorded.', 'bikepress' ), 4 );
			return;
		}

		foreach ( $this->specs as $spec ) {
			$label   = isset( $spec->spec_name ) ? (string) $spec->spec_name : '';
			$sdesc   = isset( $spec->spec_desc ) ? (string) $spec->spec_desc : '';
			$line    = $label . ': ' . $sdesc;
			$wrapped = $this->pdf->wrap_text( $line, $this->pdf->content_width(), 12 );
			$needed  = max( 16, count( $wrapped ) * 16 );
			$this->ensure_space( $needed + 4, true );
			foreach ( $wrapped as $wline ) {
				$this->pdf->write_line( $wline, 4 );
			}
		}
	}

	/**
	 * @param float $needed        Points required.
	 * @param bool  $summary_style Continued header for summary.
	 */
	private function ensure_space( $needed, $summary_style = false ) {
		if ( $this->pdf->get_y() - $needed >= $this->pdf->bottom_limit() ) {
			return;
		}
		$this->new_page();
		if ( $summary_style ) {
			$name = isset( $this->bike->bike_name ) ? (string) $this->bike->bike_name : '';
			$this->pdf->set_font( 14, true );
			$this->pdf->set_text_color( 0, 0, 0 );
			$this->pdf->write_line( $name . ' (continued)', 8 );
			$this->pdf->set_font( 12, false );
		}
	}

	/**
	 * Maintenance log pages.
	 */
	private function draw_maintenance_pages() {
		$this->new_page();

		$name = isset( $this->bike->bike_name ) ? (string) $this->bike->bike_name : '';
		$this->pdf->set_text_color( 0, 0, 0 );
		$this->pdf->set_font( 20, true );
		$this->pdf->write_line( $name . ' Maintenance Log', 12 );

		$col_date  = 80.0;
		$col_miles = 60.0;
		$col_desc  = $this->pdf->content_width() - $col_date - $col_miles;
		$x_date    = $this->pdf->margin_left();
		$x_miles   = $x_date + $col_date;
		$x_desc    = $x_miles + $col_miles;

		$this->draw_maint_header( $x_date, $x_miles, $x_desc );

		if ( empty( $this->maintenance ) ) {
			$this->pdf->set_font( 11, false );
			$this->pdf->set_text_color( 0, 0, 0 );
			$this->pdf->write_line( __( 'No maintenance records.', 'bikepress' ), 4 );
			return;
		}

		$row_index = 0;
		foreach ( $this->maintenance as $row ) {
			$date = '';
			if ( ! empty( $row->maintenance_date ) && '0000-00-00 00:00:00' !== $row->maintenance_date ) {
				$ts   = strtotime( $row->maintenance_date );
				$date = $ts ? wp_date( 'm/d/Y', $ts ) : (string) $row->maintenance_date;
			}
			$miles      = isset( $row->bike_miles ) ? (string) abs( (int) $row->bike_miles ) : '0';
			$desc       = isset( $row->maintenance_desc ) ? (string) $row->maintenance_desc : '';
			$desc_lines = $this->pdf->wrap_text( $desc, $col_desc - 12, 11 );
			if ( empty( $desc_lines ) ) {
				$desc_lines = array( '' );
			}

			$padding = 12.0;
			$line_h  = 14.0;
			$row_h   = max( 30.0, ( count( $desc_lines ) * $line_h ) + $padding );

			if ( $this->pdf->get_y() - $row_h < $this->pdf->bottom_limit() ) {
				$this->new_page();
				$this->pdf->set_text_color( 0, 0, 0 );
				$this->pdf->set_font( 16, true );
				$this->pdf->write_line( $name . ' Maintenance Log (continued)', 10 );
				$this->draw_maint_header( $x_date, $x_miles, $x_desc );
			}

			$top_y = $this->pdf->get_y();
			$box_y = $top_y - $row_h;

			if ( 0 === ( $row_index % 2 ) ) {
				$this->pdf->set_fill_color( 255, 255, 255 );
			} else {
				$this->pdf->set_fill_color( 230, 230, 230 ); // #e6e6e6
			}
			$this->pdf->filled_rect( $this->pdf->margin_left(), $box_y, $this->pdf->content_width(), $row_h );
			$this->pdf->rect( $this->pdf->margin_left(), $box_y, $this->pdf->content_width(), $row_h );

			$this->pdf->set_font( 11, false );
			$this->pdf->set_text_color( 0, 0, 0 );
			$this->pdf->set_y( $top_y - 6 );
			$this->pdf->text_at( $x_date + 4, $date );
			$this->pdf->text_at( $x_miles + 4, $miles );

			$line_y = $top_y - 6;
			foreach ( $desc_lines as $dline ) {
				$this->pdf->set_y( $line_y );
				$this->pdf->text_at( $x_desc + 4, $dline );
				$line_y -= $line_h;
			}

			$this->pdf->set_y( $box_y - 6 );
			++$row_index;
		}
	}

	/**
	 * Black header bar with white column labels.
	 *
	 * @param float $x_date  Date x.
	 * @param float $x_miles Miles x.
	 * @param float $x_desc  Desc x.
	 */
	private function draw_maint_header( $x_date, $x_miles, $x_desc ) {
		$header_h = 22.0;
		$top_y    = $this->pdf->get_y();
		$box_y    = $top_y - $header_h;

		$this->pdf->set_fill_color( 0, 0, 0 );
		$this->pdf->filled_rect( $this->pdf->margin_left(), $box_y, $this->pdf->content_width(), $header_h );

		$this->pdf->set_font( 11, true );
		$this->pdf->set_text_color( 255, 255, 255 );
		$this->pdf->set_y( $top_y - 4 );
		$this->pdf->text_at( $x_date + 4, 'DATE' );
		$this->pdf->text_at( $x_miles + 4, 'MILES' );
		$this->pdf->text_at( $x_desc + 4, 'MAINTENANCE DESCRIPTION' );

		$this->pdf->set_y( $box_y - 6 );
		$this->pdf->set_text_color( 0, 0, 0 );
		$this->pdf->set_font( 11, false );
	}
}
