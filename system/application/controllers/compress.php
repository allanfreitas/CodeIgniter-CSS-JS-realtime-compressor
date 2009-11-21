<?php
class Compress extends Controller
{

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('file');
	}
	
	// ------------------------------------------------------------------------
	
	/*
	 * CodeIgniter JS+CSS compressor script
	 * Copyright (C) 2009 Christoffer Lejdborg
	 * http://www.lejdborg.se/
	 *
	 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
	 * SOFTWARE.
	 *
	 * URI: .../compress/{js|css}/
	 */
	function index()
	{
		$type = $this->uri->segment(2);
		
		// Settings
		$js_dir = 'js/';
		$css_dir = 'css/';
		
		// Define files...
		if ( $type == 'js' )
		{
			$files = array(
				'js/jquery/jquery-1.3.2.min.js',
				'js/jquery/jquery-ui-1.7.2.custom.min.js'
				// etc ...
			);
			
			$dir = $js_dir;
		}
		else if ( $type == 'css' )
		{
			$files = array(
				'css/typography.css',
				'css/ui.css'
				// etc ...
			);
			
			$dir = $css_dir;
		}
		else
		{
			header('HTTP/1.0 404 Not Found');
			return NULL;
		}
		
		// Detirmine if file should be generated...
		$generate_new_file = FALSE;
		if ( file_exists($dir.'/cache.'.$type) )
		{			
			$cache_created_at = filectime($dir.'/cache.'.$type);
			foreach ( $files as $file )
			{
				if ( filectime($file) > $cache_created_at )
				{
					$generate_new_file = TRUE;
					break;
				}
			}
		}
		else
		{
			$generate_new_file = TRUE;
		}
		
		// Generate file...
		if ( $generate_new_file )
		{
			$output = '';
			foreach ( $files as $file )
			{
				$output .= read_file($file);
			}
			
			if ( $type == 'js' )
			{
				$output = JSMin::minify($output)
			}
			
			write_file($dir.'/cache.'.$type, $output);
		}
		
		// Print cached file...
		$print_type = ($type == 'css') ? 'css' : 'javascript';
		
		header('Content-type: text/'.$print_type);
		echo read_file($dir.'/cache.'.$type);
	}

}

/* End of file api.php */
/* Location: ./system/application/controllers/api.php */
