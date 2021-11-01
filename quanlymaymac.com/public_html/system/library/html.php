<?php if(!defined('HTH')){die('External Access to File Denied');}

/**
 * SA HTML Class
 *
 * @Author          Hoang Trong Hoi
 * @Copyright       2011
 */
class html{
 
    /**
     * html tags used by this helper.
     *
     * @var array
     */
	protected static $_tags = array(
		'meta' => '<meta%s/>',
		'metalink' => '<link href="%s"%s />',
		'link' => '<a href="%s"%s>%s</a>',
		'mailto' => '<a href="mailto:%s" %s>%s</a>',
		'form' => '<form action="%s"%s>',
		'formend' => '</form>',
		'input' => '<input name="%s"%s />',
		'textarea' => '<textarea name="%s"%s>%s</textarea>',
		'hidden' => '<input type="hidden" name="%s"%s />',
		'checkbox' => '<input type="checkbox" name="%s" %s/>',
		'checkboxmultiple' => '<input type="checkbox" name="%s[]"%s />',
		'radio' => '<input type="radio" name="%s" id="%s" %s />%s',
		'selectstart' => '<select name="%s"%s>',
		'selectmultiplestart' => '<select name="%s[]"%s>',
		'selectempty' => '<option value=""%s>&nbsp;</option>',
		'selectoption' => '<option value="%s"%s>%s</option>',
		'selectend' => '</select>',
		'optiongroup' => '<optgroup label="%s"%s>',
		'optiongroupend' => '</optgroup>',
		'checkboxmultiplestart' => '',
		'checkboxmultipleend' => '',
		'password' => '<input type="password" name="%s" %s />',
		'file' => '<input type="file" name="%s" %s/>',
		'file_no_model' => '<input type="file" name="%s" %s />',
		'submit' => '<input %s/>',
		'submitimage' => '<input type="image" src="%s" %s />',
		'button' => '<button type="%s"%s>%s</button>',
		'image' => '<img src="%s" %s/>',
		'tableheader' => '<th%s>%s</th>',
		'tableheaderrow' => '<tr%s>%s</tr>',
		'tablecell' => '<td%s>%s</td>',
		'tablerow' => '<tr%s>%s</tr>',
		'block' => '<div%s>%s</div>',
		'blockstart' => '<div%s>',
		'blockend' => '</div>',
		'tag' => '<%s%s>%s</%s>',
		'tagstart' => '<%s%s>',
		'tagend' => '</%s>',
		'para' => '<p%s>%s</p>',
		'parastart' => '<p%s>',
		'label' => '<label for="%s"%s>%s</label>',
		'fieldset' => '<fieldset%s>%s</fieldset>',
		'fieldsetstart' => '<fieldset><legend>%s</legend>',
		'fieldsetend' => '</fieldset>',
		'legend' => '<legend>%s</legend>',
		'css' => '<link rel="%s" type="text/css" href="%s" %s/>',
		'style' => '<style type="text/css"%s>%s</style>',
		'charset' => '<meta http-equiv="Content-Type" content="text/html; charset=%s" />',
		'ul' => '<ul%s>%s</ul>',
		'ol' => '<ol%s>%s</ol>',
		'li' => '<li%s>%s</li>',
		'error' => '<div%s>%s</div>',
		'javascriptblock' => '<script type="text/javascript"%s>%s</script>',
		'javascriptstart' => '<script type="text/javascript">',
		'javascriptlink' => '<script type="text/javascript" src="%s"%s></script>',
		'javascriptend' => '</script>'
	);

    /**
     * Minimized attributes
     *
     * @var array
     */
	protected static $_minimizedAttributes = array(
		'compact', 'checked', 'declare', 'readonly', 'disabled', 'selected',
		'defer', 'ismap', 'nohref', 'noshade', 'nowrap', 'multiple', 'noresize'
	);

    /**
     * Format to attribute
     *
     * @var string
     */
	protected static $_attributeFormat = '%s="%s"';

    /**
     * Format to attribute
     *
     * @var string
     */
	protected static $_minimizedAttributeFormat = '%s="%s"';

    /**
     * Breadcrumbs.
     *
     * @var array
     */
	protected static $_crumbs = array();

    /**
     * Names of script files that have been included once
     *
     * @var array
     */
	protected static $_includedScripts = array();
    /**
     * Options for the currently opened script block buffer if any.
     *
     * @var array
     */
	protected static $_scriptBlockOptions = array();
    /**
     * Document type definitions
     *
     * @var array
     */
	protected static $_docTypes = array(
		'html4-strict'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
		'html4-trans'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
		'html4-frame'  => '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
		'html5' => '<!DOCTYPE html>',
		'xhtml-strict' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'xhtml-trans' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'xhtml-frame' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'xhtml11' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">'
	);
    /**
     * An array of fieldnames that have been excluded from
     *
     * @var array
     */
	protected static $_unlockedFields = array(); 

    /**
     * Adds a link to the breadcrumbs array.
     *
     * @param string $name Text for link
     * @param string $link URL for link (if empty it won't be a link)
     * @param mixed $options Link attributes e.g. array('id'=>'selected')
     * @return void
     * @see html::link() for details on $options that can be used.
     */
	public static function addCrumb($name, $link = null, $options = null) {
		self::$_crumbs[] = array($name, $link, $options);
	}

    /**
     * Returns a doctype string.
     *
     * Possible doctypes:
     *
     *  - html4-strict:  HTML4 Strict.
     *  - html4-trans:  HTML4 Transitional.
     *  - html4-frame:  HTML4 Frameset.
     *  - html5: HTML5.
     *  - xhtml-strict: XHTML1 Strict.
     *  - xhtml-trans: XHTML1 Transitional.
     *  - xhtml-frame: XHTML1 Frameset.
     *  - xhtml11: XHTML1.1.
     *
     * @param string $type Doctype to use.
     * @return string Doctype string
     */
	public static function docType($type = 'xhtml-strict') {
		if (isset(self::$_docTypes[$type])) {
			return self::$_docTypes[$type];
		}
		return null;
	}

    /**
     * Creates a link to an external resource and handles basic meta tags
     *
     * ### Options
     *
     * - `inline` Whether or not the link element should be output inline, or in scripts_for_layout.
     *
     * @param string $type The title of the external resource
     * @param mixed $url The address of the external resource or string for content attribute
     * @param array $options Other attributes for the generated tag. If the type attribute is html,
     *    rss, atom, or icon, the mime-type is returned.
     * @return string A completed `<link />` element.
     */
	public static function meta($type, $url = null, $options = array()) {
		$inline = isset($options['inline']) ? $options['inline'] : true;
		unset($options['inline']);

		if (!is_array($type)) {
			$types = array(
				'rss'	=> array('type' => 'application/rss+xml', 'rel' => 'alternate', 'title' => $type, 'link' => $url),
				'atom'	=> array('type' => 'application/atom+xml', 'title' => $type, 'link' => $url),
				'icon'	=> array('type' => 'image/x-icon', 'rel' => 'icon', 'link' => $url),
				'keywords' => array('name' => 'keywords', 'content' => $url),
				'description' => array('name' => 'description', 'content' => $url),
			);

			if ($type === 'icon' && $url === null) {
				$types['icon']['link'] = self::webroot('favicon.ico');
			}

			if (isset($types[$type])) {
				$type = $types[$type];
			} elseif (!isset($options['type']) && $url !== null) {
				if (is_array($url) && isset($url['ext'])) {
					$type = $types[$url['ext']];
				} else {
					$type = $types['rss'];
				}
			} elseif (isset($options['type']) && isset($types[$options['type']])) {
				$type = $types[$options['type']];
				unset($options['type']);
			} else {
				$type = array();
			}
		} elseif ($url !== null) {
			$inline = $url;
		}
		$options = array_merge($type, $options);
		$out = null;

		if (isset($options['link'])) {
			if (isset($options['rel']) && $options['rel'] === 'icon') {
				$out = sprintf(self::$_tags['metalink'], $options['link'], self::_parseAttributes($options, array('link'), ' ', ' '));
				$options['rel'] = 'shortcut icon';
			} else {
				$options['link'] = self::url($options['link'], true);
			}
			$out .= sprintf(self::$_tags['metalink'], $options['link'], self::_parseAttributes($options, array('link'), ' ', ' '));
		} else {
			$out = sprintf(self::$_tags['meta'], self::_parseAttributes($options, array('type'), ' ', ' '));
		}

		if (!$inline) {
			return $out;
		} else {
			echo $out;
		}
	}

    /**
     * Returns a charset META-tag.
     *
     * @param string $charset The character set to be used in the meta tag. If empty,
     *  The App.encoding value will be used. Example: "utf-8".
     * @return string A meta tag containing the specified character set.
     */
	public static function charset($charset = null) {
		if (empty($charset)) {
			$charset = strtolower(config::get('charset'));
		}
		return sprintf(self::$_tags['charset'], (!empty($charset) ? $charset : 'utf-8'));
	}

    /**
     * Creates an HTML link.
     *
     * If $url starts with "http://" this is treated as an external link. Else,
     * it is treated as a path to controller/action and parsed with the
     * route::url() method.
     *
     * If the $url is empty, $title is used instead.
     *
     * ### Options
     *
     * - `escape` Set to false to disable escaping of title and attributes.
     * - `confirm` JavaScript confirmation message.
     *
     * @param string $title The content to be wrapped by <a> tags.
     * @param mixed $url SA-relative URL or array of URL parameters, or external URL (starts with http://)
     * @param array $options Array of HTML attributes.
     * @param string $confirmMessage JavaScript confirmation message.
     * @return string An `<a />` element.
     */
	public static function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$escapeTitle = true;
		if ($url !== null) {
			$url = route::url($url);
		} else {
			$url = route::url($title);
			$title = $url;
			$escapeTitle = false;
		}

		if (isset($options['escape'])) {
			$escapeTitle = $options['escape'];
		}

		if ($escapeTitle === true) {
			$title = h($title);
		} elseif (is_string($escapeTitle)) {
			$title = htmlentities($title, ENT_QUOTES, $escapeTitle);
		}

		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}
		if ($confirmMessage) {
			$confirmMessage = str_replace("'", "\'", $confirmMessage);
			$confirmMessage = str_replace('"', '\"', $confirmMessage);
			$options['onclick'] = "return confirm('{$confirmMessage}');";
		} elseif (isset($options['default']) && $options['default'] == false) {
			if (isset($options['onclick'])) {
				$options['onclick'] .= ' event.returnValue = false; return false;';
			} else {
				$options['onclick'] = 'event.returnValue = false; return false;';
			}
			unset($options['default']);
		}
		return sprintf(self::$_tags['link'], $url, self::_parseAttributes($options), $title);
	}

    /**
     * Creates a link element for CSS stylesheets.
     *
     * ### Usage
     *
     * Include one CSS file:
     *
     * `echo html::css('styles.css');`
     *
     * Include multiple CSS files:
     *
     * `echo html::->css(array('one.css', 'two.css'));`
     *
     * Add the stylesheet to the `$scripts_for_layout` layout var:
     *
     * `html::css('styles.css', null, array('inline' => false));`
     *
     * ### Options
     *
     * - `inline` If set to false, the generated tag appears in the head tag of the layout. Defaults to true
     *
     * @param mixed $path The name of a CSS style sheet or an array containing names of
     *   CSS stylesheets. If `$path` is prefixed with '/', the path will be relative to the webroot
     *   of your application. Otherwise, the path will be relative to your CSS path, usually webroot/css.
     * @param string $rel Rel attribute. Defaults to "stylesheet". If equal to 'import' the stylesheet will be imported.
     * @param array $options Array of HTML attributes.
     * @return string CSS <link /> or <style /> tag, depending on the type of link.
     */
	public static function css($path, $rel = null, $options = array()) {
		$options += array('inline' => true);
		if (is_array($path)) {
			$out = '';
			foreach ($path as $i) {
				$out .= "\n\t" . self::css($i, $rel, $options);
			}
			if ($options['inline'])  {
				return $out . "\n";
			}
			return;
		}

		if (strpos($path, '//') !== false) {
			$url = $path;
		} else {
			if ($path[0] !== '/') {
				$path = CSS_URL . $path;
			}

			if (strpos($path, '?') === false) {
				if (substr($path, -4) !== '.css') {
					$path .= '.css';
				}
			}
			$url = self::webroot($path);
 
		}

		if ($rel == 'import') {
			$out = sprintf(self::$_tags['style'], self::_parseAttributes($options, array('inline'), '', ' '), '@import url(' . $url . ');');
		} else {
			if ($rel == null) {
				$rel = 'stylesheet';
			}
			$out = sprintf(self::$_tags['css'], $rel, $url, self::_parseAttributes($options, array('inline'), '', ' '));
		}

		if (!$options['inline']) {
			return $out;
		} else {
			echo $out;
		}
	}

    /**
     * Returns one or many `<script>` tags depending on the number of scripts given.
     *
     * If the filename is prefixed with "/", the path will be relative to the base path of your
     * application.  Otherwise, the path will be relative to your JavaScript path, usually webroot/js.
     *
     *
     * ### Usage
     *
     * Include one script file:
     *
     * `echo html::script('styles.js');`
     *
     * Include multiple script files:
     *
     * `echo html::script(array('one.js', 'two.js'));`
     *
     * Add the script file to the `$scripts_for_layout` layout var:
     *
     * `html::script('styles.js', null, array('inline' => false));`
     *
     * ### Options
     *
     * - `inline` - Whether script should be output inline or into scripts_for_layout.
     * - `once` - Whether or not the script should be checked for uniqueness. If true scripts will only be
     *   included once, use false to allow the same script to be included more than once per request.
     *
     * @param mixed $url String or array of javascript files to include
     * @param mixed $options Array of options, and html attributes see above. If boolean sets $options['inline'] = value
     * @return mixed String of `<script />` tags or null if $inline is false or if $once is true and the file has been
     *   included before.
     */
	public static function script($url, $options = array()) {
		if (is_bool($options)) {
			list($inline, $options) = array($options, array());
			$options['inline'] = $inline;
		}
		$options = array_merge(array('inline' => true, 'once' => true), $options);
		if (is_array($url)) {
			$out = '';
			foreach ($url as $i) {
				$out .= "\n\t" . self::script($i, $options);
			}
			if ($options['inline'])  {
				return $out . "\n";
			}
			return null;
		}
		if ($options['once'] && isset(self::$_includedScripts[$url])) {
			return null;
		}
		self::$_includedScripts[$url] = true;

		if (strpos($url, '//') === false) {
			if ($url[0] !== '/') {
				$url = JS_URL . $url;
			}
			if (strpos($url, '?') === false && substr($url, -3) !== '.js') {
				$url .= '.js';
			}
			$url = self::webroot($url);
 
		}
		$attributes = self::_parseAttributes($options, array('inline', 'once'), ' ');
		$out = sprintf(self::$_tags['javascriptlink'], $url, $attributes);

		if (!$options['inline']) {
			return $out;
		} else {
			echo $out;
		}
	}
 

    /**
     * Builds CSS style data from an array of CSS properties
     *
     * ### Usage:
     *
     * {{{
     * echo html::style(array('margin' => '10px', 'padding' => '10px'), true);
     *
     * // creates
     * 'margin:10px;padding:10px;'
     * }}}
     *
     * @param array $data Style data array, keys will be used as property names, values as property values.
     * @param boolean $oneline Whether or not the style block should be displayed on one line.
     * @return string CSS styling data
     */
	public static function style($data, $oneline = true) {
		if (!is_array($data)) {
			return $data;
		}
		$out = array();
		foreach ($data as $key=> $value) {
			$out[] = $key.':'.$value.';';
		}
		if ($oneline) {
			return join(' ', $out);
		}
		return implode("\n", $out);
	}

    /**
     * Returns the breadcrumb trail as a sequence of &raquo;-separated links.
     *
     * @param string $separator Text to separate crumbs.
     * @param string $startText This will be the first crumb, if false it defaults to first crumb in array
     * @return string Composed bread crumbs
     */
	public static function getCrumbs($separator = '&raquo;', $startText = false) {
		if (!empty(self::$_crumbs)) {
			$out = array();
			if ($startText) {
				$out[] = self::link($startText, '/');
			}

			foreach (self::_crumbs as $crumb) {
				if (!empty($crumb[1])) {
					$out[] = self::link($crumb[0], $crumb[1], $crumb[2]);
				} else {
					$out[] = $crumb[0];
				}
			}
			return join($separator, $out);
		} else {
			return null;
		}
	}

    /**
     * Returns breadcrumbs as a (x)html list
     *
     * This method uses HtmlHelper::tag() to generate list and its elements. Works
     * similiary to HtmlHelper::getCrumbs(), so it uses options which every
     * crumb was added with.
     *
     * @param array $options Array of html attributes to apply to the generated list elements.
     * @return string breadcrumbs html list
     */
	public static function getCrumbList($options = array()) {
		if (!empty(self::$_crumbs)) {
			$result = '';
			$crumbCount = count(self::$_crumbs);
			$ulOptions = $options;
			foreach (self::$_crumbs as $which => $crumb) {
				$options = array();
				if (empty($crumb[1])) {
					$elementContent = $crumb[0];
				} else {
					$elementContent = self::link($crumb[0], $crumb[1], $crumb[2]);
				}
				if ($which == 0) {
					$options['class'] = 'first';
				} elseif ($which == $crumbCount - 1) {
					$options['class'] = 'last';
				}
				$result .= self::tag('li', $elementContent, $options);
			}
			return self::tag('ul', $result, $ulOptions);
		} else {
			return null;
		}
	}

    /**
     * Creates a formatted IMG element. If `$options['url']` is provided, an image link will be
     * generated with the link pointed at `$options['url']`.  This method will set an empty
     * alt attribute if one is not supplied.
     *
     * ### Usage
     *
     * Create a regular image:
     *
     * `echo html::image('sa.png', array('alt' => 'sa'));`
     *
     * Create an image link:
     *
     * `echo html::image('sa.png', array('alt' => 'sa', 'url' => 'http://sa.org'));`
     *
     * @param string $path Path to the image file, relative to the app/webroot/img/ directory.
     * @param array $options Array of HTML attributes.
     * @return string completed img tag
     */
	public static function image($path, $options = array()) {
		if (is_array($path)) {
			$path = route::url($path);
		} elseif (strpos($path, '://') === false) {
			if ($path[0] !== '/') {
				$path = IMAGES_URL . $path;
			}
			$path = self::webroot($path);
		}

		if (!isset($options['alt'])) {
			$options['alt'] = '';
		}

		$url = false;
		if (!empty($options['url'])) {
			$url = $options['url'];
			unset($options['url']);
		}

		$image = sprintf(self::$_tags['image'], $path, self::_parseAttributes($options, null, '', ' '));

		if ($url) {
			return sprintf(self::$_tags['link'], self::url($url), null, $image);
		}
		return $image;
	}
    
    /**
     * Returns a row of formatted and named TABLE headers.
     *
     * @param array $names Array of tablenames.
     * @param array $trOptions HTML options for TR elements.
     * @param array $thOptions HTML options for TH elements.
     * @return string Completed table headers
     */
	public static function tableHeaders($names, $trOptions = null, $thOptions = null) {
		$out = array();
		foreach ($names as $arg) {
			$out[] = sprintf(self::$_tags['tableheader'], self::_parseAttributes($thOptions), $arg);
		}
		return sprintf(self::$_tags['tablerow'], self::_parseAttributes($trOptions), join(' ', $out));
	}

    /**
     * Returns a formatted string of table rows (TR's with TD's in them).
     *
     * @param array $data Array of table data
     * @param array $oddTrOptions HTML options for odd TR elements if true useCount is used
     * @param array $evenTrOptions HTML options for even TR elements
     * @param boolean $useCount adds class "column-$i"
     * @param boolean $continueOddEven If false, will use a non-static $count variable,
     *    so that the odd/even count is reset to zero just for that call.
     * @return string Formatted HTML
     */
	public static function tableCells($data, $oddTrOptions = null, $evenTrOptions = null, $useCount = false, $continueOddEven = true) {
		if (empty($data[0]) || !is_array($data[0])) {
			$data = array($data);
		}

		if ($oddTrOptions === true) {
			$useCount = true;
			$oddTrOptions = null;
		}

		if ($evenTrOptions === false) {
			$continueOddEven = false;
			$evenTrOptions = null;
		}

		if ($continueOddEven) {
			static $count = 0;
		} else {
			$count = 0;
		}

		foreach ($data as $line) {
			$count++;
			$cellsOut = array();
			$i = 0;
			foreach ($line as $cell) {
				$cellOptions = array();

				if (is_array($cell)) {
					$cellOptions = $cell[1];
					$cell = $cell[0];
				} elseif ($useCount) {
					$cellOptions['class'] = 'column-' . ++$i;
				}
				$cellsOut[] = sprintf(self::$_tags['tablecell'], self::_parseAttributes($cellOptions), $cell);
			}
			$options = self::_parseAttributes($count % 2 ? $oddTrOptions : $evenTrOptions);
			$out[] = sprintf(self::$_tags['tablerow'], $options, implode(' ', $cellsOut));
		}
		return implode("\n", $out);
	}

    /**
     * Returns a formatted block tag, i.e DIV, SPAN, P.
     *
     * ### Options
     *
     * - `escape` Whether or not the contents should be html_entity escaped.
     *
     * @param string $name Tag name.
     * @param string $text String content that will appear inside the div element.
     *   If null, only a start tag will be printed
     * @param array $options Additional HTML attributes of the DIV tag, see above.
     * @return string The formatted tag element
     */
	public static function tag($name, $text = null, $options = array()) {
		if (is_array($options) && isset($options['escape']) && $options['escape']) {
			$text = h($text);
			unset($options['escape']);
		}
		if (!is_array($options)) {
			$options = array('class' => $options);
		}
		if ($text === null) {
			$tag = 'tagstart';
		} else {
			$tag = 'tag';
		}
		return sprintf(self::$_tags[$tag], $name, self::_parseAttributes($options, null, ' ', ''), $text, $name);
	}

    /**
     * Returns a formatted existent block of $tags
     *
     * @param string $tag Tag name
     * @return string Formatted block
     */
	public static function useTag($tag) {
		if (!isset(self::$_tags[$tag])) {
			return '';
		}
		$args = func_get_args();
		array_shift($args);
		foreach ($args as &$arg) {
			if (is_array($arg)) {
				$arg = self::_parseAttributes($arg, null, ' ', '');
			}
		}
		return vsprintf(self::$_tags[$tag], $args);
	}

    /**
     * Returns a formatted DIV tag for HTML FORMs.
     *
     * ### Options
     *
     * - `escape` Whether or not the contents should be html_entity escaped.
     *
     * @param string $class CSS class name of the div element.
     * @param string $text String content that will appear inside the div element.
     *   If null, only a start tag will be printed
     * @param array $options Additional HTML attributes of the DIV tag
     * @return string The formatted DIV element
     */
	public static function div($class = null, $text = null, $options = array()) {
		if (!empty($class)) {
			$options['class'] = $class;
		}
		return self::tag('div', $text, $options);
	}

    /**
     * Returns a formatted P tag.
     *
     * ### Options
     *
     * - `escape` Whether or not the contents should be html_entity escaped.
     *
     * @param string $class CSS class name of the p element.
     * @param string $text String content that will appear inside the p element.
     * @param array $options Additional HTML attributes of the P tag
     * @return string The formatted P element
     */
	public static function para($class, $text, $options = array()) {
		if (isset($options['escape'])) {
			$text = utils::h($text);
		}
		if ($class != null && !empty($class)) {
			$options['class'] = $class;
		}
		if ($text === null) {
			$tag = 'parastart';
		} else {
			$tag = 'para';
		}
		return sprintf(self::$_tags[$tag], self::_parseAttributes($options, null, ' ', ''), $text);
	}
    /**
     * Checks if a file exists when theme is used, if no file is found default location is returned
     *
     * @param string $file The file to create a webroot path to.
     * @return string Web accessible path to file.
     */
	public static function webroot($file) {
		$asset = explode('?', $file);
		$asset[1] = isset($asset[1]) ? '?' . $asset[1] : null;
		$webPath = WEBROOT . $asset[0];
		$file = $asset[0];

		if (!config::get('current_theme')) {
			$file = trim($file, '/');
			$theme = config::get('current_theme') . '/';

			if (DS === '\\') {
				$file = str_replace('/', '\\', $file);
			}

			if (file_exists(WEBROOT . 'themes' . DS .  config::get('current_theme') . DS  . $file)) {
				$webPath = WEBROOT. "themes/" . $theme . $asset[0];
			} else {
				$webPath = WEBROOT. $asset[0];
			}
		}
		if (strpos($webPath, '//') !== false) {
			return str_replace('//', '/', $webPath . $asset[1]);
		}
		return $webPath . $asset[1];
	}
    /**
     * Adds the given class to the element options
     *
     * @param array $options Array options/attributes to add a class to
     * @param string $class The classname being added.
     * @param string $key the key to use for class.
     * @return array Array of options with $key set.
     */
	public static function addClass($options = array(), $class = null, $key = 'class') {
		if (isset($options[$key]) && trim($options[$key]) != '') {
			$options[$key] .= ' ' . $class;
		} else {
			$options[$key] = $class;
		}
		return $options;
	}

    /**
     * Returns a string generated by a helper method
     *
     * This method can be overridden in subclasses to do generalized output post-processing
     *
     * @param string $str String to be output.
     * @return string
     * @deprecated This method will be removed in future versions.
     */
	public static function output($str) {
		return $str;
	}
 

    /**
     * Build a nested list (UL/OL) out of an associative array.
     *
     * @param array $list Set of elements to list
     * @param array $options Additional HTML attributes of the list (ol/ul) tag or if ul/ol use that as tag
     * @param array $itemOptions Additional HTML attributes of the list item (LI) tag
     * @param string $tag Type of list tag to use (ol/ul)
     * @return string The nested list
     */
	public static function nestedList($list, $options = array(), $itemOptions = array(), $tag = 'ul') {
		if (is_string($options)) {
			$tag = $options;
			$options = array();
		}
		$items = self::_nestedListItem($list, $options, $itemOptions, $tag);
		return sprintf(self::$_tags[$tag], self::_parseAttributes($options, null, ' ', ''), $items);
	}

    /**
     * Internal function to build a nested list (UL/OL) out of an associative array.
     *
     * @param array $items Set of elements to list
     * @param array $options Additional HTML attributes of the list (ol/ul) tag
     * @param array $itemOptions Additional HTML attributes of the list item (LI) tag
     * @param string $tag Type of list tag to use (ol/ul)
     * @return string The nested list element
     * @see html::nestedList()
     */
	protected static function _nestedListItem($items, $options, $itemOptions, $tag) {
		$out = '';

		$index = 1;
		foreach ($items as $key => $item) {
			if (is_array($item)) {
				$item = $key . self::nestedList($item, $options, $itemOptions, $tag);
			}
			if (isset($itemOptions['even']) && $index % 2 == 0) {
				$itemOptions['class'] = $itemOptions['even'];
			} else if (isset($itemOptions['odd']) && $index % 2 != 0) {
				$itemOptions['class'] = $itemOptions['odd'];
			}
			$out .= sprintf(self::$_tags['li'], self::_parseAttributes($itemOptions, array('even', 'odd'), ' ', ''), $item);
			$index++;
		}
		return $out;
	}
 
    
    /**
     * Returns a space-delimited string with items of the $options array. If a
     * key of $options array happens to be one of:
     *
     * - 'compact'
     * - 'checked'
     * - 'declare'
     * - 'readonly'
     * - 'disabled'
     * - 'selected'
     * - 'defer'
     * - 'ismap'
     * - 'nohref'
     * - 'noshade'
     * - 'nowrap'
     * - 'multiple'
     * - 'noresize'
     *
     * And its value is one of:
     *
     * - '1' (string)
     * - 1 (integer)
     * - true (boolean)
     * - 'true' (string)
     *
     * Then the value will be reset to be identical with key's name.
     * If the value is not one of these 3, the parameter is not output.
     *
     * 'escape' is a special option in that it controls the conversion of
     *  attributes to their html-entity encoded equivalents.  Set to false to disable html-encoding.
     *
     * If value for any option key is set to `null` or `false`, that option will be excluded from output.
     *
     * @param array $options Array of options.
     * @param array $exclude Array of options to be excluded, the options here will not be part of the return.
     * @param string $insertBefore String to be inserted before options.
     * @param string $insertAfter String to be inserted after options.
     * @return string Composed attributes.
     */
	protected static function _parseAttributes($options, $exclude = null, $insertBefore = ' ', $insertAfter = null) {
		if (is_array($options)) {
			$options = array_merge(array('escape' => true), $options);

			if (!is_array($exclude)) {
				$exclude = array();
			}
			$filtered = array_diff_key($options, array_merge(array_flip($exclude), array('escape' => true)));
			$escape = $options['escape'];
			$attributes = array();

			foreach ($filtered as $key => $value) {
				if ($value !== false && $value !== null) {
					$attributes[] = self::_formatAttribute($key, $value, $escape);
				}
			}
			$out = implode(' ', $attributes);
		} else {
			$out = $options;
		}
		return $out ? $insertBefore . $out . $insertAfter : '';
	}

    /**
     * Formats an individual attribute, and returns the string value of the composed attribute.
     * Works with minimized attributes that have the same value as their name such as 'disabled' and 'checked'
     *
     * @param string $key The name of the attribute to create
     * @param string $value The value of the attribute to create.
     * @param boolean $escape Define if the value must be escaped
     * @return string The composed attribute.
     */
	protected static function _formatAttribute($key, $value, $escape = true) {
		$attribute = '';
		if (is_array($value)) {
			$value = '';
		}

		if (is_numeric($key)) {
			$attribute = sprintf(self::$_minimizedAttributeFormat, $value, $value);
		} elseif (in_array($key, self::$_minimizedAttributes)) {
			if ($value === 1 || $value === true || $value === 'true' || $value === '1' || $value == $key) {
				$attribute = sprintf(self::$_minimizedAttributeFormat, $key, $key);
			}
		} else {
			$attribute = sprintf(self::$_attributeFormat, $key, ($escape ? utils::h($value) : $value));
		}
		return $attribute;
	}
 
 
    /**
     * Returns a formatted LABEL element for HTML FORMs. Will automatically generate
     * a for attribute if one is not provided.
     *
     * @param string $fieldName This should be "Modelname.fieldname"
     * @param string $text Text that will appear in the label field.
     * @param mixed $options An array of HTML attributes, or a string, to be used as a class name.
     * @return string The formatted LABEL element
     */
	public static function label($fieldName = null, $text = null, $options = array()) {
 
		if ($text === null) {
			if (strpos($fieldName, '.') !== false) {
				$fieldElements = explode('.', $fieldName);
				$text = array_pop($fieldElements);
			} else {
				$text = $fieldName;
			}
			if (substr($text, -3) == '_id') {
				$text = substr($text, 0, strlen($text) - 3);
			}
			$text = grammar::humanize(grammar::underscore($text));
		}

		if (is_string($options)) {
			$options = array('class' => $options);
		}

		if (isset($options['for'])) {
			$labelFor = $options['for'];
			unset($options['for']);
		} else {
			$labelFor = grammar::underscore($fieldName);
		}

		return self::useTag('label', $labelFor, $options, $text);
	}

    /**
     * Generate a set of inputs for `$fields`.  If $fields is null the current model
     * will be used.
     *
     * In addition to controller fields output, `$fields` can be used to control legend
     * and fieldset rendering with the `fieldset` and `legend` keys.
     * `$form->inputs(array('legend' => 'My legend'));` Would generate an input set with
     * a custom legend.  You can customize individual inputs through `$fields` as well.
     *
     * {{{
     *	$form->inputs(array(
     *		'name' => array('label' => 'custom label')
     *	));
     * }}}
     *
     * In addition to fields control, inputs() allows you to use a few additional options.
     *
     * - `fieldset` Set to false to disable the fieldset. If a string is supplied it will be used as
     *    the classname for the fieldset element.
     * - `legend` Set to false to disable the legend for the generated input set. Or supply a string
     *	to customize the legend text.
     *
     * @param mixed $fields An array of fields to generate inputs for, or null.
     * @param array $blacklist a simple array of fields to not create inputs for.
     * @return string Completed form inputs.
     */
	public static function inputs($fields = null, $blacklist = null) {
		$fieldset = $legend = true;
 
		if (is_array($fields)) {
			if (array_key_exists('legend', $fields)) {
				$legend = $fields['legend'];
				unset($fields['legend']);
			}

			if (isset($fields['fieldset'])) {
				$fieldset = $fields['fieldset'];
				unset($fields['fieldset']);
			}
		} elseif ($fields !== null) {
			$fieldset = $legend = $fields;
			if (!is_bool($fieldset)) {
				$fieldset = true;
			}
			$fields = array();
		}
 
		$out = null;
		foreach ($fields as $name => $options) {
			if (is_numeric($name) && !is_array($options)) {
				$name = $options;
				$options = array();
			}
			$entity = explode('.', $name);
			$blacklisted = (
				is_array($blacklist) &&
				(in_array($name, $blacklist) || in_array(end($entity), $blacklist))
			);
			if ($blacklisted) {
				continue;
			}
			$out .= self::input($name, $options);
		}

		if (is_string($fieldset)) {
			$fieldsetClass = sprintf(' class="%s"', $fieldset);
		} else {
			$fieldsetClass = '';
		}

		if ($fieldset && $legend) {
			return html::useTag('fieldset', $fieldsetClass, html::useTag('legend', $legend) . $out);
		} elseif ($fieldset) {
			return html::useTag('fieldset', $fieldsetClass, $out);
		} else {
			return $out;
		}
	}

    /**
     * Generates a form input element complete with label and wrapper div
     *
     * ### Options
     *
     * See each field type method for more information. Any options that are part of
     * $attributes or $options for the different **type** methods can be included in `$options` for input().i
     * Additionally, any unknown keys that are not in the list below, or part of the selected type's options
     * will be treated as a regular html attribute for the generated input.
     *
     * - `type` - Force the type of widget you want. e.g. `type => 'select'`
     * - `label` - Either a string label, or an array of options for the label. See FormHelper::label()
     * - `div` - Either `false` to disable the div, or an array of options for the div.
     *	See HtmlHelper::div() for more options.
     * - `options` - for widgets that take options e.g. radio, select
     * - `error` - control the error message that is produced
     * - `empty` - String or boolean to enable empty select box options.
     * - `before` - Content to place before the label + input.
     * - `after` - Content to place after the label + input.
     * - `between` - Content to place between the label + input.
     * - `format` - format template for element order. Any element that is not in the array, will not be in the output.
     *	- Default input format order: array('before', 'label', 'between', 'input', 'after', 'error')
     *	- Default checkbox format order: array('before', 'input', 'between', 'label', 'after', 'error')
     *	- Hidden input will not be formatted
     *	- Radio buttons cannot have the order of input and label elements controlled with these settings.
     *
     * @param string $fieldName This should be "Modelname.fieldname"
     * @param array $options Each type of input takes different options.
     * @return string Completed form widget.
     */
	public static function input($fieldName, $options = array()) {
 
		$options = array_merge(
			array('before' => null, 'between' => null, 'after' => null, 'format' => null),
			$options
		);
 
		$types = array('checkbox', 'radio', 'select');
 

		$autoLength = (!array_key_exists('maxlength', $options));
 

		$divOptions = array();
		$div = self::_extractOption('div', $options, true);
		unset($options['div']);

		if (!empty($div)) {
			$divOptions['class'] = 'input';
			$divOptions = self::addClass($divOptions, $options['type']);
			if (is_string($div)) {
				$divOptions['class'] = $div;
			} elseif (is_array($div)) {
				$divOptions = array_merge($divOptions, $div);
			}
 
			if (!isset($divOptions['tag'])) {
				$divOptions['tag'] = 'div';
			}
		}

		$label = null;
		if (isset($options['label']) && $options['type'] !== 'radio') {
			$label = $options['label'];
			unset($options['label']);
		}

		if ($options['type'] === 'radio') {
			$label = false;
			if (isset($options['options'])) {
				$radioOptions = (array)$options['options'];
				unset($options['options']);
			}
		}

		if ($label !== false) {
			$label = self::_inputLabel($fieldName, $label, $options);
		}

		$error = self::_extractOption('error', $options, null);
		unset($options['error']);

		$selected = self::_extractOption('selected', $options, null);
		unset($options['selected']);

		if (isset($options['rows']) || isset($options['cols'])) {
			$options['type'] = 'textarea';
		}

	 

		$type = $options['type'];
		$out = array_merge(
			array('before' => null, 'label' => null, 'between' => null, 'input' => null, 'after' => null, 'error' => null),
			array('before' => $options['before'], 'label' => $label, 'between' => $options['between'], 'after' => $options['after'])
		);
		$format = null;
		if (is_array($options['format']) && in_array('input', $options['format'])) {
			$format = $options['format'];
		}
		unset($options['type'], $options['before'], $options['between'], $options['after'], $options['format']);

		switch ($type) {
			case 'hidden':
				$input = self::hidden($fieldName, $options);
				$format = array('input');
				unset($divOptions);
			break;
			case 'checkbox':
				$input = self::checkbox($fieldName, $options);
				$format = $format ? $format : array('before', 'input', 'between', 'label', 'after', 'error');
			break;
			case 'radio':
				$input = self::radio($fieldName, $radioOptions, $options);
			break;
			case 'file':
				$input = self::file($fieldName, $options);
			break;
			case 'select':
				$options += array('options' => array(), 'value' => $selected);
				$list = $options['options'];
				unset($options['options']);
				$input = self::select($fieldName, $list, $options);
			break;
			 
			case 'textarea':
				$input = self::textarea($fieldName, $options + array('cols' => '30', 'rows' => '6'));
			break;
			case 'url':
				$input = self::text($fieldName, array('type' => 'url') + $options);
			break;
 
		}

		if ($type != 'hidden' && $error !== false) {
			throw new SAException('');
		}

		$out['input'] = $input;
		$format = $format ? $format : array('before', 'label', 'between', 'input', 'after', 'error');
		$output = '';
		foreach ($format as $element) {
			$output .= $out[$element];
			unset($out[$element]);
		}

		if (!empty($divOptions['tag'])) {
			$tag = $divOptions['tag'];
			unset($divOptions['tag']);
			$output = self::tag($tag, $output, $divOptions);
		}
		return $output;
	}

    /**
     * Extracts a single option from an options array.
     *
     * @param string $name The name of the option to pull out.
     * @param array $options The array of options you want to extract.
     * @param mixed $default The default option value
     * @return mixed the contents of the option or default
     */
	protected static function _extractOption($name, $options, $default = null) {
		if (array_key_exists($name, $options)) {
			return $options[$name];
		}
		return $default;
	}

    /**
     * Generate a label for an input() call.
     *
     * @param string $fieldName
     * @param string $label
     * @param array $options Options for the label element.
     * @return string Generated label element
     */
	protected static function _inputLabel($fieldName, $label, $options) {
 

		if (is_array($label)) {
			$labelText = null;
			if (isset($label['text'])) {
				$labelText = $label['text'];
				unset($label['text']);
			}
			$labelAttributes = array_merge($labelAttributes, $label);
		} else {
			$labelText = $label;
		}

		if (isset($options['id']) && is_string($options['id'])) {
			$labelAttributes = array_merge($labelAttributes, array('for' => $options['id']));
		}
		return self::label($fieldName, $labelText, $labelAttributes);
	}

    /**
     * Creates a checkbox input widget.
     *
     * ### Options:
     *
     * - `value` - the value of the checkbox
     * - `checked` - boolean indicate that this checkbox is checked.
     * - `hiddenField` - boolean to indicate if you want the results of checkbox() to include
     *    a hidden input with a value of ''.
     * - `disabled` - create a disabled input.
     * - `default` - Set the default value for the checkbox.  This allows you to start checkboxes
     *    as checked, without having to check the POST data.  A matching POST data value, will overwrite
     *    the default value.
     *
     * @param string $fieldName Name of a field, like this "Modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string An HTML text input element.
     */
	public static function checkbox($fieldName, $options = array()) {
		$valueOptions = array();
		if(isset($options['default'])){
			$valueOptions['default'] = $options['default'];
			unset($options['default']);
		}

		$options = array('hiddenField' => true);
		$value = current($this->value($valueOptions));
		$output = "";

		if (empty($options['value'])) {
			$options['value'] = 1;
		}
		if (
			(!isset($options['checked']) && !empty($value) && $value == $options['value']) ||
			!empty($options['checked'])
		) {
			$options['checked'] = 'checked';
		}
		if ($options['hiddenField']) {
			$hiddenOptions = array(
				'id' => $options['id'] . '_', 'name' => $options['name'],
				'value' => '0', 'secure' => false
			);
			if (isset($options['disabled']) && $options['disabled'] == true) {
				$hiddenOptions['disabled'] = 'disabled';
			}
			$output = self::hidden($fieldName, $hiddenOptions);
		}
		unset($options['hiddenField']);

		return $output . self::useTag('checkbox', $options['name'], array_diff_key($options, array('name' => '')));
	}

    /**
     * Creates a set of radio widgets. Will create a legend and fieldset
     * by default.  Use $options to control this
     *
     * ### Attributes:
     *
     * - `separator` - define the string in between the radio buttons
     * - `legend` - control whether or not the widget set has a fieldset & legend
     * - `value` - indicate a value that is should be checked
     * - `label` - boolean to indicate whether or not labels for widgets show be displayed
     * - `hiddenField` - boolean to indicate if you want the results of radio() to include
     *    a hidden input with a value of ''. This is useful for creating radio sets that non-continuous
     *
     * @param string $fieldName Name of a field, like this "Modelname.fieldname"
     * @param array $options Radio button options array.
     * @param array $attributes Array of HTML attributes, and special attributes above.
     * @return string Completed radio widget set.
     */
	public static function radio($fieldName, $options = array(), $attributes = array()) {
		$attributes = $this->_initInputField($fieldName, $attributes);
		$legend = false;
		$disabled = array();

		if (isset($attributes['legend'])) {
			$legend = $attributes['legend'];
			unset($attributes['legend']);
		} 
		$label = true;

		if (isset($attributes['label'])) {
			$label = $attributes['label'];
			unset($attributes['label']);
		}
		$inbetween = null;

		if (isset($attributes['separator'])) {
			$inbetween = $attributes['separator'];
			unset($attributes['separator']);
		}

		if (isset($attributes['value'])) {
			$value = $attributes['value'];
		} else {
			$value =  $this->value($fieldName);
		}

		if (isset($attributes['disabled'])) {
			$disabled = $attributes['disabled'];
		}

		$out = array();

		$hiddenField = isset($attributes['hiddenField']) ? $attributes['hiddenField'] : true;
		unset($attributes['hiddenField']);

		foreach ($options as $optValue => $optTitle) {
			$optionsHere = array('value' => $optValue);

			if (isset($value) && $optValue == $value) {
				$optionsHere['checked'] = 'checked';
			}
			if (!empty($disabled) && in_array($optValue, $disabled)) {
				$optionsHere['disabled'] = true;
			}
			$tagName = grammar::camelize(
				$attributes['id'] . '_' . grammar::slug($optValue)
			);

			if ($label) {
				$optTitle = self::useTag('label', $tagName, '', $optTitle);
			}
			$allOptions = array_merge($attributes, $optionsHere);
			$out[] = self::useTag('radio', $attributes['name'], $tagName,
				array_diff_key($allOptions, array('name' => '', 'type' => '', 'id' => '')),
				$optTitle
			);
		}
		$hidden = null;

		if ($hiddenField) {
			if (!isset($value) || $value === '') {
				$hidden = self::hidden($fieldName, array(
					'id' => $attributes['id'] . '_', 'value' => '', 'name' => $attributes['name']
				));
			}
		}
		$out = $hidden . implode($inbetween, $out);

		if ($legend) {
			$out = self::useTag('fieldset', '', self::useTag('legend', $legend) . $out);
		}
		return $out;
	}
    /**
     * Creates a textarea widget.
     *
     * ### Options:
     *
     * - `escape` - Whether or not the contents of the textarea should be escaped. Defaults to true.
     *
     * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
     * @param array $options Array of HTML attributes, and special options above.
     * @return string A generated HTML text input element
     */
	public static function textarea($fieldName, $options = array()) {
 
		$value = null;

		if (array_key_exists('value', $options)) {
			$value = $options['value'];
			if (!array_key_exists('escape', $options) || $options['escape'] !== false) {
				$value = h($value);
			}
			unset($options['value']);
		}
		return self::useTag('textarea', $options['name'], array_diff_key($options, array('type' => '', 'name' => '')), $value);
	}

    /**
     * Creates a hidden input field.
     *
     * @param string $fieldName Name of a field, in the form of "Modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string A generated hidden input
     */
	public static function hidden($fieldName, $options = array()) {

		return self::useTag('hidden', $options['name'], array_diff_key($options, array('name' => '')));
	}

    /**
     * Creates file input widget.
     *
     * @param string $fieldName Name of a field, in the form "Modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string A generated file input.
     */
	public static function file($fieldName, $options = array()) {
		 

		foreach (array('name', 'type', 'tmp_name', 'error', 'size') as $suffix) {
			$this->_secure($secure, array_merge($field, array($suffix)));
		}

		return self::useTag('file', $options['name'], array_diff_key($options, array('name' => '')));
	}

    /**
     * Creates a `<button>` tag.  The type attribute defaults to `type="submit"`
     * You can change it to a different value by using `$options['type']`.
     *
     * ### Options:
     *
     * - `escape` - HTML entity encode the $title of the button. Defaults to false.
     *
     * @param string $title The button's caption. Not automatically HTML encoded
     * @param array $options Array of options and HTML attributes.
     * @return string A HTML button tag.
     */
	public static function button($title, $options = array()) {
		$options += array('type' => 'submit', 'escape' => false);
		if ($options['escape']) {
			$title = utils::h($title);
		}
		if (isset($options['name'])) {
			$name = str_replace(array('[', ']'), array('.', ''), $options['name']);
			 
		}
		return self::useTag('button', $options['type'], array_diff_key($options, array('type' => '')), $title);
	}

 

    /**
     * Creates an HTML link, but access the url using method POST.
     * Requires javascript to be enabled in browser.
     *
     * This method creates a `<form>` element. So do not use this method inside an existing form.
     * Instead you should add a submit button using FormHelper::submit()
     *
     * ### Options:
     *
     * - `data` - Array with key/value to pass in input hidden
     * - `confirm` - Can be used instead of $confirmMessage.
     * - Other options is the same of HtmlHelper::link() method.
     * - The option `onclick` will be replaced.
     *
     * @param string $title The content to be wrapped by <a> tags.
     * @param mixed $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
     * @param array $options Array of HTML attributes.
     * @param string $confirmMessage JavaScript confirmation message.
     * @return string An `<a />` element.
     */
	public static function postLink($title, $url = null, $options = array(), $confirmMessage = false) {
		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}

		$formName = uniqid('post_');
		$formUrl = route::url($url);
		$out = self::useTag('form', $formUrl, array('name' => $formName, 'id' => $formName, 'style' => 'display:none;', 'method' => 'post'));
		$out .= self::useTag('hidden', '_method', ' value="POST"');
 

		$fields = array();
		if (isset($options['data']) && is_array($options['data'])) {
			foreach ($options['data'] as $key => $value) {
				$fields[$key] = $value;
				$out .= self::hidden($key, array('value' => $value, 'id' => false));
			}
			unset($options['data']);
		}
 
		$out .= self::useTag('formend');

		$url = '#';
		$onClick = 'document.' . $formName . '.submit();';
		if ($confirmMessage) {
			$confirmMessage = str_replace(array("'", '"'), array("\'", '\"'), $confirmMessage);
			$options['onclick'] = "if (confirm('{$confirmMessage}')) { {$onClick} }";
		} else {
			$options['onclick'] = $onClick;
		}
		$options['onclick'] .= ' event.returnValue = false; return false;';

		$out .= self::link($title, $url, $options);
		return $out;
	}
    /**
     * Add to or get the list of fields that are currently unlocked.
     * Unlocked fields are not included in the field hash used by SecurityComponent
     * unlocking a field once its been added to the list of secured fields will remove
     * it from the list of fields.
     *
     * @param string $name The dot separated name for the field.
     * @return mixed Either null, or the list of fields.
     */
	public static function unlockField($name = null) {
		if ($name === null) {
			return self::$_unlockedFields;
		}
		if (!in_array($name, self::$_unlockedFields)) {
			self::$_unlockedFields[] = $name;
		}
		$index = array_search($name, $this->fields);
 
	 
	}
    /**
     * Creates a submit button element.  This method will generate `<input />` elements that
     * can be used to submit, and reset forms by using $options.  image submits can be created by supplying an
     * image path for $caption.
     *
     * ### Options
     *
     * - `div` - Include a wrapping div?  Defaults to true.  Accepts sub options similar to
     *   FormHelper::input().
     * - `before` - Content to include before the input.
     * - `after` - Content to include after the input.
     * - `type` - Set to 'reset' for reset inputs.  Defaults to 'submit'
     * - Other attributes will be assigned to the input element.
     *
     * ### Options
     *
     * - `div` - Include a wrapping div?  Defaults to true.  Accepts sub options similar to
     *   html::input().
     * - Other attributes will be assigned to the input element.
     *
     * @param string $caption The label appearing on the button OR if string contains :// or the
     *  extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
     *  exists, AND the first character is /, image is relative to webroot,
     *  OR if the first character is not /, image is relative to webroot/img.
     * @param array $options Array of options.  See above.
     * @return string A HTML submit button
     */
	public static function submit($caption = null, $options = array()) {
		if (!is_string($caption) && empty($caption)) {
			$caption = 'Submit';
		}
		$out = null;
		$div = true;

		if (isset($options['div'])) {
			$div = $options['div'];
			unset($options['div']);
		}
		$options += array('type' => 'submit', 'before' => null, 'after' => null, 'secure' => false);
		$divOptions = array('tag' => 'div');

		if ($div === true) {
			$divOptions['class'] = 'submit';
		} elseif ($div === false) {
			unset($divOptions);
		} elseif (is_string($div)) {
			$divOptions['class'] = $div;
		} elseif (is_array($div)) {
			$divOptions = array_merge(array('class' => 'submit', 'tag' => 'div'), $div);
		}

		if (isset($options['name'])) {
			$name = str_replace(array('[', ']'), array('.', ''), $options['name']);
		 
		}
		unset($options['secure']);

		$before = $options['before'];
		$after = $options['after'];
		unset($options['before'], $options['after']);

		$isUrl = strpos($caption, '://') !== false;
		$isImage = preg_match('/\.(jpg|jpe|jpeg|gif|png|ico)$/', $caption);

		if ($isUrl || $isImage) {
			$unlockFields = array('x', 'y');
			if (isset($options['name'])) {
				$unlockFields = array(
					$options['name'] . '_x', $options['name'] . '_y'
				);
			}
			foreach ($unlockFields as $ignore) {
				self::unlockField($ignore);
			}
		}

		if ($isUrl) {
			unset($options['type']);
			$tag = self::useTag('submitimage', $caption, $options);
		} elseif ($isImage) {
			unset($options['type']);
			if ($caption{0} !== '/') {
				$url = self::webroot(IMAGES_URL . $caption);
			} else {
				$url = self::webroot(trim($caption, '/'));
			}
			 
			$tag = self::useTag('submitimage', $url, $options);
		} else {
			$options['value'] = $caption;
			$tag = self::useTag('submit', $options);
		}
		$out = $before . $tag . $after;

		if (isset($divOptions)) {
			$tag = $divOptions['tag'];
			unset($divOptions['tag']);
			$out = self::tag($tag, $out, $divOptions);
		}
		return $out;
	}

    /**
     * Returns a formatted SELECT element.
     *
     * ### Attributes:
     *
     * - `showParents` - If included in the array and set to true, an additional option element
     *   will be added for the parent of each option group. You can set an option with the same name
     *   and it's key will be used for the value of the option.
     * - `multiple` - show a multiple select box.  If set to 'checkbox' multiple checkboxes will be
     *   created instead.
     * - `empty` - If true, the empty select option is shown.  If a string,
     *   that string is displayed as the empty element.
     * - `escape` - If true contents of options will be HTML entity encoded. Defaults to true.
     * - `value` The selected value of the input.
     * - `class` - When using multiple = checkbox the classname to apply to the divs. Defaults to 'checkbox'.
     *
     * ### Using options
     *
     * A simple array will create normal options:
     *
     * {{{
     * $options = array(1 => 'one', 2 => 'two);
     * $this->Form->select('Model.field', $options));
     * }}}
     *
     * While a nested options array will create optgroups with options inside them.
     * {{{
     * $options = array(
     *	1 => 'bill',
     *	'fred' => array(
     *		2 => 'fred',
     *		3 => 'fred jr.'
     *	 )
     * );
     * html::select('field', $options);
     * }}}
     *
     * In the above `2 => 'fred'` will not generate an option element.  You should enable the `showParents`
     * attribute to show the fred option.
     *
     * If you have multiple options that need to have the same value attribute, you can
     * use an array of arrays to express this:
     *
     * {{{
     * $options = array(
     *		array('name' => 'United states', 'value' => 'USA'),
     *		array('name' => 'USA', 'value' => 'USA'),
     * );
     * }}}
     *
     * @param string $fieldName Name attribute of the SELECT
     * @param array $options Array of the OPTION elements (as 'value'=>'Text' pairs) to be used in the
     *	SELECT element
     * @param array $attributes The HTML attributes of the select element.
     * @return string Formatted SELECT element
     */
	public static function select($fieldName, $options = array(), $attributes = array()) {
		$select = array();
		$style = null;
		$tag = null;
		$attributes += array(
			'class' => null,
			'escape' => true,
			'secure' => true,
			'empty' => '',
			'showParents' => false,
			'hiddenField' => true,
            'disabled' => null,
		);

		$escapeOptions = self::_extractOption('escape', $attributes);
        
        $attributes['name'] = $fieldName;
        if(!isset($attributes['id'])) $attributes['id'] = $fieldName;
        if(!isset($attributes['value'])) $attributes['value'] = false;
        
		$showEmpty = self::_extractOption('empty', $attributes);
		$showParents = self::_extractOption('showParents', $attributes);
		$hiddenField = self::_extractOption('hiddenField', $attributes);
		unset($attributes['escape'] , $attributes['empty'], $attributes['showParents'], $attributes['hiddenField']);
		$id = self::_extractOption('id', $attributes);
 
		if (isset($attributes['type'])) {
			unset($attributes['type']);
		}

		if (!isset($selected)) {
			$selected = $attributes['value'];
		}

		if (!empty($attributes['multiple'])) {
			$style = ($attributes['multiple'] === 'checkbox') ? 'checkbox' : null;
			$template = ($style) ? 'checkboxmultiplestart' : 'selectmultiplestart';
			$tag = $template;
			if ($hiddenField) {
				$hiddenAttributes = array(
					'value' => '',
					'id' => $attributes['id'] . ($style ? '' : '_'),
					'name' => $attributes['name']
				);
				$select[] = self::hidden(null, $hiddenAttributes);
			}
		} else {
 			$tag = 'selectstart';
		}

		if (!empty($tag) || isset($template)) {
			 
			$select[] = self::useTag($tag, $attributes['name'], array_diff_key($attributes, array('name' => '', 'value' => '')));
		}
		$emptyMulti = (
			$showEmpty !== null && $showEmpty !== false && !(
				empty($showEmpty) && (isset($attributes) &&
				array_key_exists('multiple', $attributes))
			)
		);

		if ($emptyMulti) {
			$showEmpty = ($showEmpty === true) ? '' : $showEmpty;
			$options = array_reverse($options, true);
			$options[''] = $showEmpty;
			$options = array_reverse($options, true);
		}

		if (!$id) {
			$attributes['id'] = grammar::camelize($attributes['id']);
		}
 
		$select = array_merge($select, self::_selectOptions(
			array_reverse($options, true),
			array(),
			$showParents,
			array(
				'escape' => $escapeOptions,
				'style' => $style,
				'name' => $attributes['name'],
				'value' => $attributes['value'],
				'class' => $attributes['class'],
				'id' => $attributes['id'],
                'disabled' => $attributes['disabled'],
			)
		));

		$template = ($style == 'checkbox') ? 'checkboxmultipleend' : 'selectend';
		$select[] = self::useTag($template);
		return implode("\n", $select);
	}
    /**
     * Returns an array of formatted OPTION/OPTGROUP elements
     *
     * @param array $elements
     * @param array $parents
     * @param boolean $showParents
     * @param array $attributes
     * @return array
     */
	protected static function _selectOptions($elements = array(), $parents = array(), $showParents = null, $attributes = array()) {
		$select = array();
		$attributes = array_merge(
			array('escape' => true, 'style' => null, 'value' => null, 'class' => null),
			$attributes
		);

		$selectedIsEmpty = ($attributes['value'] === '' || $attributes['value'] === null);
		$selectedIsArray = is_array($attributes['value']);
        
        $disabledIsEmpty = ($attributes['disabled'] === '' || $attributes['disabled'] === null);
		$disabledIsArray = is_array($attributes['disabled']);
 
		foreach ($elements as $name => $title) {
			$htmlOptions = array();
			if (is_array($title) && (!isset($title['name']) || !isset($title['value']))) {
				if (!empty($name)) {
					if ($attributes['style'] === 'checkbox') {
						$select[] = self::useTag('fieldsetend');
					} else {
						$select[] = self::useTag('optiongroupend');
					}
					$parents[] = $name;
				}
				$select = array_merge($select, self::_selectOptions(
					$title, $parents, $showParents, $attributes
				));

				if (!empty($name)) {
					$name = $attributes['escape'] ? utils::h($name) : $name;
					if ($attributes['style'] === 'checkbox') {
						$select[] = self::useTag('fieldsetstart', $name);
					} else {
						$select[] = self::useTag('optiongroup', $name, '');
					}
				}
				$name = null;
			} elseif (is_array($title)) {
				$htmlOptions = $title;
				$name = $title['value'];
				$title = $title['name'];
				unset($htmlOptions['name'], $htmlOptions['value']);
			}

			if ($name !== null) {
				if (
					(!$selectedIsArray && !$selectedIsEmpty && (string)$attributes['value'] == (string)$name) ||
					($selectedIsArray && in_array($name, $attributes['value']))
				) {
					if ($attributes['style'] === 'checkbox') {
						$htmlOptions['checked'] = true;
					} else {
						$htmlOptions['selected'] = 'selected';
					}
				}
                
                if (
					(!$disabledIsArray && !$disabledIsEmpty && (string)$attributes['disabled'] == (string)$name) ||
					($disabledIsArray && in_array($name, $attributes['disabled']))
				) {					
						$htmlOptions['disabled'] = true;					
				}
                
				if ($showParents || (!in_array($title, $parents))) {
					$title = ($attributes['escape']) ? utils::h($title) : $title;

					if ($attributes['style'] === 'checkbox') {
						$htmlOptions['value'] = $name;

						$tagName = $attributes['id'] . grammar::camelize(grammar::slug($name));
						$htmlOptions['id'] = $tagName;
						$label = array('for' => $tagName);

						if (isset($htmlOptions['checked']) && $htmlOptions['checked'] === true) {
							$label['class'] = 'selected';
						}

						$name = $attributes['name'];

						if (empty($attributes['class'])) {
							$attributes['class'] = 'checkbox';
						} elseif ($attributes['class'] === 'form-error') {
							$attributes['class'] = 'checkbox ' . $attributes['class'];
						}
						$label = self::label(null, $title, $label);
						$item = self::useTag('checkboxmultiple', $name, $htmlOptions);
						$select[] = self::div($attributes['class'], $item . $label);
					} else {
						$select[] = self::useTag('selectoption', $name, $htmlOptions, $title);
					}
				}
			}
		}

		return array_reverse($select, true);
	}

    /**
     * Generates option lists for common <select /> menus
     *
     * @param string $name
     * @param array $options
     * @return array
     */
	public static function _generateOptions($name, $options = array()) {
		 
		$data = array();

		switch ($name) {
			case 'minute':
				if (isset($options['interval'])) {
					$interval = $options['interval'];
				} else {
					$interval = 1;
				}
				$i = 0;
				while ($i < 60) {
					$data[sprintf('%02d', $i)] = sprintf('%02d', $i);
					$i += $interval;
				}
			break;
			case 'hour':
				for ($i = 1; $i <= 12; $i++) {
					$data[sprintf('%02d', $i)] = $i;
				}
			break;
			case 'hour24':
				for ($i = 0; $i <= 23; $i++) {
					$data[sprintf('%02d', $i)] = $i;
				}
			break;
			case 'meridian':
				$data = array('am' => 'am', 'pm' => 'pm');
			break;
			case 'day':
				$min = 1;
				$max = 31;

				if (isset($options['min'])) {
					$min = $options['min'];
				}
				if (isset($options['max'])) {
					$max = $options['max'];
				}

				for ($i = $min; $i <= $max; $i++) {
					$data[sprintf('%02d', $i)] = $i;
				}
			break;
			case 'range':
				$min = 1;
				$max = 31;

				if (isset($options['min'])) {
					$min = $options['min'];
				}
				if (isset($options['max'])) {
					$max = $options['max'];
				}

				for ($i = $min; $i <= $max; $i++) {
					$data[$i] = $i;
				}
			break;            
			case 'month':
				if ($options['monthNames'] === true) {
					$data['01'] =  'January';
					$data['02'] =  'February';
					$data['03'] =  'March';
					$data['04'] =  'April';
					$data['05'] =  'May';
					$data['06'] =  'June';
					$data['07'] =  'July';
					$data['08'] =  'August';
					$data['09'] =  'September';
					$data['10'] =  'October';
					$data['11'] =  'November';
					$data['12'] =  'December';
				} else if (is_array($options['monthNames'])) {
					$data = $options['monthNames'];
				} else {
					for ($m = 1; $m <= 12; $m++) {
						$data[sprintf("%02s", $m)] = strftime("%m", mktime(1, 1, 1, $m, 1, 1999));
					}
				}
			break;
			case 'year':
				$current = intval(date('Y'));

				if (!isset($options['min'])) {
					$min = $current - 20;
				} else {
					$min = $options['min'];
				}

				if (!isset($options['max'])) {
					$max = $current + 20;
				} else {
					$max = $options['max'];
				}
				if ($min > $max) {
					list($min, $max) = array($max, $min);
				}
				for ($i = $min; $i <= $max; $i++) {
					$data[$i] = $i;
				}
				if ($options['order'] != 'asc') {
					$data = array_reverse($data, true);
				}
			break;
		}
		return $data;
 
	}

}