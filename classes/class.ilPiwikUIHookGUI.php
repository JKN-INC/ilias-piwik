<?php

/* Copyright (c) 1998-2010 ILIAS open source, Extended GPL, see docs/LICENSE */

include_once("./Services/UIComponent/classes/class.ilUIHookPluginGUI.php");

/**
 * User interface hook Piwik class.
 *
 * Base Piwik UI Class, adding tracking code
 * @author Peter Boden <mail@pebosi.net>
 * @version
 *
 * @ingroup ServicesUIComponent
 */
class ilPiwikUIHookGUI extends ilUIHookPluginGUI
{
	/**
	 * Modify HTML output of GUI elements. Modifications modes are:
	 * - ilUIHookPluginGUI::KEEP (No modification)
	 * - ilUIHookPluginGUI::REPLACE (Replace default HTML with your HTML)
	 * - ilUIHookPluginGUI::APPEND (Append your HTML to the default HTML)
	 * - ilUIHookPluginGUI::PREPEND (Prepend your HTML to the default HTML)
	 *
	 * @param string $a_comp component
	 * @param string $a_part string that identifies the part of the UI that is handled
	 * @param string $a_par array of parameters (depend on $a_comp and $a_part)
	 *
	 * @return array array with entries "mode" => modification mode, "html" => your html
	 */
	function getHTML($a_comp, $a_part, $a_par = array())
	{
		global $ilCtrl, $ilUser;

		// loading a template and this is NOT an async call?
		if ($a_part == "template_load" && !$ilCtrl->isAsynch())
		{
			// is main template?
			if (strtolower($a_par['tpl_id']) == "tpl.main.html")
			{

                $html = $a_par['html'];
                $index = strripos($html, "</head>", -7);
                if ($index !== false)
		        {
		   
                    $tmpl = $this->plugin_object->getTemplate("tpl.piwik_tracking.html", true, true);
                    $tmpl->setVariable("PORTAL_NAME", CLIENT_NAME);
		    
                    // insert code
                    $html = substr($html, 0, $index) . $tmpl->get() . substr($html, $index);
                    return array("mode" => ilUIHookPluginGUI::REPLACE, "html" => $html);
                }

			}
		}

		return array("mode" => ilUIHookPluginGUI::KEEP, "html" => "");
	}
}
