<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * Image Library SA Framework
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class image extends file
{
	private $_image;
	private $_type;
	private $_quality = 100;
	private $_compression = 4;
	public $width;
	public $height;
	public $file;
	
	// Construct
	// ---------------------------------------------------------------------------
	public function __construct($file=FALSE)
	{
		if($file)
		{
			$this->open($file);
		}
	}
 
	// Open Image
	// ---------------------------------------------------------------------------
	public function open($file)
	{
	    $this->file = $file;
		// Check to see if image exists
		if(!file_exists($file))
		{
			sa_error("Image file not found: The image file ($file) could not be found.",E_USER_ERROR);
		}
		
		// Attempt to open image
		else
		{
			$fs = getimagesize($file);
			
			switch($fs['mime'])
			{
				case 'image/pjpeg':
					$this->_image = imagecreatefromjpeg($file);
					$this->_type = 'jpg';
				break;
				case 'image/jpeg':
					$this->_image = imagecreatefromjpeg($file);
					$this->_type = 'jpg';
				break;
				case 'image/gif':
					$this->_image = imagecreatefromgif($file);
					$this->_type = 'gif';
				break;
				case 'image/x-png':
					$this->_image = imagecreatefrompng($file);
					imagealphablending($this->_image,true);
					imagesavealpha($this->_image,true);
					$this->_type = 'png';
				break;
				case 'image/png':
					$this->_image = imagecreatefrompng($file);
					imagealphablending($this->_image,true);
					imagesavealpha($this->_image,true);
					$this->_type = 'png';
				break;
				default:
					throw new SAException('Invalid image: The given image could not be opened.');
				break;
			}
			
			$this->width = imagesx($this->_image);
			$this->height = imagesy($this->_image);
		}
		
		return $this;
	}
	
	
	// Set Image Type
	// ---------------------------------------------------------------------------
	public function type($type)
	{
		// Check to see if supported image type
		if(($type != 'jpg') AND ($type != 'gif') AND ($type != 'png'))
		{
			sa_error(E_USER_ERROR,"Invalid image type: The given image type ($type) was not valid.");
		}
		else
		{
			$this->_type = strtolower($type);
		}
		
		return $this;
	}
	
	
	// Image Quality
	// ---------------------------------------------------------------------------
	public function quality($q=100)
	{
		$this->_quality = $q;
		return $this;
	}
	
	
	// Image Compression
	// ---------------------------------------------------------------------------
	public function compression($c=4)
	{
		$this->_compression = $c;
		return $this;
	}
	
	
	// Resize
	// ---------------------------------------------------------------------------
	public function resize($x=NULL,$y=NULL)
	{
		// Width not given
		if(!$x)
		{
			$x = $this->width*($y/$this->height);
		}
		
		// Height not given
		elseif(!$y)
		{
			$y = $this->height*($x/$this->width);
		}
		
		$tmp = imagecreatetruecolor($x,$y);
		imagecopyresampled($tmp,$this->_image,0,0,0,0,$x,$y,$this->width,$this->height);
		$this->_image = $tmp;
		
		$this->width = $x;
		$this->height = $y;
		
		return $this;
	}
	
	
	// Crop
	// ---------------------------------------------------------------------------
	public function crop($x=0,$y=0,$width=1,$height=1)
	{
		
		$tmp = imagecreatetruecolor($width,$height);
		imagecopyresampled($tmp,$this->_image,0,0,$x,$y,$width,$height,$width,$height);
		$this->_image = $tmp;
		
		$this->width = $width;
		$this->height = $height;
		
		return $this;
	}
	
	
	// Dynamic Resize
	// ---------------------------------------------------------------------------
	public function dynamic_resize($new_width,$new_height)
	{
		// Taller image
		if($new_height > $new_width OR ($new_height == $new_width AND $this->height < $this->width))
		{
			$this->resize(NULL,$new_height);
			
			$w = ($new_width-$this->width)/-2;
			$this->crop($w,0,$new_width,$new_height);
		}
		
		// Wider image
		else
		{
			$this->resize($new_width,NULL);
			
			$y = ($new_height-$this->height)/-2;
			$this->crop(0,$y,$new_width,$new_height);
		}
		
		$this->width = $new_width;
		$this->height = $new_height;
		
		return $this;
	}
	
	
	// Square
	// ---------------------------------------------------------------------------
	public function square($size)
	{
		return $this->dynamic_resize($size,$size);
	}
	
	
	// Zone Crop
	// ---------------------------------------------------------------------------
	public function zone_crop($width,$height,$zone='center')
	{
		// Center
		if($zone == 'center')
		{
			$x = ($width-$this->width)/-2;
			$y = ($height-$this->height)/-2;
		}
		
		// Top Left
		elseif($zone == 'top-left')
		{
			$x = 0;
			$y = 0;
		}
		
		// Top
		elseif($zone == 'top')
		{
			$x = ($this->width-$width)/2;
			$y = 0;
		}
		
		// Top Right
		elseif($zone == 'top-right')
		{
			$x = $this->width-$width;
			$y = 0;
		}
		
		// Right
		elseif($zone == 'right')
		{
			$x = $this->width-$width;
			$y = ($this->height-$height)/2;
		}
		
		// Bottom Right
		elseif($zone == 'bottom-right')
		{
			$x = $this->width-$width;
			$y = $this->height-$height;
		}
		
		// Bottom
		elseif($zone == 'bottom')
		{
			$x = ($this->width-$width)/2;
			$y = $this->height-$height;
		}
		
		// Bottom Left
		elseif($zone == 'bottom-left')
		{
			$x = 0;
			$y = $this->height-$height;
		}
		
		// Left
		elseif($zone == 'left')
		{
			$x = 0;
			$y = ($this->height-$height)/2;
		}
		
		// Invalid Zone
		else
		{
			sa_error(E_USER_ERROR,"Invalid image crop zone '$zone' given for image helper zone_crop().");
		}
		
		return $this->crop($x,$y,$width,$height);
	}
	
	
	// Rotate
	// ---------------------------------------------------------------------------
	public function rotate($deg=0,$bg=0)
	{
		$this->_image = imagerotate($this->_image,$deg,$bg);
		
		return $this;
	}
	
	
	// Save Image
	// ---------------------------------------------------------------------------
	public function save($file)
	{
		// JPG
		if($this->_type == 'jpg')
		{
			imagejpeg($this->_image,$file,$this->_quality);
		}
		
		// GIF
		elseif($this->_type == 'gif')
		{
			imagegif($this->_image,$file,$this->_quality);
		}
		
		// PNG
		elseif($this->_type == 'png')
		{
			imagepng($this->_image,$file,$this->_compression);
		}
		
		return $this;
	}
	
	
	// Show Image
	// ---------------------------------------------------------------------------
	public function show()
	{
		// JPG
		if($this->_type == 'jpg')
		{
			header('Content-type:image/jpeg');
			imagejpeg($this->_image,NULL,$this->_quality);
		}
		
		// GIF
		elseif($this->_type == 'gif')
		{
			header('Content-type:image/gif');
			imagegif($this->_image,NULL,$this->_quality);
		}
		
		// PNG
		elseif($this->_type == 'png')
		{
			header('Content-type:image/png');
			imagepng($this->_image,NULL,$this->_compression);
		}
		
		return $this;
	}
	
	
	// Close
	// ---------------------------------------------------------------------------
	public function close()
	{
		imagedestroy($this->_image);
		
		return $this;
	}
    
	/**
	 * Checks to make sure the class can handle the image file specified
	 * 
	 * @internal
	 * 
	 * @throws SAException  When the image specified does not exist
	 * 
	 * @param  string $image  The image to check for incompatibility
	 * @return boolean  If the image is compatible with the detected image processor
	 */
	public static function isImageCompatible($image)
	{
 
		if (!file_exists($image)) {
			throw new SAException(
				'The image specified, %s, does not exist',
				$image
			);
		}
		
		$type = self::getImageType($image);
	
		if ($type === NULL || ($type == 'tif')) {
			return FALSE;
		}
		
		return TRUE;
	}
	/**
	 * Returns the type of the image
	 * 
	 * @return string  The type of the image: `'jpg'`, `'gif'`, `'png'`, `'tif'`
	 */
	public static function getType()
	{
		return self::getImageType($this->file);
	}  
	/**
	 * Gets the image type from a file by looking at the file contents
	 * 
	 * @param  string $image  The image path to get the type for
	 * @return string|NULL  The type of the image - `'jpg'`, `'gif'`, `'png'` or `'tif'` - NULL if not one of those  
	 */
	private static function getImageType($image)
	{
		$handle   = fopen($image, 'r');
		$contents = fread($handle, 12);
		fclose($handle);
		
		$_0_8  = substr($contents, 0, 8);
		$_0_4  = substr($contents, 0, 4);
		$_6_4  = substr($contents, 6, 4);
		$_20_4 = substr($contents, 20, 4);
		
		if ($_0_4 == "MM\x00\x2A" || $_0_4 == "II\x2A\x00") {
			return 'tif';
		}
		
		if ($_0_8 == "\x89PNG\x0D\x0A\x1A\x0A") {
			return 'png';
		}
		
		if ($_0_4 == 'GIF8') {
			return 'gif';
		}
		
		if ($_6_4 == 'JFIF' || $_6_4 == 'Exif' || ($_0_4 == "\xFF\xD8\xFF\xED" && $_20_4 == "8BIM")) {
			return 'jpg';
		}
		
		return NULL;
	}
 
      
}