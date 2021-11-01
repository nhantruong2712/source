<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA Directory Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */

class dir
{
	/**
	 * A backtrace from when the file was deleted 
	 * 
	 * @var array
	 */
	protected $deleted = NULL;
	
	/**
	 * The full path to the directory
	 * 
	 * @var string
	 */
	public $directory;
    
	/**
	 * Creates an object to represent a directory on the filesystem
	 * 
	 * If multiple fDirectory objects are created for a single directory,
	 * they will reflect changes in each other including rename and delete
	 * actions.
	 * 
	 * @throws SAException  When no directory was specified, when the directory does not exist or when the path specified is not a directory
	 * 
	 * @param  string  $directory    The path to the directory
	 * @param  boolean $skip_checks  If file checks should be skipped, which improves performance, but may cause undefined behavior - only skip these if they are duplicated elsewhere
	 * @return directory
	 */
	public function __construct($directory, $skip_checks=FALSE)
	{
		if (!$skip_checks) {
			if (empty($directory)) {
				throw new SAException('No directory was specified');
			}
			
			if (!is_readable($directory)) {
				throw new SAException(
					'The directory specified, %s, does not exist or is not readable',
					$directory
				);
			}
			if (!is_dir($directory)) {
				throw new SAException(
					'The directory specified, %s, is not a directory',
					$directory
				);
			}
		}
		
		$directory = self::makeCanonical(realpath($directory));
		
		//echo 
        $this->directory =& filesystem::hookFilenameMap($directory);
		$this->deleted   =& filesystem::hookDeletedMap($directory);
		
		// If the directory is listed as deleted and we are not inside a transaction,
		// but we've gotten to here, then the directory exists, so we can wipe the backtrace
		if ($this->deleted !== NULL && !filesystem::isInsideTransaction()) {
			filesystem::updateDeletedMap($directory, NULL);
		}
	}   
    
	/**
	 * Makes sure a directory has a `/` or `\` at the end
	 * 
	 * @param  string $directory  The directory to check
	 * @return string  The directory name in canonical form
	 */
	static public function makeCanonical($directory)
	{
		if (substr($directory, -1) != '/' && substr($directory, -1) != '\\') {
			$directory .= DIRECTORY_SEPARATOR;
		}
		return $directory;
	}     
	/**
	 * Copies a file/folder.
	 *
	 * @return	bool						True if the file/directory was copied, false if not.
	 * @param	string $source				The full path to the source file/folder.
	 * @param	string $destination			The full path to the destination.
	 * @param	bool[optional] $overwrite	If the destination already exists, should we overwrite?
	 * @param	bool[optional] $strict		If strict is true, exceptions will be thrown when an error occures.
	 * @param 	int[optional] $chmod		Mode that will be applied on the file/directory.
	 */
	public static function copy($source, $destination, $overwrite = true, $strict = true, $chmod = 0777)
	{
		// redefine vars
		$source = (string) $source;
		$destination = (string) $destination;
		$return = true;

		// validation
		if($strict)
		{
			if(!@file_exists($source)) throw new Exception('The given path (' . $source . ') doesn\'t exist.');
			if(!$overwrite && @file_exists($destination)) throw new Exception('The given path (' . $destination . ') already exists.');
		}

		// is a directory
		if(is_dir($source))
		{
			// create dir
			if(!self::exists($destination))
			{
				// create dir
				$return = self::create($destination, $chmod);

				// check
				if(!$return)
				{
					if($strict) throw new SAException('The directory-structure couldn\'t be created.');
					return false;
				}
			}

			// get content
			$contentList = (array) self::getList($source, true);

			// loop content
			foreach($contentList as $item)
			{
				// copy dir (recursive)
				if(is_dir($source . '/' . $item)) self::copy($source . '/' . $item, $destination . '/' . $item);
				else
				{
					// delete the file if needed
					if($overwrite && SpoonFile::exists($destination . '/' . $item)) file::delete($destination . '/' . $item);

					// copy file
					if(!SpoonFile::exists($destination . '/' . $item))
					{
						// copy file
						$return = @copy($source . '/' . $item, $destination . '/' . $item);

						// check
						if(!$return)
						{
							if($strict) throw new SAException('The directory/file (' . $source . '/' . $item . ') couldn\'t be copied.');
							return false;
						}

						// chmod
						@chmod($destination . '/' . $item, $chmod);
					}
				}
			}
		}

		// not a directory
		else
		{
			// delete the file if needed
			if($overwrite && file::exists($destination)) file::delete($destination);

			// copy file
			if(!file::exists($destination))
			{
				// copy file
				$return = @copy($source, $destination);

				// check
				if(!$return)
				{
					if($strict) throw new SAException('The directory/file (' . $source . ') couldn\'t be copied.');
					return false;
				}

				// chmod
				@chmod($destination, $chmod);
			}
		}

		// return
		return true;
	}


	/**
	 * Creates a folder with the given chmod settings.
	 *
	 * @return	bool						True if the directory was created, false if not.
	 * @param	string $directory			The name for the directory.
	 * @param	string[optional] $chmod		Mode that will be applied on the directory.
	 
	public static function create($directory, $chmod = 0777)
	{
		return (!self::exists($directory)) ? @mkdir((string) $directory, $chmod, true) : true;
	}
    
 
	 * Creates a directory on the filesystem and returns an object representing it
	 * 
	 * The directory creation is done recursively, so if any of the parent
	 * directories do not exist, they will be created.
	 * 
	 * This operation will be reverted by a filesystem transaction being rolled back.
	 * 
	 * @throws SAException  When no directory was specified, or the directory already exists
	 * 
	 * @param  string  $directory  The path to the new directory
	 * @param  numeric $mode       The mode (permissions) to use when creating the directory. This should be an octal number (requires a leading zero). This has no effect on the Windows platform.
	 * @return fDirectory
	 */
	static public function create($directory, $mode=0777)
	{
		if (empty($directory)) {
			throw new SAException('No directory name was specified');
		}
		
		if (file_exists($directory)) {
			throw new SAException(
				'The directory specified, %s, already exists',
				$directory
			);
		}
		
		$parent_directory = filesystem::getPathInfo($directory, 'dirname');
		if (!file_exists($parent_directory)) {
			self::create($parent_directory, $mode);
		}
		
		if (!is_writable($parent_directory)) {
			throw new SAException(
				'The directory specified, %s, is inside of a directory that is not writable',
				$directory
			);
		}
		
		mkdir($directory, $mode);
		
		$directory = new directory($directory);
		
		filesystem::recordCreate($directory);
		
		return $directory;
	}

	/**
	 * Deletes a directory and all of its subdirectories.
	 *
	 * @return	bool				True if the directory was deleted, false if not.
	 * @param	string $directory	Full path to the directory.
	 */
	public static function delete($directory)
	{
		// redfine directory
		$directory = (string) $directory;

		// directory exists
		if(self::exists($directory))
		{
			// get the list
			$list = self::getList($directory, true);

			// has subdirectories/files
			if(count($list) != 0)
			{
				// loop directories and execute function
				foreach((array) $list as $item)
				{
					// delete directory recursive
					if(is_dir($directory . '/' . $item)) self::delete($directory . '/' . $item);

					// delete file
					else file::delete($directory . '/' . $item);
				}
			}

			// has no content
			@rmdir($directory);
		}

		// directory doesn't exist
		else return false;
	}


	/**
	 * Checks if this directory exists.
	 *
	 * @return	bool				True if the directory exists, false if not.
	 * @param	string $directory	Full path of the directory to check.
	 */
	public static function exists($directory)
	{
		// redefine directory
		$directory = (string) $directory;

		// directory exists
		if(file_exists($directory) && is_dir($directory)) return true;

		// doesn't exist
		return false;
	}


	/**
	 * Returns a list of directories within a directory.
	 *
	 * @return	array								An array containing all directories (and files if $showFiles is true).
	 * @param	string $path						Path of the directory.
	 * @param	bool[optional] $showFiles			Should files be included in the list.
	 * @param	array[optional] $excluded			An array containing directories/files to exclude.
	 * @param 	string[optional] $includeRegexp		An regular expression that represents the directories/files to include in the list. Other directories will be excluded.
	 */
	public static function getList($path, $showFiles = false, array $excluded = null, $includeRegexp = null)
	{
		// redefine arguments
		$path = (string) $path;
		$showFiles = (bool) $showFiles;

		// validate regex
		if($includeRegexp !== null)
		{
			// redefine
			$includeRegexp = (string) $includeRegexp;

			// validate
			if(!validate::regexp($includeRegexp)) throw new Exception('Invalid regular expression (' . $includeRegexp . ').');
		}

		// define file list
		$directories = array();

		// directory exists
		if(self::exists($path))
		{
			// attempt to open directory
			$directory = @opendir($path);

			// do your thing if directory-handle isn't false
			if($directory !== false)
			{
				// start reading
				while((($file = readdir($directory)) !== false))
				{
					// no '.' and '..' and it's a file
					if(($file != '.') && ($file != '..'))
					{
						// directory
						if(is_dir($path . '/' . $file))
						{
							// exclude certain files
							if(count($excluded) != 0)
							{
								if(!in_array($file, $excluded))
								{
									if($includeRegexp !== null)
									{
										// init var
										$matches = array();

										// is this a match?
										if(preg_match($includeRegexp, $file, $matches) != 0) $directories[] = $file;
									}

									// add to list
									else $directories[] = $file;
								}
							}

							// no excludes defined
							else
							{
								if($includeRegexp !== null)
								{
									// init var
									$matches = array();

									// is this a match?
									if(preg_match($includeRegexp, $file, $matches) != 0) $directories[] = $file;
								}

								// add to list
								else $directories[] = $file;
							}
						}

						// file
						else
						{
							// show files
							if($showFiles)
							{
								// exclude certain files
								if(count($excluded) != 0)
								{
									if(!in_array($file, $excluded))
									{
										$directories[] = $file;
									}
								}

								// add file
								else $directories[] = $file;
							}
						}
					}
				}
			}

			// close directory
			@closedir($directory);
		}

		// cough up directory listing
		natsort($directories);

		return $directories;
	}


	/**
	 * Retrieve the size of a directory in megabytes.
	 *
	 * @return	int									The size in MB.
	 * @param	string $path						The path of the directory.
	 * @param	bool[optional] $subdirectories		Should the subfolders be included in the calculation.
	 */
	public static function getSize($path, $subdirectories = true)
	{
		// internal size
		$size = 0;

		// redefine arguments
		$path = (string) $path;
		$subdirectories = (bool) $subdirectories;

		// directory doesn't exists
		if(!self::exists($path)) return false;

		// directory exists
		else
		{
			// fetch list
			$list = (array) self::getList($path, true);

			// loop list
			foreach($list as $item)
			{
				// get directory size if subdirectories should be included
				if(is_dir($path . '/' . $item) && $subdirectories) $size += self::getSize($path . '/' . $item, $subdirectories);

				// add filesize
				else $size += filesize($path . '/' . $item);
			}
		}

		return $size;
	}


	/**
	 * Check if a directory is writable.
	 * The default is_writable function has problems due to Windows ACLs "bug"
	 *
	 * @return	bool
	 * @param	string $path	The path to check.
     */
	public static function is_Writable($path)
	{
		// redefine argument
		$path = (string) $path;

		// create temporary file
		$file = tempnam($path, 'isWritable');

		// file has been created
		if($file !== false)
		{
			// remove temporary file
			file::delete($file);

			// file could not be created = writable
			return true;
		}

		// file could not be created = not writable
		return false;
	}
	/** 
	public function isWritable()
	{
		// redefine argument
		$path = $this->directory;

		// create temporary file
		$file = tempnam($path, 'isWritable');

		// file has been created
		if($file !== false)
		{
			// remove temporary file
			file::delete($file);

			// file could not be created = writable
			return true;
		}

		// file could not be created = not writable
		return false;
	}
     */   
    /**
    * Check to see if the current directory is writable
    *
    * @return boolean  If the directory is writable
    
    public function isWritable()
    {
        $this->tossIfDeleted();
       
        return is_writable($this->directory);
    }
    */
	/**
	 * Move/rename a directory/file.
	 *
	 * @return	bool						True if the directory was moved or renamed, false if not.
	 * @param	string $source				Path of the source directory.
	 * @param	string $destination			Path of the destination.
	 * @param 	bool[optional] $overwrite	Should an existing directory be overwritten?
	 * @param	int[optional] $chmod		Mode that should be applied on the directory.
	 */
	public static function move($source, $destination, $overwrite = true, $chmod = 0777)
	{
		// redefine vars
		$source = (string) $source;
		$destination = (string) $destination;
		$overwrite = (bool) $overwrite;

		// validation
		if(!file_exists($source)) throw new Exception('The given path (' . $source . ') doesn\'t exist.');
		if(!$overwrite && file_exists($destination)) throw new Exception('The given destination (' . $destination . ') already exists.');

		// create missing directories
		if(!file_exists(dirname($destination))) self::create(dirname($destination));

		// delete file
		if($overwrite && file_exists($destination)) self::delete($destination);

		// rename
		$return = @rename($source, $destination);
		@chmod($destination, $chmod);

		// return
		return $return;
	}
    
	
	/**
	 * Gets the directory's current path
	 * 
	 * If the web path is requested, uses translations set with
	 * filesystem::addWebPathTranslation()
	 * 
	 * @param  boolean $translate_to_web_path  If the path should be the web path
	 * @return string  The path for the directory
	 */
	public function getPath($translate_to_web_path=FALSE)
	{
		$this->tossIfDeleted();
		
		if ($translate_to_web_path) {
			return filesystem::translateToWebPath($this->directory);
		}
		return $this->directory;
	}    
	/**
	 * Throws an exception if the directory has been deleted
	 * 
	 * @return void
	 */
	protected function tossIfDeleted()
	{
		if ($this->deleted) {
			throw new SAException(
				"The action requested can not be performed because the directory has been deleted\n\nBacktrace for fDirectory::delete() call:\n%s",
				core::backtrace(0, $this->deleted)
			);
		}
	}    
}