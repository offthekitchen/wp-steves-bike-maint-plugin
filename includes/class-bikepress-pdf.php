<?php
/**
 * Minimal PDF builder (Helvetica, colors, JPEG images, multi-page).
 *
 * @package BikePress
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Lightweight PDF writer — no third-party dependency.
 */
class BikePress_Pdf {

	/** @var string[] */
	private $pages = array();

	/** @var string */
	private $current = '';

	/** @var array<int,array{w:int,h:int,data:string}> */
	private $images = array();

	/** @var array<int,int[]> Image object indexes used per page (1-based image keys). */
	private $page_image_keys = array();

	/** @var int[] */
	private $current_page_images = array();

	/** @var float */
	private $page_width = 612.0;

	/** @var float */
	private $page_height = 792.0;

	/** @var float */
	private $margin_left = 54.0;

	/** @var float */
	private $margin_right = 54.0;

	/** @var float */
	private $margin_top = 54.0;

	/** @var float */
	private $margin_bottom = 54.0;

	/** @var float */
	private $y;

	/** @var float */
	private $font_size = 12.0;

	/** @var bool */
	private $bold = false;

	/** @var bool */
	private $page_open = false;

	/** @var float[] RGB 0-1 */
	private $text_rgb = array( 0, 0, 0 );

	/** @var float[] RGB 0-1 */
	private $fill_rgb = array( 0, 0, 0 );

	public function __construct() {
		$this->start_page();
	}

	/**
	 * @return float
	 */
	public function content_width() {
		return $this->page_width - $this->margin_left - $this->margin_right;
	}

	/**
	 * @return float
	 */
	public function get_y() {
		return $this->y;
	}

	/**
	 * @param float $y Y from bottom.
	 */
	public function set_y( $y ) {
		$this->y = (float) $y;
	}

	/**
	 * @return float
	 */
	public function bottom_limit() {
		return $this->margin_bottom + 30.0;
	}

	/**
	 * @return float
	 */
	public function margin_left() {
		return $this->margin_left;
	}

	/**
	 * @return float
	 */
	public function page_width() {
		return $this->page_width;
	}

	/**
	 * @return float
	 */
	public function font_size() {
		return $this->font_size;
	}

	/**
	 * Start a new page.
	 */
	public function start_page() {
		if ( $this->page_open ) {
			$this->pages[]             = $this->current;
			$this->page_image_keys[]   = $this->current_page_images;
			$this->current             = '';
			$this->current_page_images = array();
			$this->page_open           = false;
		}
		$this->current   = '';
		$this->page_open = true;
		$this->y         = $this->page_height - $this->margin_top;
		$this->set_font( 12, false );
		$this->set_text_color( 0, 0, 0 );
	}

	/**
	 * @param float $size Font size.
	 * @param bool  $bold Bold.
	 */
	public function set_font( $size, $bold = false ) {
		$this->font_size = (float) $size;
		$this->bold      = (bool) $bold;
	}

	/**
	 * @param int $r 0-255.
	 * @param int $g 0-255.
	 * @param int $b 0-255.
	 */
	public function set_text_color( $r, $g, $b ) {
		$this->text_rgb = array( $r / 255, $g / 255, $b / 255 );
	}

	/**
	 * @param int $r 0-255.
	 * @param int $g 0-255.
	 * @param int $b 0-255.
	 */
	public function set_fill_color( $r, $g, $b ) {
		$this->fill_rgb = array( $r / 255, $g / 255, $b / 255 );
	}

	/**
	 * @param string     $text Text.
	 * @param float|null $size Font size.
	 * @return float
	 */
	public function text_width( $text, $size = null ) {
		$size = null === $size ? $this->font_size : (float) $size;
		return strlen( (string) $text ) * $size * 0.5;
	}

	/**
	 * @param float  $x    X.
	 * @param string $text Text.
	 */
	public function text_at( $x, $text ) {
		$text = $this->escape( $text );
		$font = $this->bold ? 'F2' : 'F1';
		$this->current .= sprintf(
			"%.3F %.3F %.3F rg BT /%s %.2F Tf 1 0 0 1 %.2F %.2F Tm (%s) Tj ET\n",
			$this->text_rgb[0],
			$this->text_rgb[1],
			$this->text_rgb[2],
			$font,
			$this->font_size,
			$x,
			$this->y - $this->font_size,
			$text
		);
	}

	/**
	 * @param string $text    Text.
	 * @param float  $leading Extra space after line.
	 */
	public function write_line( $text, $leading = 4.0 ) {
		$this->text_at( $this->margin_left, $text );
		$this->y -= ( $this->font_size + $leading );
	}

	/**
	 * @param string     $text  Text.
	 * @param float      $width Max width.
	 * @param float|null $size  Font size.
	 * @return string[]
	 */
	public function wrap_text( $text, $width, $size = null ) {
		$size  = null === $size ? $this->font_size : (float) $size;
		$text  = preg_replace( "/\r\n|\r/", "\n", (string) $text );
		$paras = explode( "\n", $text );
		$lines = array();

		foreach ( $paras as $para ) {
			$para = trim( $para );
			if ( '' === $para ) {
				$lines[] = '';
				continue;
			}
			$words = preg_split( '/\s+/', $para );
			$line  = '';
			foreach ( $words as $word ) {
				$trial = '' === $line ? $word : $line . ' ' . $word;
				if ( $this->text_width( $trial, $size ) <= $width ) {
					$line = $trial;
				} else {
					if ( '' !== $line ) {
						$lines[] = $line;
					}
					while ( $this->text_width( $word, $size ) > $width && strlen( $word ) > 1 ) {
						$cut     = max( 1, (int) floor( $width / ( $size * 0.5 ) ) );
						$lines[] = substr( $word, 0, $cut );
						$word    = substr( $word, $cut );
					}
					$line = $word;
				}
			}
			if ( '' !== $line ) {
				$lines[] = $line;
			}
		}

		return $lines;
	}

	/**
	 * @param float $thickness Stroke width.
	 */
	public function line_across( $thickness = 0.75 ) {
		$x1 = $this->margin_left;
		$x2 = $this->page_width - $this->margin_right;
		$y  = $this->y;
		$this->current .= sprintf( "0 0 0 RG %.2F w %.2F %.2F m %.2F %.2F l S\n", $thickness, $x1, $y, $x2, $y );
		$this->y -= 8;
	}

	/**
	 * Filled rectangle (x,y = bottom-left).
	 *
	 * @param float $x X.
	 * @param float $y Y.
	 * @param float $w Width.
	 * @param float $h Height.
	 */
	public function filled_rect( $x, $y, $w, $h ) {
		$this->current .= sprintf(
			"%.3F %.3F %.3F rg %.2F %.2F %.2F %.2F re f\n",
			$this->fill_rgb[0],
			$this->fill_rgb[1],
			$this->fill_rgb[2],
			$x,
			$y,
			$w,
			$h
		);
	}

	/**
	 * Stroke rectangle.
	 *
	 * @param float $x X.
	 * @param float $y Y.
	 * @param float $w Width.
	 * @param float $h Height.
	 */
	public function rect( $x, $y, $w, $h ) {
		$this->current .= sprintf( "0 0 0 RG 0.6 w %.2F %.2F %.2F %.2F re S\n", $x, $y, $w, $h );
	}

	/**
	 * Draw a JPEG (or convertible) image. Returns false on failure.
	 *
	 * @param string $path Filesystem path.
	 * @param float  $x    Bottom-left X.
	 * @param float  $y    Bottom-left Y.
	 * @param float  $w    Display width.
	 * @param float  $h    Display height.
	 * @return bool
	 */
	public function image( $path, $x, $y, $w, $h ) {
		$jpeg = $this->load_jpeg_bytes( $path );
		if ( ! $jpeg ) {
			return false;
		}

		$key = count( $this->images ) + 1;
		$this->images[ $key ] = $jpeg;
		$this->current_page_images[] = $key;

		$this->current .= sprintf(
			"q %.2F 0 0 %.2F %.2F %.2F cm /Im%d Do Q\n",
			$w,
			$h,
			$x,
			$y,
			$key
		);
		return true;
	}

	/**
	 * @param string $path Path.
	 * @return array{w:int,h:int,data:string}|null
	 */
	private function load_jpeg_bytes( $path ) {
		if ( ! is_readable( $path ) ) {
			return null;
		}

		$info = @getimagesize( $path ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( ! $info ) {
			return null;
		}

		$mime = isset( $info['mime'] ) ? $info['mime'] : '';
		$data = null;

		if ( 'image/jpeg' === $mime ) {
			$data = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		} elseif ( function_exists( 'imagecreatefromstring' ) && function_exists( 'imagejpeg' ) ) {
			$raw = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$img = @imagecreatefromstring( $raw ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			if ( false === $img ) {
				return null;
			}
			ob_start();
			imagejpeg( $img, null, 85 );
			imagedestroy( $img );
			$data = ob_get_clean();
		}

		if ( ! is_string( $data ) || '' === $data ) {
			return null;
		}

		return array(
			'w'    => (int) $info[0],
			'h'    => (int) $info[1],
			'data' => $data,
		);
	}

	/**
	 * @param string $text Text.
	 * @return string
	 */
	private function escape( $text ) {
		$text = wp_strip_all_tags( (string) $text );
		$text = str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $text );
		$text = preg_replace( '/[^\x20-\x7E]/', '?', $text );
		return $text;
	}

	/**
	 * @return string
	 */
	public function output() {
		if ( $this->page_open ) {
			$this->pages[]           = $this->current;
			$this->page_image_keys[] = $this->current_page_images;
			$this->current           = '';
			$this->page_open         = false;
		}
		if ( empty( $this->pages ) ) {
			$this->pages[]           = '';
			$this->page_image_keys[] = array();
		}

		$objects   = array( null );
		$objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
		$objects[] = ''; // pages
		$objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
		$objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

		$image_obj_ids = array();
		foreach ( $this->images as $key => $img ) {
			$image_obj_ids[ $key ] = count( $objects );
			$objects[]             = sprintf(
				"<< /Type /XObject /Subtype /Image /Width %d /Height %d /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length %d >>\nstream\n%s\nendstream",
				$img['w'],
				$img['h'],
				strlen( $img['data'] ),
				$img['data']
			);
		}

		$page_ids    = array();
		$content_ids = array();

		foreach ( $this->pages as $i => $page_content ) {
			$content_ids[] = count( $objects );
			$objects[]     = '';
			$page_ids[]    = count( $objects );
			$objects[]     = '';
		}

		foreach ( $this->pages as $i => $page_content ) {
			$cid = $content_ids[ $i ];
			$pid = $page_ids[ $i ];
			$objects[ $cid ] = '<< /Length ' . strlen( $page_content ) . " >>\nstream\n" . $page_content . "\nendstream";

			$xobjects = '';
			$used     = isset( $this->page_image_keys[ $i ] ) ? $this->page_image_keys[ $i ] : array();
			if ( ! empty( $used ) ) {
				$parts = array();
				foreach ( array_unique( $used ) as $ikey ) {
					$parts[] = '/Im' . $ikey . ' ' . $image_obj_ids[ $ikey ] . ' 0 R';
				}
				$xobjects = '/XObject << ' . implode( ' ', $parts ) . ' >>';
			}

			$objects[ $pid ] = sprintf(
				'<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2F %.2F] /Contents %d 0 R /Resources << /Font << /F1 3 0 R /F2 4 0 R >> %s >> >>',
				$this->page_width,
				$this->page_height,
				$cid,
				$xobjects
			);
		}

		$kids = array();
		foreach ( $page_ids as $pid ) {
			$kids[] = $pid . ' 0 R';
		}
		$objects[2] = '<< /Type /Pages /Kids [ ' . implode( ' ', $kids ) . ' ] /Count ' . count( $page_ids ) . ' >>';

		$out     = "%PDF-1.4\n";
		$offsets = array( 0 );
		for ( $i = 1, $n = count( $objects ); $i < $n; $i++ ) {
			$offsets[ $i ] = strlen( $out );
			$out          .= $i . " 0 obj\n" . $objects[ $i ] . "\nendobj\n";
		}
		$xref = strlen( $out );
		$out .= "xref\n0 " . count( $objects ) . "\n";
		$out .= "0000000000 65535 f \n";
		for ( $i = 1, $n = count( $objects ); $i < $n; $i++ ) {
			$out .= sprintf( "%010d 00000 n \n", $offsets[ $i ] );
		}
		$out .= "trailer\n<< /Size " . count( $objects ) . " /Root 1 0 R >>\n";
		$out .= "startxref\n{$xref}\n%%EOF";

		return $out;
	}
}
