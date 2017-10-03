<?php
/* 

This file is part of TREAM - Portfolio Management Software.

TREAM is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of
the License, or (at your option) any later version.

TREAM is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with TREAM. If not, see <http://www.gnu.org/licenses/gpl.html>.

To contact the authors write to: 
Timon Zielonka <timon@zukunft.com>

Copyright (c) 2013-2017 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>
*/

/** This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2017 zukunft.com AG, Zurich
 * @link http://tream.biz
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License

*/
class Security_underlyings extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->security_underlyings);
		$this->setTitle("Security underlyings or other security links");
		$this->firstRow();

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->fields->security_id
			->setLabel("Security")
			->setType("select")
			->setSource(P4A::singleton()->select_securities)
			->setSourceDescriptionField("select_name");

		$this->fields->underlying_id
			->setLabel("Underlying")
			->setType("select")
			->setSource(P4A::singleton()->underlyings)
			->setSourceDescriptionField("select_name");

		$this->fields->security_link_type_id
			->setLabel("Link Type")
			->setType("select")
			->setSource(P4A::singleton()->security_link_types_select)
			->setSourceDescriptionField("type_name");

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->security_underlyings)
			->setVisibleCols(array("security","underlying","weight","delta","comment")) 
			->setWidth(500)
			->showNavigationBar();

		//$this->setRequiredField("description");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Security type detail")
			->anchor($this->fields->security_id)
			->anchor($this->fields->underlying_id)
			->anchor($this->fields->security_link_type_id)
			->anchor($this->fields->weight)
			->anchor($this->fields->delta)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->weight);
	}
}
