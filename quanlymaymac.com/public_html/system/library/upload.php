<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Upload Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */
 
class upload
{
 
	/**
	 * Checks to see if the field specified is a valid file upload field
	 * 
	 * @throws SAException  If `$throw_exception` is `TRUE` and the request was not a POST or the content type is not multipart/form-data
	 *
	 * @param  string  $field            The field to check
	 * @param  boolean $throw_exception  If an exception should be thrown when there are issues with the form
	 * @return boolean  If the field is a valid file upload field
	 */
	static public function check($field, $throw_exception=TRUE)
	{
		if (isset($_GET[$field]) && $_SERVER['REQUEST_METHOD'] != 'POST') {
			if ($throw_exception) {
				throw new SAException(
					'Missing method="post" attribute in form tag'
				);
			}
			return FALSE;
		}
		
		if (isset($_POST[$field]) && (!isset($_SERVER['CONTENT_TYPE']) || stripos($_SERVER['CONTENT_TYPE'], 'multipart/form-data') === FALSE)) {
			if ($throw_exception) {
				throw new SAException(
					'Missing enctype="multipart/form-data" attribute in form tag'
				);
			}
			return FALSE;
		}
		 
		return isset($_FILES) && isset($_FILES[$field]) && is_array($_FILES[$field]) && !empty($_FILES[$field]["name"]);
	}
	
	
	/**
	 * Returns the number of files uploaded to a file upload array field
	 * 
	 * @throws SAException  If the form is not properly configured for file uploads
	 *
	 * @param  string  $field  The field to get the number of files for
	 * @return integer  The number of uploaded files
	 */
	static public function count($field, $throw_exception=TRUE)
	{
		if (!self::check($field, $throw_exception)) {
            if($throw_exception)
			throw new SAException(
				'The field specified, %s, does not appear to be a file upload field',
				$field
			);
		}
		
		if (!is_array($_FILES[$field]['name'])) {
		    if($throw_exception)
			throw new SAException(
				'The field specified, %s, does not appear to be an array file upload field',
				$field
			);
		}
		
		return sizeof($_FILES[$field]['name']);
	}
	
	
	/**
	 * Composes text using text if loaded
	 * 
	 * @param  string  $message    The message to compose
	 * @param  mixed   $component  A string or number to insert into the message
	 * @param  mixed   ...
	 * @return string  The composed and possible translated message
	 */
	static protected function compose($message)
	{
		$args = array_slice(func_get_args(), 1);
		
		if (class_exists('text', FALSE)) {
			return call_user_func_array(
				array('text', 'compose'),
				array($message, $args)
			);
		} else {
			return vsprintf($message, $args);
		}
	}
	
	
	/**
	 * Removes individual file upload entries from an array of file inputs in `$_FILES` when no file was uploaded
	 * 
	 * @throws SAException  If the form is not properly configured for file uploads
	 *
	 * @param  string  $field  The field to filter
	 * @return array  The indexes of the files that were uploaded
	 */
	static public function filter($field)
	{
		$indexes = array();
		$columns = array('name', 'type', 'tmp_name', 'error', 'size');
		
		if (!self::count($field)) {
			return;	
		}
		
		foreach (array_keys($_FILES[$field]['name']) as $index) {
			if ($_FILES[$field]['error'][$index] == UPLOAD_ERR_NO_FILE) {
				foreach ($columns as $column) {
					unset($_FILES[$field][$column][$index]);
				}
			} else {
				$indexes[] = $index;
			}	
		}
		
		return $indexes;
	}
	
	
	/**
	 * If files starting with `.` can be uploaded
	 * 
	 * @var boolean
	 */
	private $allow_dot_files = FALSE;
	
	/**
	 * If PHP files can be uploaded
	 * 
	 * @var boolean
	 */
	private $allow_php = FALSE;
	
	/**
	 * The dimension restrictions for uploaded images
	 * 
	 * @var array
	 */
	private $image_dimensions = array();
	
	/**
	 * The dimension ratio restriction for uploaded images
	 * 
	 * @var array
	 */
	private $image_ratio = array();
	
	/**
	 * If existing files of the same name should be overwritten
	 * 
	 * @var boolean
	 */
	private $enable_overwrite = FALSE;
	
	/**
	 * The maximum file size in bytes
	 * 
	 * @var integer
	 */
	private $max_size = 0;
	
	/**
	 * The error message to display if the mime types do not match
	 * 
	 * @var string
	 */
	private $mime_type_message = NULL;
	
	/**
	 * The mime types of files accepted
	 * 
	 * @var array
	 */
	private $mime_types = array();
	
	/**
	 * If the file upload is required
	 * 
	 * @var boolean
	 */
	private $required = TRUE;
	
	public $mime = false;
	/**
	 * All requests that hit this method should be requests for callbacks
	 * 
	 * @internal
	 * 
	 * @param  string $method  The method to create a callback for
	 * @return callback  The callback for the method requested
	 */
	public function __get($method)
	{
		return array($this, $method);		
	}
	
	
	/**
	 * Sets the upload class to allow files starting with a `.`
	 * 
	 * Files starting with `.` may change the behaviour of web servers,
	 * for instance `.htaccess` files for Apache.
	 * 
	 * @return void
	 */
	public function allowDotFiles()
	{
		$this->allow_dot_files = TRUE;
	}
	
	
	/**
	 * Sets the upload class to allow PHP files
	 * 
	 * @return void
	 */
	public function allowPHP()
	{
		$this->allow_php = TRUE;
	}
	
	
	/**
	 * Set the class to overwrite existing files in the destination directory instead of renaming the file
	 * 
	 * @return void
	 */
	public function enableOverwrite()
	{
		$this->enable_overwrite = TRUE;
	}
	
	
	/**
	 * Returns the `$_FILES` array for the field specified.
	 * 
	 * @param  string $field  The field to get the file array for
	 * @param  mixed  $index  If the field is an array file upload field, use this to specify which array index to return
	 * @return array  The file info array from `$_FILES`
	 */
	private function extractFileUploadArray($field, $index=NULL)
	{
		if ($index === NULL) {
			return $_FILES[$field];
		}
		
		if (!is_array($_FILES[$field]['name'])) {
			throw new SAException(
				'The field specified, %s, does not appear to be an array file upload field',
				$field
			);
		}
		
		if (!isset($_FILES[$field]['name'][$index])) {
			throw new SAException(
				'The index specified, %1$s, is invalid for the field %2$s',
				$index,
				$field
			);
		}
		
		$file_array = array();
		$file_array['name']     = $_FILES[$field]['name'][$index];
		$file_array['type']     = $_FILES[$field]['type'][$index];
		$file_array['tmp_name'] = $_FILES[$field]['tmp_name'][$index];
		$file_array['error']    = $_FILES[$field]['error'][$index];
		$file_array['size']     = $_FILES[$field]['size'][$index];
		
		return $file_array;
	}
	
	
	/**
	 * Moves an uploaded file from the temp directory to a permanent location
	 * 
	 * @throws SAException  When the form is not setup for file uploads, the `$directory` is somehow invalid or ::validate() thows an exception
	 * 
	 * @param  string|dir $directory  The directory to upload the file to
	 * @param  string            $field      The file upload field to get the file from
	 * @param  mixed             $index      If the field was an array file upload field, upload the file corresponding to this index
	 * @return file|NULL  An file (or image) object, or `NULL` if no file was uploaded
	 */
	public function move($directory, $field, $index=NULL)
	{
		if (!is_object($directory)) {
			$directory = new dir($directory);
		}
 
		//if (!$directory->isWritable()) {
        if ( !dir::is_Writable($directory->directory)) {
			throw new SAException(
				'The directory specified, %s, is not writable',
				$directory->getPath()
			);
		}
		
		if (!self::check($field)) {
			throw new SAException(
				'The field specified, %s, does not appear to be a file upload field',
				$field
			);
		}
		
		$file_array = $this->extractFileUploadArray($field, $index);
		$error      = $this->validateField($file_array);
		if ($error) {
			throw new SAException($error);
		}
		
		// This will only ever be true if the file is optional
		if ($file_array['name'] == '' || $file_array['tmp_name'] == '' || $file_array['size'] == 0) {
			return NULL;
		}
		
		$file_name  = filesystem::makeURLSafe($file_array['name']);
		
		$file_name = $directory->getPath() . $file_name;
		if (!$this->enable_overwrite) {
			$file_name = filesystem::makeUniqueName($file_name);
		}
		
		if (!move_uploaded_file($file_array['tmp_name'], $file_name)) {
			throw new SAException('There was an error moving the uploaded file');
		}
		
		if (!chmod($file_name, 0644)) {
			throw new SAException('Unable to change permissions on the uploaded file');
		}
		
		return filesystem::createObject($file_name);
	}
	
	
	/**
	 * Sets the allowable dimensions for an uploaded image
	 * 
	 * @param  integer $min_width   The minimum width - `0` for no minimum
	 * @param  integer $min_height  The minimum height - `0` for no minimum
	 * @param  integer $max_width   The maximum width - `0` for no maximum
	 * @param  integer $max_height  The maximum height - `0` for no maximum
	 * @return void
	 */
	public function setImageDimensions($min_width, $min_height, $max_width=0, $max_height=0)
	{
		if (!is_numeric($min_width) || $min_width < 0) {
			throw new SAException(
				'The minimum width specified, %s, is not an integer, or is less than 0',
				$min_width
			);
		}
		if (!is_numeric($min_height) || $min_height < 0) {
			throw new SAException(
				'The minimum height specified, %s, is not an integer, or is less than 0',
				$min_height
			);
		}
		if (!is_numeric($max_width) || $max_width < 0) {
			throw new SAException(
				'The maximum width specified, %s, is not an integer, or is less than 0',
				$max_width
			);
		}
		if (!is_numeric($max_height) || $max_height < 0) {
			throw new SAException(
				'The maximum height specified, %s, is not an integer, or is less than 0',
				$max_height
			);
		}
		
		settype($min_width,  'int');
		settype($min_height, 'int');
		settype($max_width,  'int');
		settype($max_height, 'int');
		
		// If everything is 0 then there are no restrictions
		if (!$min_width && !$min_height && !$max_width && !$max_height) {
			$this->image_dimensions = array();
			return;
		}
		
		$this->image_dimensions = array(
			'min_width'  => $min_width,
			'min_height' => $min_height,
			'max_width'  => $max_width,
			'max_height' => $max_height
		);
	}
	
	
	/**
	 * Sets the allowable dimensions for an uploaded image
	 * 
	 * @param  numeric $width                   The minimum ratio width
	 * @param  numeric $height                  The minimum ratio height
	 * @param  string  $allow_excess_dimension  The dimension that should allow for excess pixels
	 * @return void
	 */
	public function setImageRatio($width, $height, $allow_excess_dimension)
	{
		if (!is_numeric($width) || $width <= 0) {
			throw new SAException(
				'The width specified, %s, is not a number, or is less than or equal to 0',
				$width
			);
		}
		if (!is_numeric($height) || $height <= 0) {
			throw new SAException(
				'The height specified, %s, is not a number, or is less than or equal to 0',
				$height
			);
		}
		
		$valid_dimensions = array('width', 'height');
		if (!in_array($allow_excess_dimension, $valid_dimensions)) {
			throw new SAException(
				'The allow excess dimension specified, %1$s, is not valid. Must be one of: %2$s.',
				$allow_excess_dimension,
				$valid_dimensions
			);
		}
		
		$this->image_ratio = array(
			'width'                  => $width,
			'height'                 => $height,
			'allow_excess_dimension' => $allow_excess_dimension
		);
	}
	
	
	/**
	 * Sets the maximum size the uploaded file may be
	 * 
	 * This method should be used with the
	 * [http://php.net/file-upload.post-method `MAX_FILE_SIZE`] hidden form
	 * input since the hidden form input will reject a file that is too large
	 * before the file completely uploads, while this method will wait until the
	 * whole file has been uploaded. This method should always be used since it
	 * is very easy for the `MAX_FILE_SIZE` post field to be manipulated on the
	 * client side.
	 * 
	 * This method can only further restrict the
	 * [http://php.net/upload_max_filesize `upload_max_filesize` ini setting],
	 * it can not increase that setting. `upload_max_filesize` must be set
	 * in the php.ini (or an Apache configuration) since file uploads are
	 * handled before the request is handed off to PHP.
	 * 
	 * @param  string $size  The maximum file size (e.g. `1MB`, `200K`, `10.5M`) - `0` for no limit
	 * @return void
	 */
	public function setMaxSize($size)
	{
		$ini_max_size = ini_get('upload_max_filesize');
		$ini_max_size = (!is_numeric($ini_max_size)) ? filesystem::convertToBytes($ini_max_size) : $ini_max_size;
			
		$size = filesystem::convertToBytes($size);
		
		if ($size && $size > $ini_max_size) {
			throw new SAException(
				'The requested max file upload size, %1$s, is larger than the %2$s ini setting, which is currently set at %3$s. The ini setting must be increased to allow files of this size.',
				$max_size,
				'upload_max_filesize',
				$ini_max_size
			);
		}
		
		$this->max_size = $size;
	}
	
	
	/**
	 * Sets the file mime types accepted
	 * 
	 * @param  array  $mime_types  The mime types to accept
	 * @param  string $message     The message to display if the uploaded file is not one of the mime type specified
	 * @return void
	 */
	public function setMIMETypes($mime_types, $message)
	{
		$this->mime_types        = $mime_types;
		$this->mime_type_message = $message;
	}
	
	
	/**
	 * Sets the file upload to be optional instead of required
	 * 
	 * @return void
	 */
	public function setOptional()
	{
		$this->required = FALSE;
	}
	
	
	/**
	 * Validates the uploaded file, ensuring a file was actually uploaded and that is matched the restrictions put in place
	 * 
	 * @throws SAException  When the form is not configured for file uploads, no file is uploaded or the uploaded file violates the options set for this object
	 * 
	 * @param  string  $field           The field the file was uploaded through
	 * @param  mixed   $index           If the field was an array of file uploads, this specifies which one to validate
	 * @param  boolean $return_message  If any validation error should be returned as a string instead of being thrown as an fValidationException
	 * @param  string  |$field
	 * @param  boolean |$return_message
	 * @return NULL|string  If `$return_message` is not `TRUE` or if no error occurs, `NULL`, otherwise a string error message
	 */
	public function validate($field, $index=NULL, $return_message=NULL)
	{
		if (is_bool($index) && $return_message === NULL) {
			$return_message = $index;
			$index          = NULL;
		}
		
		if (!self::check($field)) {
			throw new SAException(
				'The field specified, %s, does not appear to be a file upload field',
				$field
			);
		}
		
		$file_array = $this->extractFileUploadArray($field, $index);
		$error      = $this->validateField($file_array);
		if ($error) {
			if ($return_message) {
				return $error;
			}
			throw new SAException($error);
		}
	}
	
	
	/**
	 * Validates a $_FILES array against the upload configuration
	 * 
	 * @param array $file_array  The $_FILES array for a single file
	 * @return string  The validation error message
	 */
	private function validateField($file_array)
	{
		if (empty($file_array['name'])) {
			if ($this->required) {
				return self::compose('Please upload a file');
			}
			return NULL;
		}
		
		if ($file_array['error'] == UPLOAD_ERR_FORM_SIZE || $file_array['error'] == UPLOAD_ERR_INI_SIZE) {
			$max_size = (!empty($_POST['MAX_FILE_SIZE'])) ? $_POST['MAX_FILE_SIZE'] : ini_get('upload_max_filesize');
			$max_size = (!is_numeric($max_size)) ? filesystem::convertToBytes($max_size) : $max_size;
			return self::compose(
				'The file uploaded is over the limit of %s',
				filesystem::formatFilesize($max_size)
			);
		}
		if ($this->max_size && $file_array['size'] > $this->max_size) {
			return self::compose(
				'The file uploaded is over the limit of %s',
				filesystem::formatFilesize($this->max_size)
			);
		}
		
		if (empty($file_array['tmp_name']) || empty($file_array['size'])) {
			if ($this->required) {
				return self::compose('Please upload a file');
			}
			return NULL;	
		}
		//$mime = $file_array['type']?$file_array['type']:
        //var_dump(empty($this->mime_types));die();
        if(file_exists($file_array['tmp_name'])){
            if($file_array['type']) $this->mime = $file_array['type'];
            else{
                $contents = file_get_contents($file_array['tmp_name'], FALSE, NULL, 0, 4096);
                $this->mime = file::mime($file_array['name'], $contents);
            }
        }
        
		if (!empty($this->mime_types) && file_exists($file_array['tmp_name'])) {
		 
			//if (!in_array(file::determineMimeType($file_array['name'], $contents), $this->mime_types)) {
			 if (!in_array($this->mime , $this->mime_types)) {
			// if (!in_array(file::mime($file_array['tmp_name']), $this->mime_types)) {
				return self::compose($this->mime_type_message);
			}
		}
		
		if (!$this->allow_php) {
			$file_info = filesystem::getPathInfo($file_array['name']);
			if (in_array(strtolower($file_info['extension']), array('php', 'php4', 'php5'))) {
				return self::compose('The file uploaded is a PHP file, but those are not permitted');
			}
		}
		
		if (!$this->allow_dot_files) {
			if (substr($file_array['name'], 0, 1) == '.') {
				return self::compose('The name of the uploaded file may not being with a .');
			}
		}
		
		if ($this->image_dimensions && file_exists($file_array['tmp_name'])) {
			if (image::isImageCompatible($file_array['tmp_name'])) {
				list($width, $height, $other) = getimagesize($file_array['tmp_name']);
				
				if ($this->image_dimensions['min_width'] && $width < $this->image_dimensions['min_width']) {
					return self::compose(
						'The uploaded image is narrower than the minimum width of %spx',
						$this->image_dimensions['min_width']
					);
				}
				
				if ($this->image_dimensions['min_height'] && $height < $this->image_dimensions['min_height']) {
					return self::compose(
						'The uploaded image is shorter than the minimum height of %spx',
						$this->image_dimensions['min_height']
					);
				}
				
				if ($this->image_dimensions['max_width'] && $width > $this->image_dimensions['max_width']) {
					return self::compose(
						'The uploaded image is wider than the maximum width of %spx',
						$this->image_dimensions['max_width']
					);
				}
				
				if ($this->image_dimensions['max_height'] && $height > $this->image_dimensions['max_height']) {
					return self::compose(
						'The uploaded image is taller than the maximum height of %spx',
						$this->image_dimensions['max_height']
					);
				}
			}
		}
		
		if ($this->image_ratio && file_exists($file_array['tmp_name'])) {
			if (image::isImageCompatible($file_array['tmp_name'])) {
				list($width, $height, $other) = getimagesize($file_array['tmp_name']);
				
				if ($this->image_ratio['allow_excess_dimension'] == 'width' && $width/$height < $this->image_ratio['width']/$this->image_ratio['height']) {
					return self::compose(
						'The uploaded image is too narrow for its height. The required ratio is %1$sx%2$s or wider.',
						$this->image_ratio['width'],
						$this->image_ratio['height']
					);
				}
				
				if ($this->image_ratio['allow_excess_dimension'] == 'height' && $width/$height > $this->image_ratio['width']/$this->image_ratio['height']) {
					return self::compose(
						'The uploaded image is too short for its width. The required ratio is %1$sx%2$s or taller.',
						$this->image_ratio['width'],
						$this->image_ratio['height']
					);
				}
			}
		}
	}
}