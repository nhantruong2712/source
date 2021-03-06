<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA FileSystem Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */
 
class filesystem
{
 
	/**
	 * Stores the operations to perform when a commit occurs
	 * 
	 * @var array
	 */
	static private $commit_operations = NULL;
	
	/**
	 * Maps deletion backtraces to all instances of a file or directory, providing consistency
	 * 
	 * @var array
	 */
	static private $deleted_map = array();
	
	/**
	 * Stores file and directory names by reference, allowing all object instances to be updated at once
	 * 
	 * @var array
	 */
	static private $filename_map = array();
	
	/**
	 * Stores the operations to perform if a rollback occurs
	 * 
	 * @var array
	 */
	static private $rollback_operations = NULL;
	
	/**
	 * Stores a list of search => replace strings for web path translations
	 * 
	 * @var array
	 */
	static private $web_path_translations = array();
	
	
	/**
	 * Adds a directory to the web path translation list
	 * 
	 * The web path conversion list is a list of directory paths that will be
	 * converted (from the beginning of filesystem paths) when preparing a path
	 * for output into HTML.
	 * 
	 * By default the `$_SERVER['DOCUMENT_ROOT']` will be converted to a blank
	 * string, in essence stripping it from filesystem paths.
	 * 
	 * @param  string $search_path   The path to look for
	 * @param  string $replace_path  The path to replace with
	 * @return void
	 */
	public static function addWebPathTranslation($search_path, $replace_path)
	{
		// Ensure we have the correct kind of slash for the OS being used
		$search_path  = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $search_path);
		$replace_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $replace_path);
		self::$web_path_translations[$search_path] = $replace_path;
	}
	
	
	/**
	 * Starts a filesystem pseudo-transaction, should only be called when no transaction is in progress.
	 * 
	 * Flourish filesystem transactions are NOT full ACID-compliant
	 * transactions, but rather more of an filesystem undo buffer which can
	 * return the filesystem to the state when ::begin() was called. If your PHP
	 * script dies in the middle of an operation this functionality will do
	 * nothing for you and all operations will be retained, except for deletes
	 * which only occur once the transaction is committed.
	 * 
	 * @return void
	 */
	public static function begin()
	{
		if (self::$commit_operations !== NULL) {
			throw new SAException(
				'There is already a filesystem transaction in progress'
			);
		}
		self::$commit_operations   = array();
		self::$rollback_operations = array();
	}
	
	
	/**
	 * Commits a filesystem transaction, should only be called when a transaction is in progress
	 * 
	 * @return void
	 */
	public static function commit()
	{
		if (!self::isInsideTransaction()) {
			throw new SAException(
				'There is no filesystem transaction in progress to commit'
			);
		}
		
		$commit_operations = self::$commit_operations;
		
		self::$commit_operations   = NULL;
		self::$rollback_operations = NULL;
		
		$commit_operations = array_reverse($commit_operations);
		
		foreach ($commit_operations as $operation) {
			// Commit operations only include deletes, however it could be a filename or object
			if (isset($operation['filename'])) {
				unlink($operation['filename']);
			} else {
				$operation['object']->delete();
			}
		}
	}
	
	
	/**
	 * Takes a file size including a unit of measure (i.e. kb, GB, M) and converts it to bytes
	 * 
	 * Sizes are interpreted using base 2, not base 10. Sizes above 2GB may not
	 * be accurately represented on 32 bit operating systems.
	 * 
	 * @param  string $size  The size to convert to bytes
	 * @return integer  The number of bytes represented by the size
	 */
	public static function convertToBytes($size)
	{
		if (!preg_match('#^(\d+(?:\.\d+)?)\s*(k|m|g|t)?(ilo|ega|era|iga)?( )?b?(yte(s)?)?$#D', strtolower(trim($size)), $matches)) {
			throw new SAException(
				'The size specified, %s, does not appears to be a valid size',
				$size
			);
		}
		
		if (empty($matches[2])) {
			$matches[2] = 'b';
		}
		
		$size_map = array('b' => 1,
						  'k' => 1024,
						  'm' => 1048576,
						  'g' => 1073741824,
						  't' => 1099511627776);
		return round($matches[1] * $size_map[$matches[2]]);
	}
	
	
	/**
	 * Takes a filesystem path and creates either an directory, file or image object from it
	 * 
	 * @throws SAException  When no path was specified or the path specified does not exist
	 * 
	 * @param  string $path  The path to the filesystem object
	 * @return directory|file|image
	 */
	public static function createObject($path)
	{
		if (empty($path)) {
			throw new SAException(
				'No path was specified'
			);
		}
		
		if (!is_readable($path)) {
			throw new SAException(
				'The path specified, %s, does not exist or is not readable',
				$path
			);
		}
		
		if (is_dir($path)) {
			return new dir($path, TRUE);
		}
		
		if (image::isImageCompatible($path)) {
			return new image($path, TRUE);
		}
		
		//return new file($path, TRUE);
        return new file($path);
	}
	
	
	/**
	 * Takes the size of a file in bytes and returns a friendly size in B/K/M/G/T
	 * 
	 * @param  integer $bytes           The size of the file in bytes
	 * @param  integer $decimal_places  The number of decimal places to display
	 * @return string
	 */
	public static function formatFilesize($bytes, $decimal_places=1)
	{
		if ($bytes < 0) {
			$bytes = 0;
		}
		$suffixes  = array('B', 'K', 'M', 'G', 'T');
		$sizes     = array(1, 1024, 1048576, 1073741824, 1099511627776);
		$suffix    = (!$bytes) ? 0 : floor(log($bytes)/6.9314718);
		return number_format($bytes/$sizes[$suffix], ($suffix == 0) ? 0 : $decimal_places) . ' ' . $suffixes[$suffix];
	}
	
	
	/**
	 * Returns info about a path including dirname, basename, extension and filename
	 * 
	 * @param  string $path     The file/directory path to retrieve information about
	 * @param  string $element  The piece of information to return: `'dirname'`, `'basename'`, `'extension'`, or `'filename'`
	 * @return array  The file's dirname, basename, extension and filename
	 */
	public static function getPathInfo($path, $element=NULL)
	{
		$valid_elements = array('dirname', 'basename', 'extension', 'filename');
		if ($element !== NULL && !in_array($element, $valid_elements)) {
			throw new SAException(
				'The element specified, %1$s, is invalid. Must be one of: %2$s.',
				$element,
				join(', ', $valid_elements)
			);
		}
		
		$path_info = pathinfo($path);
		
		if (!isset($path_info['extension'])) {
			$path_info['extension'] = NULL;
		}
		
		if (!isset($path_info['filename'])) {
			$path_info['filename'] = preg_replace('#\.' . preg_quote($path_info['extension'], '#') . '$#D', '', $path_info['basename']);
		}
		$path_info['dirname'] .= DIRECTORY_SEPARATOR;
		
		if ($element) {
			return $path_info[$element];
		}
		
		return $path_info;
	}
	
	
	/**
	 * Hooks a file/directory into the deleted backtrace map entry for that filename
	 * 
	 * Since the value is returned by reference, all objects that represent
	 * this file/directory always see the same backtrace.
	 * 
	 * @internal
	 * 
	 * @param  string $file  The name of the file or directory
	 * @return mixed  Will return `NULL` if no match, or the backtrace array if a match occurs
	 */
	public static function &hookDeletedMap($file)
	{
		if (!isset(self::$deleted_map[$file])) {
			self::$deleted_map[$file] = NULL;
		}
		return self::$deleted_map[$file];
	}
	
	
	/**
	 * Hooks a file/directory name to the filename map
	 * 
	 * Since the value is returned by reference, all objects that represent
	 * this file/directory will always be update on a rename.
	 * 
	 * @internal
	 * 
	 * @param  string $file  The name of the file or directory
	 * @return mixed  Will return `NULL` if no match, or the exception object if a match occurs
	 */
	public static function &hookFilenameMap($file)
	{
		if (!isset(self::$filename_map[$file])) {
			self::$filename_map[$file] = $file;
		}
		return self::$filename_map[$file];
	}
	
	
	/**
	 * Indicates if a transaction is in progress
	 * 
	 * @return void
	 */
	public static function isInsideTransaction()
	{
		return is_array(self::$commit_operations);
	}
	
	
	/**
	 * Changes a filename to be safe for URLs by making it all lower case and changing everything but letters, numers, - and . to _
	 * 
	 * @param  string $filename  The filename to clean up
	 * @return string  The cleaned up filename
	 */
	public static function makeURLSafe($filename)
	{
		$filename = strtolower(trim($filename));
		$filename = str_replace("'", '', $filename);
		return preg_replace('#[^a-z0-9\-\.]+#', '_', $filename);	
	}
	
	
	/**
	 * Returns a unique name for a file
	 * 
	 * @param  string $file           The filename to check
	 * @param  string $new_extension  The new extension for the filename, should not include `.`
	 * @return string  The unique file name
	 */
	public static function makeUniqueName($file, $new_extension=NULL)
	{
		$info = self::getPathInfo($file);
		
		// Change the file extension
		if ($new_extension !== NULL) {
			$new_extension = ($new_extension) ? '.' . $new_extension : $new_extension;
			$file = $info['dirname'] . $info['filename'] . $new_extension;
			$info = self::getPathInfo($file);
		}
		
		// If there is an extension, be sure to add . before it
		$extension = (!empty($info['extension'])) ? '.' . $info['extension'] : '';
		
		// Remove _copy# from the filename to start
		$file = preg_replace('#_copy(\d+)' . preg_quote($extension, '#') . '$#D', $extension, $file);
		
		// Look for a unique name by adding _copy# to the end of the file
		while (file_exists($file)) {
			$info = self::getPathInfo($file);
			if (preg_match('#_copy(\d+)' . preg_quote($extension, '#') . '$#D', $file, $match)) {
				$file = preg_replace('#_copy(\d+)' . preg_quote($extension, '#') . '$#D', '_copy' . ($match[1]+1) . $extension, $file);
			} else {
				$file = $info['dirname'] . $info['filename'] . '_copy1' . $extension;
			}
		}
		
		return $file;
	}
	
	
	/**
	 * Updates the deleted backtrace for a file or directory
	 * 
	 * @internal
	 * 
	 * @param  string $file		  A file or directory name, directories should end in `/` or `\`
	 * @param  array  $backtrace  The backtrace for this file/directory
	 * @return void
	 */
	public static function updateDeletedMap($file, $backtrace)
	{
		self::$deleted_map[$file] = $backtrace;
	}
	
	
	/**
	 * Updates the filename map, causing all objects representing a file/directory to be updated
	 * 
	 * @internal
	 * 
	 * @param  string $existing_filename  The existing filename
	 * @param  string $new_filename       The new filename
	 * @return void
	 */
	public static function updateFilenameMap($existing_filename, $new_filename)
	{
		if ($existing_filename == $new_filename) {
			return;
		}
		
		self::$filename_map[$new_filename] =& self::$filename_map[$existing_filename];
		self::$deleted_map[$new_filename]  =& self::$deleted_map[$existing_filename];
		
		unset(self::$filename_map[$existing_filename]);
		unset(self::$deleted_map[$existing_filename]);
		
		self::$filename_map[$new_filename] = $new_filename;
	}
	
	
	/**
	 * Updates the filename map recursively, causing all objects representing a directory to be updated
	 * 
	 * Also updates all files and directories in the specified directory to the new paths.
	 * 
	 * @internal
	 * 
	 * @param  string $existing_dirname  The existing directory name
	 * @param  string $new_dirname       The new dirname
	 * @return void
	 */
	public static function updateFilenameMapForDirectory($existing_dirname, $new_dirname)
	{
		if ($existing_dirname == $new_dirname) {
			return;
		}
		
		// Handle the directory name
		self::$filename_map[$new_dirname] =& self::$filename_map[$existing_dirname];
		self::$deleted_map[$new_dirname]  =& self::$deleted_map[$existing_dirname];
		
		unset(self::$filename_map[$existing_dirname]);
		unset(self::$deleted_map[$existing_dirname]);
		
		self::$filename_map[$new_dirname] = $new_dirname;
		
		// Handle all of the directories and files inside this directory
		foreach (self::$filename_map as $filename => $ignore) {
			if (preg_match('#^' . preg_quote($existing_dirname, '#') . '#', $filename)) {
				$new_filename = preg_replace(
					'#^' . preg_quote($existing_dirname, '#') . '#',
					strtr($new_dirname, array('\\' => '\\\\', '$' => '\\$')),
					$filename
				);
				
				self::$filename_map[$new_filename] =& self::$filename_map[$filename];
				self::$deleted_map[$new_filename]  =& self::$deleted_map[$filename];
				
				unset(self::$filename_map[$filename]);
				unset(self::$deleted_map[$filename]);
				
				self::$filename_map[$new_filename] = $new_filename;
					
			}
		}
	}
	
	
	/**
	 * Stores what data has been added to a file so it can be removed if there is a rollback
	 * 
	 * @internal
	 * 
	 * @param  fFile  $file  The file that is being written to
	 * @param  string $data  The data being appended to the file
	 * @return void
	 */
	public static function recordAppend($file, $data)
	{
		self::$rollback_operations[] = array(
			'action'   => 'append',
			'filename' => $file->getPath(),
			'length'   => strlen($data)
		);
	}
	
	
	/**
	 * Keeps a record of created files so they can be deleted up in case of a rollback
	 * 
	 * @internal
	 * 
	 * @param  object $object  The new file or directory to get rid of on rollback
	 * @return void
	 */
	public static function recordCreate($object)
	{
		self::$rollback_operations[] = array(
			'action' => 'delete',
			'object' => $object
		);
	}
	
	
	/**
	 * Keeps track of file and directory names to delete when a transaction is committed
	 * 
	 * @internal
	 * 
	 * @param  fFile|fDirectory $object  The filesystem object to delete
	 * @return void
	 */
	public static function recordDelete($object)
	{
		self::$commit_operations[] = array(
			'action' => 'delete',
			'object' => $object
		);
	}
	
	
	/**
	 * Keeps a record of duplicated files so they can be cleaned up in case of a rollback
	 * 
	 * @internal
	 * 
	 * @param  fFile $file  The duplicate file to get rid of on rollback
	 * @return void
	 */
	public static function recordDuplicate($file)
	{
		self::$rollback_operations[] = array(
			'action'   => 'delete',
			'filename' => $file->getPath()
		);
	}
	
	
	/**
	 * Keeps a temp file in place of the old filename so the file can be restored during a rollback
	 * 
	 * @internal
	 * 
	 * @param  string $old_name  The old file or directory name
	 * @param  string $new_name  The new file or directory name
	 * @return void
	 */
	public static function recordRename($old_name, $new_name)
	{
		self::$rollback_operations[] = array(
			'action'   => 'rename',
			'old_name' => $old_name,
			'new_name' => $new_name
		);
		
		// Create the file with no content to prevent overwriting by another process
		file_put_contents($old_name, '');
		
		self::$commit_operations[] = array(
			'action'   => 'delete',
			'filename' => $old_name
		);
	}
	
	
	/**
	 * Keeps backup copies of files so they can be restored if there is a rollback
	 * 
	 * @internal
	 * 
	 * @param  fFile $file  The file that is being written to
	 * @return void
	 */
	public static function recordWrite($file)
	{
		self::$rollback_operations[] = array(
			'action'   => 'write',
			'filename' => $file->getPath(),
			'old_data' => file_get_contents($file->getPath())
		);
	}
	
	
	/**
	 * Resets the configuration of the class
	 * 
	 * @internal
	 * 
	 * @return void
	 */
	public static function reset()
	{
		self::rollback();
		self::$commit_operations     = NULL;
		self::$deleted_map           = array();
		self::$filename_map          = array();
		self::$rollback_operations   = NULL;
		self::$web_path_translations = array();	
	}
	
	
	/**
	 * Rolls back a filesystem transaction, it is safe to rollback when no transaction is in progress
	 * 
	 * @return void
	 */
	public static function rollback()
	{
		if (self::$rollback_operations === NULL) {
			return;	
		}
		
		self::$rollback_operations = array_reverse(self::$rollback_operations);
		
		$clear_cache = FALSE;
		foreach (self::$rollback_operations as $operation) {
			switch($operation['action']) {
				
				case 'append':
					$current_length = filesize($operation['filename']);
					$handle         = fopen($operation['filename'], 'r+');
					ftruncate($handle, $current_length - $operation['length']);
					fclose($handle);
					$clear_cache = TRUE;
					break;
				
				case 'delete':
					self::updateDeletedMap(
						$operation['filename'],
						debug_backtrace()
					);
					unlink($operation['filename']);
					fFilesystem::updateFilenameMap($operation['filename'], '*DELETED at ' . time() . ' with token ' . uniqid('', TRUE) . '* ' . $operation['filename']);
					break;
					
				case 'write':
					file_put_contents($operation['filename'], $operation['old_data']);
					$clear_cache = TRUE;
					break;
					
				case 'rename':
					fFilesystem::updateFilenameMap($operation['new_name'], $operation['old_name']);
					rename($operation['new_name'], $operation['old_name']);
					break;
					
			}
		}
		
		// All files to be deleted should have their backtraces erased
		foreach (self::$commit_operations as $operation) {
			if (isset($operation['object'])) {
				self::updateDeletedMap($operation['object']->getPath(), NULL);
				filesystem::updateFilenameMap($operation['object']->getPath(), preg_replace('#*DELETED at \d+ with token [\w.]+* #', '', $operation['filename']));
			}
		}
		
		self::$commit_operations   = NULL;
		self::$rollback_operations = NULL;
		
		if ($clear_cache) {
			clearstatcache();
		}
	}
	
	
	/**
	 * Takes a filesystem path and translates it to a web path using the rules added
	 * 
	 * @param  string $path  The path to translate
	 * @return string  The filesystem path translated to a web path
	 */
	public static function translateToWebPath($path)
	{
		$translations = array(realpath($_SERVER['DOCUMENT_ROOT']) => '') + self::$web_path_translations;
		
		foreach ($translations as $search => $replace) {
			$path = preg_replace(
				'#^' . preg_quote($search, '#') . '#',
				strtr($replace, array('\\' => '\\\\', '$' => '\\$')),
				$path
			);
		}
		
		return str_replace('\\', '/', $path);
	}
 
}
 