<?php
/** ---------------------------------------------------------------------
 * app/lib/core/Plugins/PDFRenderer/domPDF.php : renders HTML as PDF using domPDF
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This source code is free and modifiable under the terms of
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * @package CollectiveAccess
 * @subpackage Print
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License version 3
 *
 * ----------------------------------------------------------------------
 */

  /**
    *
    */ 
    
include_once(__CA_LIB_DIR__."/core/Plugins/PDFRenderer/BasePDFRendererPlugin.php");
require_once(__CA_LIB_DIR__.'/core/Parsers/dompdf/dompdf_config.inc.php');
include_once(__CA_APP_DIR__."/helpers/mediaPluginHelpers.php");

class WLPlugPDFRendererdomPDF Extends BasePDFRendererPlugIn Implements IWLPlugPDFRenderer {
	# ------------------------------------------------
	/** 
	 *
	 */
	private $renderer;
	
	# ------------------------------------------------
	/**
	 *
	 */
	public function __construct() {
		parent::__construct();
		$this->info['NAME'] = 'domPDF';
		$this->set('CODE', 'domPDF');
		
		$this->description = _t('Renders HTML as PDF using domPDF');
		
		$this->renderer = new DOMPDF();
	}
	# ------------------------------------------------
	/**
	 * Render HTML formatted string as a PDF
	 *
	 * @param string $ps_content A fully-formed HTML document to render as a PDF
	 * @param array $pa_options Options include:
	 *		stream = Output the rendered PDF directly to the response [Default=false]
	 *		filename = The filename to set the PDF to when streams [Default=export_results.pdf]
	 *
	 * @return string The rendered PDF content
	 * @seealso domPDF::renderFile()
	 */
	public function render($ps_content, $pa_options=null) {
		$this->renderer->load_html($ps_content);
		$this->renderer->render();
		
		if (caGetOption('stream', $pa_options, false)) {
			$this->renderer->stream(caGetOption('filename', $pa_options, 'export_results.pdf'));
		}
		
		return $this->renderer->output();
	}
	# ------------------------------------------------
	/**
	 * Render HTML file as a PDF
	 *
	 * @param string $ps_file_path Path to fully-formed HTML file to render as a PDF
	 * @param array $pa_options Options include:
	 *		stream = Output the rendered PDF directly to the response [Default=false]
	 *		filename = The filename to set the PDF to when streams [Default=export_results.pdf]
	 *
	 * @return string The rendered PDF content
	 * @seealso domPDF::render()
	 */
	public function renderFile($ps_file_path, $pa_options=null) {
		$this->renderer->load_html_file($load_html_file);
		
		$this->renderer->render();
		
		if (caGetOption('stream', $pa_options, false)) {
			$this->renderer->stream(caGetOption('filename', $pa_options, 'export_results.pdf'));
		}
		
		return $this->renderer->output();
	}
	# ------------------------------------------------
	/**
	 * Set page size and orientation
	 *
	 * @param string Page size (ex. A4, letter, legal)
	 * @param string Page orientation (ex. portrait, landscape)
	 *
	 * @return bool True on success, false if parameters are invalid
	 */
	public function setPage($ps_size, $ps_orientation, $ps_margin_top=0, $ps_margin_right=0, $ps_margin_bottom=0, $ps_margin_left=0) {
		$this->renderer->set_paper($ps_size, $ps_orientation);
		
		return true;
	}
	# ------------------------------------------------
	/**
	 * Returns status of plugin.
	 *
	 * @return array - status info array; 'available' key determines if the plugin should be loaded or not
	 */
	public function checkStatus() {
		$va_status = parent::checkStatus();
		$va_status['available'] = !caPhantomJSInstalled() && !caWkhtmltopdfInstalled();
		
		return $va_status;
	}
	# ------------------------------------------------
}