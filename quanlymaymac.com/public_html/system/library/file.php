<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * File Library For SA Framework
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class file
{
	public $fh = FALSE;
	public $location = FALSE;
	public $method = FALSE;
	public $lock = FALSE;
    public $file = FALSE;
	
	
	// Construct
	// ---------------------------------------------------------------------------
	public function __construct($location=FALSE,$method='r',$lock=LOCK_EX)
	{
		if($location)
		{
			$this->file = $this->location = $location;
            
			$this->method = $method;
			$this->lock = $lock;
			
			$this->fh = fopen($this->location,$this->method);
			flock($this->fh,$this->lock);
		}
		else
		{
			$this->fh = tmpfile();
		}
		
		return $this;
	}
	
	
	// Read
	// ---------------------------------------------------------------------------
	public function read($chars=FALSE)
	{
		if(!$chars)
		{
			$chars = filesize($this->location);
		}
		
		return fread($this->fh,$chars);
	}
	
	
	// Write
	// ---------------------------------------------------------------------------
	public function write($data=FALSE)
	{
		fwrite($this->fh,$data);
	}
	
	
	// Read Line
	// ---------------------------------------------------------------------------
	public function read_lines($data=FALSE)
	{
		return fgets($this->fh,$data);
	}
	
	
	// Seek
	// ---------------------------------------------------------------------------
	public function seek($offset=0,$whence=FALSE)
	{
		fseek($this->fh,$offset,$whence=FALSE);
	}
	
	
	// Scan File
	// ---------------------------------------------------------------------------
	public function scan($format)
	{
		return fscanf($this->fh,$format);
	}
	
	
	// Close
	// ---------------------------------------------------------------------------
	public function close()
	{
		if($this->fh)
		{
			if($this->lock)
			{
				flock($this->fh,LOCK_UN);
			}
			
			fclose($this->fh);
			$this->fh = FALSE;
		}
	}
	
	
	// Destruct
	// ---------------------------------------------------------------------------
	public function __destruct()
	{
		$this->close();
	}
	
	
	// MIME Type
	// ---------------------------------------------------------------------------
	/**
	 * Determines the file's mime type by either looking at the file contents or matching the extension
	 * 
	 * Please see the ::getMimeType() description for details about how the
	 * mime type is determined and what mime types are detected.
	 * 
	 * @internal
	 * 
	 * @param  string $file      The file to check the mime type for - must be a valid filesystem path if no `$contents` are provided, otherwise just a filename
	 * @param  string $contents  The first 4096 bytes of the file content - the `$file` parameter only need be a filename if this is provided
	 * @return string  The mime type of the file
	 */
	static public function mime($file, $contents=NULL)
	{
		// If no contents are provided, we must get them
		if ($contents === NULL) {
			if (!file_exists($file)) {
				throw new SAException(
					'The file specified, %s, does not exist',
					$file
				);
			}
			
			// The first 4k should be enough for content checking
			$handle   = fopen($file, 'r');
			$contents = fread($handle, 4096);
			fclose($handle);
		}
		
		$extension = strtolower(filesystem::getPathInfo($file, 'extension'));
		
		// If there are no low ASCII chars and no easily distinguishable tokens, we need to detect by file extension
		if (!preg_match('#[\x00-\x08\x0B\x0C\x0E-\x1F]|%PDF-|<\?php|\%\!PS-Adobe-3|<\?xml|\{\\\\rtf|<\?=|<html|<\!doctype|<rss|\#\![/a-z0-9]+(python|ruby|perl|php)\b#i', $contents)) {
			return self::determineMimeTypeByExtension($extension);		
		}
		
		return self::determineMimeTypeByContents($contents, $extension);
	}
	
	
	/**
	 * Looks for specific bytes in a file to determine the mime type of the file
	 * 
	 * @param  string $content    The first 4 bytes of the file content to use for byte checking
	 * @param  string $extension  The extension of the filetype, only used for difficult files such as Microsoft office documents
	 * @return string  The mime type of the file
	 */
	static private function determineMimeTypeByContents($content, $extension)
	{
		$length = strlen($content);
		$_0_8   = substr($content, 0, 8);
		$_0_6   = substr($content, 0, 6);
		$_0_5   = substr($content, 0, 5);
		$_0_4   = substr($content, 0, 4);
		$_0_3   = substr($content, 0, 3);
		$_0_2   = substr($content, 0, 2);
		$_8_4   = substr($content, 8, 4);
		
		// Images
		if ($_0_4 == "MM\x00\x2A" || $_0_4 == "II\x2A\x00") {
			return 'image/tiff';	
		}
		
		if ($_0_8 == "\x89PNG\x0D\x0A\x1A\x0A") {
			return 'image/png';	
		}
		
		if ($_0_4 == 'GIF8') {
			return 'image/gif';	
		}
		
		if ($_0_2 == 'BM' && $length > 14 && in_array($content[14], array("\x0C", "\x28", "\x40", "\x80"))) {
			return 'image/x-ms-bmp';	
		}
		
		$normal_jpeg    = $length > 10 && in_array(substr($content, 6, 4), array('JFIF', 'Exif'));
		$photoshop_jpeg = $length > 24 && $_0_4 == "\xFF\xD8\xFF\xED" && substr($content, 20, 4) == '8BIM';
		if ($normal_jpeg || $photoshop_jpeg) {
			return 'image/jpeg';	
		}
		
		if (preg_match('#^[^\n\r]*\%\!PS-Adobe-3#', $content)) {
			return 'application/postscript';			
		}
		
		if ($_0_4 == "\x00\x00\x01\x00") {
			return 'application/vnd.microsoft.icon';	
		}
		
		
		// Audio/Video
		if ($_0_4 == 'MOVI') {
			if (in_array($_4_4, array('moov', 'mdat'))) {
				return 'video/quicktime';
			}	
		}
		
		if ($length > 8 && substr($content, 4, 4) == 'ftyp') {
			
			$_8_3 = substr($content, 8, 3);
			$_8_2 = substr($content, 8, 2);
			
			if (in_array($_8_4, array('isom', 'iso2', 'mp41', 'mp42'))) {
				return 'video/mp4';
			}	
			
			if ($_8_3 == 'M4A') {
				return 'audio/mp4';
			}
			
			if ($_8_3 == 'M4V') {
				return 'video/mp4';
			}
			
			if ($_8_3 == 'M4P' || $_8_3 == 'M4B' || $_8_2 == 'qt') {
				return 'video/quicktime';	
			}
		}
		
		// MP3
		if (($_0_2 & "\xFF\xF6") == "\xFF\xF2") {
			if (($content[2] & "\xF0") != "\xF0" && ($content[2] & "\x0C") != "\x0C") {
				return 'audio/mpeg';
			}	
		}
		if ($_0_3 == 'ID3') {
			return 'audio/mpeg';	
		}
		
		if ($_0_8 == "\x30\x26\xB2\x75\x8E\x66\xCF\x11") {
			if ($content[24] == "\x07") {
				return 'audio/x-ms-wma';
			}
			if ($content[24] == "\x08") {
				return 'video/x-ms-wmv';
			}
			return 'video/x-ms-asf';	
		}
		
		if ($_0_4 == 'RIFF' && $_8_4 == 'AVI ') {
			return 'video/x-msvideo';	
		}
		
		if ($_0_4 == 'RIFF' && $_8_4 == 'WAVE') {
			return 'audio/x-wav';	
		}
		
		if ($_0_4 == 'OggS') {
			$_28_5 = substr($content, 28, 5);
			if ($_28_5 == "\x01\x76\x6F\x72\x62") {
				return 'audio/vorbis';	
			}
			if ($_28_5 == "\x07\x46\x4C\x41\x43") {
				return 'audio/x-flac';	
			}
			// Theora and OGM	
			if ($_28_5 == "\x80\x74\x68\x65\x6F" || $_28_5 == "\x76\x69\x64\x65") {
				return 'video/ogg';		
			}
		}
		
		if ($_0_3 == 'FWS' || $_0_3 == 'CWS') {
			return 'application/x-shockwave-flash';	
		}
		
		if ($_0_3 == 'FLV') {
			return 'video/x-flv';	
		}
		
		
		// Documents
		if ($_0_5 == '%PDF-') {
			return 'application/pdf'; 	
		}
		
		if ($_0_5 == '{\rtf') {
			return 'text/rtf';	
		}
		
		// Office '97-2003 or Office 2007 formats
		if ($_0_8 == "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1" || $_0_8 == "PK\x03\x04\x14\x00\x06\x00") {
			if (in_array($extension, array('xlsx', 'xls', 'csv', 'tab'))) {
				return 'application/vnd.ms-excel';	
			}
			if (in_array($extension, array('pptx', 'ppt'))) {	
				return 'application/vnd.ms-powerpoint';
			}
			// We default to word since we need something if the extension isn't recognized
			return 'application/msword';
		}
		
		if ($_0_8 == "\x09\x04\x06\x00\x00\x00\x10\x00") {
			return 'application/vnd.ms-excel';	
		}
		
		if ($_0_6 == "\xDB\xA5\x2D\x00\x00\x00" || $_0_5 == "\x50\x4F\x5E\x51\x60" || $_0_4 == "\xFE\x37\x0\x23" || $_0_3 == "\x94\xA6\x2E") {
			return 'application/msword';	
		}
		
		
		// Archives
		if ($_0_4 == "PK\x03\x04") {
			return 'application/zip';	
		}
		
		if ($length > 257) {
			if (substr($content, 257, 6) == "ustar\x00") {
				return 'application/x-tar';	
			}
			if (substr($content, 257, 8) == "ustar\x40\x40\x00") {
				return 'application/x-tar';	
			}
		}
		
		if ($_0_4 == 'Rar!') {
			return 'application/x-rar-compressed';	
		}
		
		if ($_0_2 == "\x1F\x9D") {
			return 'application/x-compress';	
		}
		
		if ($_0_2 == "\x1F\x8B") {
			return 'application/x-gzip';	
		}
		
		if ($_0_3 == 'BZh') {
			return 'application/x-bzip2';	
		}
		
		if ($_0_4 == "SIT!" || $_0_4 == "SITD" || substr($content, 0, 7) == 'StuffIt') {
			return 'application/x-stuffit';	
		}	
		
		
		// Text files
		if (strpos($content, '<?xml') !== FALSE) {
			if (stripos($content, '<!DOCTYPE') !== FALSE) {
				return 'application/xhtml+xml';
			}
			if (strpos($content, '<svg') !== FALSE) {
				return 'image/svg+xml';
			}
			if (strpos($content, '<rss') !== FALSE) {
				return 'application/rss+xml';
			}
			return 'application/xml';	
		}   
		
		if (strpos($content, '<?php') !== FALSE || strpos($content, '<?=') !== FALSE) {
			return 'application/x-httpd-php';	
		}
		
		if (preg_match('#^\#\![/a-z0-9]+(python|perl|php|ruby)$#mi', $content, $matches)) {
			switch (strtolower($matches[1])) {
				case 'php':
					return 'application/x-httpd-php';
				case 'python':
					return 'application/x-python';
				case 'perl':
					return 'application/x-perl';
				case 'ruby':
					return 'application/x-ruby';
			}	
		}
		
		
		// Default
		return 'application/octet-stream';
	}
	
	
	/**
	 * Uses the extension of the all-text file to determine the mime type
	 * 
	 * @param  string $extension  The file extension
	 * @return string  The mime type of the file
	 */
	static private function determineMimeTypeByExtension($extension)
	{
		switch ($extension) {
			case 'css':
				return 'text/css';
			
			case 'csv':
				return 'text/csv';
			
			case 'htm':
			case 'html':
			case 'xhtml':
				return 'text/html';
				
			case 'ics':
				return 'text/calendar';
			
			case 'js':
				return 'application/javascript';
			
			case 'php':
			case 'php3':
			case 'php4':
			case 'php5':
			case 'inc':
				return 'application/x-httpd-php';
				
			case 'pl':
			case 'cgi':
				return 'application/x-perl';
			
			case 'py':
				return 'application/x-python';
			
			case 'rb':
			case 'rhtml':
				return 'application/x-ruby';
			
			case 'rss':
				return 'application/rss+xml';
				
			case 'tab':
				return 'text/tab-separated-values';
			
			case 'vcf':
				return 'text/x-vcard';
			
			case 'xml':
				return 'application/xml';
			
			default:
				return 'text/plain';	
		}
	}
	
	public static function is_image($mime){
	   //return substr(self::mime($this->location),0,6)=='image/';
       return substr($mime,0,6)=='image/';
	}
	// Download
	// ---------------------------------------------------------------------------
	static function download($file,$name)
	{
		header("Content-disposition:attachment;filename=$name");
		header('Content-type:'+self::mime($file));
		readfile($file);
	}
    
	/**
	 * Deletes one or more files.
	 *
	 * @return	bool				True if the file was deleted, false if not.
	 * @param	mixed $filename		Full path (including filename) of the file(s) that should be deleted.
	 */
	public static function delete($filename)
	{
		// an array
		if(is_array($filename)) foreach($filename as $file) @unlink((string) $file);

		// string
		else return @unlink((string) $filename);
	}
    
	/**
	 * Does this file exist.
	 *
	 * @return	bool				True if the file exists, false if not.
	 * @param	string $filename	The full path of the file to check for existance.
	 */
	public static function exists($filename)
	{
		return (@file_exists((string) $filename) && is_file((string) $filename));
	}


	/**
	 * Fetch the content from a file or URL.
	 *
	 * @return	string				The content.
	 * @param	string $filename	The path or URL to the file. URLs will only work if fopen-wrappers are enabled.
	 */
	public static function getContent($filename)
	{
		return @file_get_contents((string) $filename);
	}


	/**
	 * Fetch the extension for a filename.
	 *
	 * @return	string						The extension.
	 * @param	string $filename			The full path of the file.
	 * @param	bool[optional] $lowercase	Should the extension be returned in lowercase or in its original form.
	 */
	public static function getExtension($filename, $lowercase = true)
	{
		// init var
		$filename = ($lowercase) ? strtolower((string) $filename) : (string) $filename;

		// fetch extension
		$chunks = (array) explode('.', $filename);

		// count the chunks
		$count = count($chunks);

		// has an extension
		if($count != 0)
		{
			// extension can only have alphanumeric chars
			if(validate::alphanumeric($chunks[$count - 1])) return $chunks[$count - 1];
		}

		// no extension
		return '';
	}

    public static function getname($filename){
        return pathinfo($filename,PATHINFO_BASENAME);
    }
	/**
	 * Fetch the information about a file.
	 *
	 * @return	array				An array that contains a lot of information about the file.
	 * @param	string $filename	The path of the file.
	 */
	public static function getInfo($filename)
	{
		// redefine
		$filename = (string) $filename;

		// init var
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

		// fetch pathinfo
		$pathInfo = pathinfo($filename);

		// clear cache
		@clearstatcache();

		// build details array
		$file = array();
		$file['basename'] = $pathInfo['basename'];
		$file['extension'] = self::getExtension($filename);
		$file['name'] = substr($file['basename'], 0, strlen($file['basename']) - strlen($file['extension']) -1);
		$file['size'] = @filesize($filename);
		$file['is_executable'] = @is_executable($filename);
		$file['is_readable'] = @is_readable($filename);
		$file['is_writable'] = @is_writable($filename);
		$file['modification_date'] = @filemtime($filename);
		$file['path'] = $pathInfo['dirname'];
		$file['permissions'] = @fileperms($filename);

		// calculate human readable size
		$size = $file['size'];
		$mod = 1024;
		for($i = 0; $size > $mod; $i++) $size /= $mod;
		$file['human_readable_size'] = round($size, 2) . ' ' . $units[$i];

		// clear cache
		@clearstatcache();

		// cough it up
		return $file;
	}


	/**
	 * Retrieves a list of files within a directory.
	 *
	 * @return	array								An array containing a list of files in the given directory.
	 * @param	string $path						The path to the directory.
	 * @param	string[optional] $includeRegexp		A regular expresion that filters the files that should be included in the list.
	 */
	public static function getList($path, $includeRegexp = null)
	{
		// redefine arguments
		$path = (string) $path;

		// validate regex
		if($includeRegexp !== null)
		{
			// redefine
			$includeRegexp = (string) $includeRegexp;

			// validate
			if(!validate::regexp($includeRegexp)) throw new SAException('Invalid regular expression (' . $includeRegexp . ')');
		}

		// define list
		$files = array();

		// directory exists
		if(directory::exists($path))
		{
			// attempt to open directory
			if($directory = @opendir($path))
			{
				// start reading
				while((($file = readdir($directory)) !== false))
				{
					// no '.' and '..' and it's a file
					if(($file != '.') && ($file != '..') && is_file($path . '/' . $file))
					{
						// is there a include-pattern?
						if($includeRegexp !== null)
						{
							// init var
							$matches = array();

							// is this a match?
							if(preg_match($includeRegexp, $file, $matches) != 0) $files[] = $file;
						}

						// no excludes defined
						else $files[] = $file;
					}
				}
			}

			// close directory
			@closedir($directory);
		}

		// directory doesn't exist or a problem occured
		return $files;
	}


	/**
	 * Move/rename a directory/file.
	 *
	 * @return	bool						True if the file was moved or renamed, false if not.
	 * @param	string $source				Path of the source file.
	 * @param	string $destination			Path of the destination.
	 * @param 	bool[optional] $overwrite	Should an existing file be overwritten?
	 * @param	int[optional] $chmod		Chmod mode that should be applied on the file/directory.
	 */
	public static function move($source, $destination, $overwrite = true, $chmod = 0777)
	{
		return directory::move($source, $destination, $overwrite, $chmod);
	}


	/**
	 * Writes a string to a file.
	 *
	 * @return	bool						True if the content was written, false if not.
	 * @param	string $filename			The path of the file.
	 * @param	string $content				The content that should be written.
	 * @param	bool[optional] $createFile	Should the file be created if it doesn't exists?
	 * @param	bool[optional] $append		Should the content be appended if the file already exists?
	 * @param	int[optional] $chmod		Mode that should be applied on the file.
	 */
	public static function setContent($filename, $content, $createFile = true, $append = false, $chmod = 0777)
	{
		// redefine vars
		$filename = (string) $filename;
		$content = (string) $content;
		$createFile = (bool) $createFile;
		$append = (bool) $append;

		// file may not be created, but it doesn't exist either
		if(!$createFile && self::exists($filename)) throw new SAException('The file "' . $filename . '" doesn\'t exist');

		// create directory recursively if needed
		directory::create(dirname($filename), $chmod, true);

		// create file & open for writing
		$handler = ($append) ? @fopen($filename, 'a') : @fopen($filename, 'w');

		// something went wrong
		if($handler === false) throw new SAException('The file "' . $filename . '" could not be created. Check if PHP has enough permissions.');

		// store error reporting level
		$level = error_reporting();

		// disable errors
		error_reporting(0);

		// write to file
		$write = fwrite($handler, $content);

		// validate write
		if($write === false) throw new SAException('The file "' . $filename . '" could not be written to. Check if PHP has enough permissions.');

		// close the file
		fclose($handler);

		// chmod file
		chmod($filename, $chmod);

		// restore error reporting level
		error_reporting($level);

		// status
		return true;
	}
        
}