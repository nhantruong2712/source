<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Thumbnail Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

 
class thumbnail
{
	/**
	 * Is enlargement allowed
	 *
	 * @var	bool
	 */
	private $allowEnlargement = false;


	/**
	 * The horizontal crop position
	 *
	 * @var	string
	 */
	private $cropPositionHorizontal = 'center';


	/**
	 * The vertical crop position
	 *
	 * @var	string
	 */
	private $cropPositionVertical = 'middle';


	/**
	 * The path for the original image
	 *
	 * @var	string
	 */
	private $filename;


	/**
	 * Should we respect the original aspect ratio?
	 *
	 * @var	bool
	 */
	private $forceOriginalAspectRatio = true;


	/**
	 * The height for the thumbnail
	 *
	 * @var	int
	 */
	private $height;


	/**
	 * The image resource
	 *
	 * @var	resource
	 */
	private $image;


	/**
	 * The width for the thumbnail
	 *
	 * @var int
	 */
	private $width;


	/**
	 * The strict setting
	 *
	 * @var	bool
	 */
	private $strict = true;


	/**
	 * Default constructor.
	 *
	 * @param	string $filename		The path to the source-image.
	 * @param	int[optional] $width	The required width, if not provided it will be calculated based on the height.
	 * @param	int[optional] $height	The required height, if not provided it will be calculated based on the width.
	 * @param	bool[optional] $strict	Should strict-mode be activated?
	 */
	public function __construct($filename, $width = null, $height = null, $strict = true)
	{
		// check if gd is available
		if(!extension_loaded('gd')) throw new SAException('GD2 isn\'t loaded. Contact your server-admin to enable it.');

		// redefine vars
		$filename = (string) $filename;
		if($width != null) $width = (int) $width;
		if($height != null) $height = (int) $height;

		// set strict
		$this->strict = (bool) $strict;

		// validate
		if(!file::exists($filename)) throw new SAException('The sourcefile "' . $filename . '" couldn\'t be found.');

		// set properties
		$this->filename = $filename;
		$this->width = $width;
		$this->height = $height;
	}


	/**
	 * Check if file is supported.
	 *
	 * @return	bool				True if the file is supported, false if not.
	 * @param	string $filename	The path to the file tp check.
	 */
	public static function isSupportedFileType($filename)
	{
		// get watermarkfile properties
		list($width, $height, $type) = @getimagesize($filename);

		// create image from sourcefile
		switch($type)
		{
			// gif
			case IMG_GIF:

			// jpg
			case IMG_JPG:

			// png
			case 3:
			case IMG_PNG:
				return true;
			break;

			default:
				return false;
		}
	}


	/**
	 * Outputs the image as png to the browser.
	 *
	 * @param	bool[optional] $headers		Should the headers be send? This is a usefull when you're debugging.
	 */
	public function parse($headers = true)
	{
		// set headers
		if($headers) http::setHeaders('Content-type: image/png');

		// get current dimensions
		$imageProperties = @getimagesize($this->filename);

		// validate imageProperties
		if($imageProperties === false) throw new SAException('The sourcefile "' . $this->filename . '" could not be found.');

		// set current dimensions
		$currentWidth = (int) $imageProperties[0];
		$currentHeight = (int) $imageProperties[1];
		$currentType = (int) $imageProperties[2];
		$currentMime = (string) $imageProperties['mime'];

		// resize image
		$this->resizeImage($currentWidth, $currentHeight, $currentType, $currentMime);

		// output image
		$success = @imagepng($this->image);

		// validate
		if(!$success) throw new SAException('Something went wrong while outputting the image.');

		// cleanup the memory
		@imagedestroy($this->image);
	}


	/**
	 * Saves the image to a file (quality is only used for jpg images).
	 *
	 * @return	bool						True if the image was saved, false if not.
	 * @param	string $filename			The path where the image should be saved.
	 * @param	int[optional] $quality		The quality to use (only applies on jpg-images).
	 * @param	int[optional] $chmod		Mode that should be applied on the file.
	 */
	public function parseToFile($filename, $quality = 100, $chmod = 0777)
	{
		// redefine vars
		$filename = (string) $filename;
		$quality = (int) $quality;

		//
		if(@is_writable(dirname($filename)) !== true)
		{
			// does the folder exist? if not, try to create
			if(!dir::create(dirname($filename)))
			{
				if($this->strict) throw new SAException('The destination-path should be writable.');
				return false;
			}
		}

		// get extension
		$extension = file::getExtension($filename);

		// invalid quality
		if(!validate::range(1, 100, $quality))
		{
			// strict?
			if($this->strict) throw new SAException('The quality should be between 1 - 100');
			return false;
		}
		// invalid extension
		if(input::getValue($extension, array('gif', 'jpeg', 'jpg', 'png'), '') == '')
		{
			if($this->strict) throw new SAException('Only gif, jpeg, jpg or png are allowed types.');
			return false;
		}

		// get current dimensions
		$imageProperties = @getimagesize($this->filename);

		// validate imageProperties
		if($imageProperties === false)
		{
			// strict?
			if($this->strict) throw new SAException('The sourcefile "' . $this->filename . '" could not be found.');
			return false;
		}
		// set current dimensions
		$currentWidth = (int) $imageProperties[0];
		$currentHeight = (int) $imageProperties[1];
		$currentType = (int) $imageProperties[2];
		$currentMime = (string) $imageProperties['mime'];

		// file is the same?
		if(($currentType == IMAGETYPE_GIF && $extension == 'gif') || ($currentType == IMAGETYPE_JPEG && in_array($extension, array('jpg', 'jpeg'))) || ($currentType == IMAGETYPE_PNG && $extension == 'png'))
		{
			if($currentWidth == $this->width && $currentHeight == $this->height)
			{
				return dir::copy($this->filename, $filename, true, true, $chmod);
			}
		}

		// resize image
		$this->resizeImage($currentWidth, $currentHeight, $currentType, $currentMime);

		// output to file
		switch(strtolower($extension))
		{
			case 'gif':
				$return = @imagegif($this->image, $filename);
			break;

			case 'jpeg':
			case 'jpg':
				$return = @imagejpeg($this->image, $filename, $quality);
			break;

			case 'png':
				$return = @imagepng($this->image, $filename);
			break;
		}

		// chmod
		@chmod($filename, $chmod);

		// cleanup memory
		@imagedestroy($this->image);

		// return success
		return (bool) $return;
	}


	/**
	 * This internal function will resize/crop the image.
	 *
	 * @param	int $currentWidth		Original width.
	 * @param	int $currentHeight		Original height.
	 * @param	int $currentType		Current type of image.
	 * @param	string $currentMime		Current mime-type.
	 */
	private function resizeImage($currentWidth, $currentHeight, $currentType, $currentMime)
	{
		// check if needed dimensions are present
		if(!$this->forceOriginalAspectRatio) $this->resizeImageWithoutForceAspectRatio($currentWidth, $currentHeight, $currentType, $currentMime);

		// FAR is on
		else $this->resizeImageWithForceAspectRatio($currentWidth, $currentHeight, $currentType, $currentMime);
	}


	/**
	 * Resize the image with Force Aspect Ratio.
	 *
	 * @param	int $currentWidth		Original width.
	 * @param	int $currentHeight		Original height.
	 * @param	int $currentType		Current type of image.
	 * @param	string $currentMime		Current mime-type.
	 */
	private function resizeImageWithForceAspectRatio($currentWidth, $currentHeight, $currentType, $currentMime)
	{
		// current width is larger then current height
		if($currentWidth > $currentHeight)
		{
			// width is specified
			if($this->width !== null)
			{
				// width is specified
				$newWidth = $this->width;

				// calculate new height
				$newHeight = (int) floor($currentHeight * ($this->width / $currentWidth));
			}

			// height is specified
			elseif($this->height !== null)
			{
				// height is specified
				$newHeight = $this->height;

				// calculate new width
				$newWidth = (int) floor($currentWidth * ($this->height / $currentHeight));
			}

			// no dimensions
			else throw new SAException('No width or height specified.');
		}

		// current width equals current height
		if($currentWidth == $currentHeight)
		{
			// width is specified
			if($this->width !== null)
			{
				$newWidth = $this->width;
				$newHeight = $this->width;
			}

			// height is specified
			elseif($this->height !== null)
			{
				$newWidth = $this->height;
				$newHeight = $this->height;
			}

			// no dimensions
			else throw new SAException('No width or height specified.');
		}

		// current width is smaller then current height
		if($currentWidth < $currentHeight)
		{
			// height is specified
			if($this->height !== null)
			{
				// height is specified
				$newHeight = $this->height;

				// calculate new width
				$newWidth = (int) floor($currentWidth * ($this->height / $currentHeight));
			}

			// width is specified
			elseif($this->width !== null)
			{
				// width is specified
				$newWidth = $this->width;

				// calculate new height
				$newHeight = (int) floor($currentHeight * ($this->width / $currentWidth));
			}

			// no dimensions
			else throw new SAException('No width or height specified.');
		}

		// check if we stay within the borders
		if($this->width !== null && $this->height !== null)
		{
			if($newWidth > $this->width)
			{
				// width is specified
				$newWidth = $this->width;

				// calculate new height
				$newHeight = (int) floor($currentHeight * ($this->width / $currentWidth));
			}

			if($newHeight > $this->height)
			{
				// height is specified
				$newHeight = $this->height;

				// calculate new width
				$newWidth = (int) floor($currentWidth * ($this->height / $currentHeight));
			}
		}
 
		// read current image
		switch($currentType)
		{
			case IMG_GIF:
				$currentImage = @imagecreatefromgif($this->filename);
			break;

			case IMG_JPG:
				$currentImage = @imagecreatefromjpeg($this->filename);
			break;

			case 3:
			case IMG_PNG:
				$currentImage = @imagecreatefrompng($this->filename);
			break;

			default:
				throw new SAException('The file you specified "' . $currentMime . '" is not supported. Only gif, jpeg, jpg and png are supported.');
		}

		// validate image
		if($currentImage === false) throw new SAException('The file you specified is corrupt.');

		// create image resource
		$this->image = @imagecreatetruecolor($newWidth, $newHeight);

		// validate
		if($this->image === false) throw new SAException('Could not create new image.');

		// set transparent
		@imagealphablending($this->image, false);

		// transparency supported
		if(in_array($currentType, array(IMG_GIF, 3, IMG_PNG)))
		{
			// get transparent color
			$colorTransparent = @imagecolorallocatealpha($this->image, 0, 0, 0, 127);

			// any color found?
			if($colorTransparent !== false)
			{
				@imagefill($this->image, 0, 0, $colorTransparent);
				@imagesavealpha($this->image, true);
			}
		}

		// resize
		$success = @imagecopyresampled($this->image, $currentImage, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);

		// image creation fail
		if(!$success)
		{
			if($this->strict) throw new SAException('Something went wrong while trying to resize the image.');
			return false;
		}

		// reset if needed
		if(!$this->allowEnlargement && $currentWidth <= $newWidth && $currentHeight <= $newHeight) $this->image = $currentImage;

		// set transparency for GIF, or try to
		if($currentType == IMG_GIF)
		{
			// get transparent index
			$transparentIndex = @imagecolortransparent($currentImage);

			// valid index
			if($transparentIndex > 0)
			{
				// magic
				$transparentColor = @imagecolorsforindex($currentImage, $transparentIndex);

				// validate transparent color
				if($transparentColor !== false)
				{
					// get color
					$transparentIndex = @imagecolorallocate($this->image, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);

					// fill
					if($transparentIndex !== false)
					{
						@imagefill($this->image, 0, 0, $transparentIndex);
						@imagecolortransparent($this->image, $transparentIndex);
					}
				}
			}
		}
	}


	/**
	 * Resize the image without Force Aspect Ratio.
	 *
	 * @param	int $currentWidth		Original width.
	 * @param	int $currentHeight		Original height.
	 * @param	int $currentType		Current type of image.
	 * @param	string $currentMime		Current mime-type.
	 */
	private function resizeImageWithoutForceAspectRatio($currentWidth, $currentHeight, $currentType, $currentMime)
	{
		// validate
		if($this->width === null || $this->height === null) throw new SAException('If forceAspectRatio is false you have to specify width and height.');

		// set new size
		$newWidth = $this->width;
		$newHeight = $this->height;

		// read current image
		switch($currentType)
		{
			case IMG_GIF:
				$currentImage = @imagecreatefromgif($this->filename);
			break;

			case IMG_JPG:
				$currentImage = @imagecreatefromjpeg($this->filename);
			break;

			case 3:
			case IMG_PNG:
				$currentImage = @imagecreatefrompng($this->filename);
			break;

			default:
				throw new SAException('The file you specified "' . $currentMime . '" is not supported. Only gif, jpeg, jpg and png are supported.');
		}

		// current width is larger then current height
		if($currentWidth > $currentHeight)
		{
			$tempHeight = $this->height;
			$tempWidth = (int) floor($currentWidth * ($this->height / $currentHeight));
		}

		// current width equals current height
		if($currentWidth == $currentHeight)
		{
			$tempWidth = $this->width;
			$tempHeight = $this->width;
		}

		// current width is smaller then current height
		if($currentWidth < $currentHeight)
		{
			$tempWidth = $this->width;
			$tempHeight = (int) floor($currentHeight * ($this->width / $currentWidth));
		}

		// recalculate
		if($tempWidth < $this->width || $tempHeight < $this->height)
		{
			// current width is smaller than the current height
			if($currentWidth < $currentHeight)
			{
				$tempHeight = $this->height;
				$tempWidth = (int) floor($currentWidth * ($this->height / $currentHeight));
			}

			// current width is greater than the current height
			if($currentWidth > $currentHeight)
			{
				$tempWidth = $this->width;
				$tempHeight = (int) floor($currentHeight * ($this->width / $currentWidth));
			}
		}

		// create image resource
		$tempImage = @imagecreatetruecolor($tempWidth, $tempHeight);

		// set transparent
		@imagealphablending($tempImage, false);
		@imagesavealpha($tempImage, true);

		// resize
		$success = @imagecopyresampled($tempImage, $currentImage, 0, 0, 0, 0, $tempWidth, $tempHeight, $currentWidth, $currentHeight);

		// destroy original image
		imagedestroy($currentImage);

		// image creation fail
		if(!$success)
		{
			if($this->strict) throw new SAException('Something went wrong while resizing the image.');
			return false;
		}

		// calculate horizontal crop position
		switch($this->cropPositionHorizontal)
		{
			case 'left':
				$x = 0;
			break;

			case 'center':
				$x = (int) floor(($tempWidth - $this->width) / 2);
			break;

			case 'right':
				$x = (int) $tempWidth - $this->width;
			break;
		}

		// calculate vertical crop position
		switch($this->cropPositionVertical)
		{
			case 'top':
				$y = 0;
			break;

			case 'middle':
				$y = (int) floor(($tempHeight - $this->height) / 2);
			break;

			case 'bottom':
				$y = (int) $tempHeight - $this->height;
			break;
		}

		// init vars
		$newWidth = $this->width;
		$newHeight = $this->height;

		// validate
		if(!$this->allowEnlargement && ($newWidth > $currentWidth || $newHeight > $currentHeight))
		{
			if($this->strict) throw new SAException('The specified width/height is larger then the original width/height. Please enable allowEnlargement.');
			return false;
		}

		// create image resource
		$this->image = @imagecreatetruecolor($this->width, $this->height);

		// set transparent
		@imagealphablending($this->image, false);
		$colorTransparent = @imagecolorallocatealpha($this->image, 0, 0, 0, 127);
		@imagefill($this->image, 0, 0, $colorTransparent);
		@imagesavealpha($this->image, true);

		// resize
		$success = @imagecopyresampled($this->image, $tempImage, 0, 0, $x, $y, $newWidth, $newHeight, $newWidth, $newHeight);

		// destroy temp
		@imagedestroy($tempImage);

		// image creation fail
		if(!$success)
		{
			if($this->strict) throw new SAException('Something went wrong while resizing the image.');
			return false;
		}

		// set transparent for GIF
		if($currentType == IMG_GIF)
		{
			// get transparent index
			$transparentIndex = @imagecolortransparent($currentImage);

			// valid index
			if($transparentIndex > 0)
			{
				// magic
				$transparentColor = @imagecolorsforindex($currentImage, $transparentIndex);
				$transparentIndex = @imagecolorallocate($this->image, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);

				// fill
				@imagefill($this->image, 0, 0, $transparentIndex);
				@imagecolortransparent($this->image, $transparentIndex);
			}
		}
	}


	/**
	 * set the allowEnlargement, default is false.
	 *
	 * @param	bool[optional] $on	May the original image be enlarged.
	 */
	public function setAllowEnlargement($on = false)
	{
		$this->allowEnlargement = (bool) $on;
	}


	/**
	 * Sets the horizontal and vertical cropposition.
	 *
	 * @return	mixed							In strict-mode it wil return false on errors.
	 * @param	string[optional] $horizontal	The horizontal crop position, possible values are: left, center, right.
	 * @param	string[optional] $vertical		The vertical crop position, possible values are: top, middle, bottom.
	 */
	public function setCropPosition($horizontal = 'center', $vertical = 'middle')
	{
		// redefine vars
		$horizontal = (string) $horizontal;
		$vertical = (string) $vertical;

		// validate horizontal
		if(input::getValue($horizontal, array('left', 'center', 'right'), '') == '')
		{
			if($this->strict) throw new SAException('The horizontal crop-position "' . $horizontal . '" isn\'t valid.');
			return false;
		}

		// validte vertical
		if(input::getValue($vertical, array('top', 'middle', 'bottom'), '') == '')
		{
			if($this->strict) throw new SAException('The vertical crop-position "' . $vertical . '" isn\'t valid.');
			return false;
		}

		// set properties
		$this->cropPositionHorizontal = $horizontal;
		$this->cropPositionVertical = $vertical;
	}


	/**
	 * Enables the Force aspect ratio.
	 *
	 * @param	bool[optional] $on	Should the original aspect ratio be respected?
	 */
	public function setForceOriginalAspectRatio($on = true)
	{
		$this->forceOriginalAspectRatio = (bool) $on;
	}


	/**
	 * Set the strict option.
	 *
	 * @param	bool[optional] $on	Should strict-mode be enabled?
	 */
	public function setStrict($on = true)
	{
		$this->strict = (bool) $on;
	}
}
/*
$cache = $_SERVER['DOCUMENT_ROOT'].'/cache/';

$image_request = $_REQUEST['image'];
if ( get_magic_quotes_gpc() ) $image_request = stripslashes( $_REQUEST['image'] );

new Thumby( $image_request, $cache, ( int ) $_REQUEST['w'], ( int ) ( $_REQUEST['h'] ), isset( $_REQUEST['f'] ) );
*/
class Thumby {

	public static function not_found() {
		header( 'HTTP/1.0 404 Not Found' );
		echo 'File not found.';
		exit;
	}

	function Thumby( $image, $pathto_cache, $max_width = 1200, $max_height = 1200, $force_size = 0 ) {
		if ( $force_size = '' ) $force_size = 0;
		if ( $max_width == 0 ) $max_width = 1200;
		if ( $max_height == 0 ) $max_height = 1200;

		// If you know what you're doing, you can set this to "im" for ImageMagick, make sure to change $convert_path below
		$software = 'gd';
		$gal_path = $_SERVER['DOCUMENT_ROOT'];
		$ext = strtolower( pathinfo( $image, PATHINFO_EXTENSION ) );
		$extensions = explode( ',', 'jpg,jpe,jpeg,png,gif' );
			$image_path = $gal_path.'/'.$image;

		if ( strpos($image, './' ) or !in_array( $ext, $extensions ) or !file_exists( $image_path ) )
				self::not_found();

			$thumb_path = $pathto_cache.$max_width.'x'.$max_height.( $force_size?'f':'' ).strtr( '-$gallery-$image', ':/?\\', '----' );
			$imageModified = @filemtime( $image_path );
			$thumbModified = @filemtime( $thumb_path );

		switch( $ext ) {
			case 'gif' : header( 'Content-type: image/gif' ); break;
			case 'png' : header( 'Content-type: image/png' ); break;
			default: header( 'Content-type: image/jpeg' ); break;
		}
		//if thumbnail is newer than image then output cached thumbnail and exit
		if ( $imageModified<$thumbModified ) {
			header( 'Last-Modified: '.gmdate( 'D, d M Y H:i:s', $thumbModified).' GMT' );
			readfile($thumb_path);
			exit;
		} else {
			$this->make_thumb($image_path, $thumb_path, $max_width, $max_height, $force_size);
		}
	}

	function make_thumb( $image_path, $thumb_path, $max_width = 1200, $max_height = 1200, $force_size, $software='gd2', $convert_path = null ) {

		$convert_path = '/opt/local/bin/convert';

		$thumbQuality = 95;
		list( $image_width, $image_height, $image_type ) = GetImageSize( $image_path );

		//if aspect ratio is to be constrained set crop size
		if ( $force_size ) {
			$newAspect = $max_width/$max_height;
			$oldAspect = $image_width/$image_height;

			if ( $newAspect > $oldAspect ) {
				$cropWidth = $image_width;
				$cropHeight = round( $oldAspect/$newAspect * $image_height );
			} else {
				$cropWidth = round( $newAspect/$oldAspect * $image_width );
				$cropHeight = $image_height;
			}
		//else crop size is image size
		} else {
			$cropWidth = $image_width;
			$cropHeight = $image_height;
		}

		//set cropping offset
		$cropX = floor( ( $image_width-$cropWidth )/2 );
		$cropY = floor( ( $image_height-$cropHeight )/2 );

		//compute width and height of thumbnail to create
		if ( $cropWidth >= $max_width && ( $cropHeight < $max_height || ( $cropHeight > $max_height && round( $cropWidth/$cropHeight * $max_height ) > $max_width ) ) ) {
			$thumbWidth = $max_width;
			$thumbHeight = round( $cropHeight/$cropWidth * $max_width );
		} elseif ( $cropHeight >= $max_height ) {
			$thumbWidth = round( $cropWidth/$cropHeight * $max_height );
		    $thumbHeight = $max_height;
		} else {
			//image is smaller than required dimensions so output it and exit
			readfile( $image_path );
			exit;
		}

		switch( $software ) {
			case 'im' : //use ImageMagick
			// hack for square thumbs;
			if ( ( $thumbWidth == $thumbHeight ) or $force_size ) {
				$thumbsize = $thumbWidth;
				if ( $image_height > $image_width ) {
					$cropY = -($thumbsize / 2);
					$cropX = 0;
					$thumbcommand = '{$thumbsize}x';
				} else {
					$cropY = -($thumbsize / 2);
					$cropX = 0;
					$thumbcommand = 'x{$thumbsize}';
				}
			} else {
				$thumbcommand = $thumbWidth.'x'.$thumbHeight;
			}
			$cmd  = '"'.$convert_path.'"';
			if ( $force_size ) $cmd .= ' -gravity center -crop {$thumbWidth}x{$thumbHeight}!+0+0';
			$cmd .= ' -resize {$thumbcommand}';
			if ( $image_type == 2 ) $cmd .= ' -quality $thumbQuality';
			$cmd .= ' -interlace Plane';
			$cmd .= ' +profile "*"';
			$cmd .= ' '.escapeshellarg( $image_path ).' '.escapeshellarg( $thumb_path );
			exec( $cmd );
			readfile( $thumb_path );
			exit;
			break;

			case 'gd2' :
			default : //use GD by default
			//read in image as appropriate type
			switch( $image_type ) {
				case 1 : $image = ImageCreateFromGIF( $image_path ); break;
				case 3 : $image = ImageCreateFromPNG( $image_path ); break;
				case 2 :
				default: $image = ImageCreateFromJPEG( $image_path ); break;
			}

			//create blank truecolor image
			$thumb = ImageCreateTrueColor( $thumbWidth,$thumbHeight );

			//resize image with resampling
			ImageCopyResampled( $thumb, $image, 0, 0, $cropX, $cropY, $thumbWidth, $thumbHeight, $cropWidth, $cropHeight );

			//set image interlacing
			ImageInterlace( $thumb, $this->config->progressive_thumbs );

			//output image of appropriate type
			switch( $image_type ) {
				case 1 :
				//GIF images are output as PNG
				case 3 :
				ImagePNG( $thumb,$thumb_path );
				break;
				case 2 :
				default:
				ImageJPEG( $thumb,$thumb_path,$thumbQuality );
				break;
			}
			ImageDestroy( $image );
			ImageDestroy( $thumb );
			readfile( $thumb_path );
		}
	}
}