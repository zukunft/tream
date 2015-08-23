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

Copyright (c) 2013-2015 zukunft.com AG, Zurich
Heang Lor <heang@zukunft.com>

http://tream.biz

 * This file is based on P4A - PHP For Applications.
 *
 * To contact the authors write to:                                     
 * Fabrizio Balliano <fabrizio@fabrizioballiano.it>                    
 * Andrea Giardina <andrea.giardina@crealabs.it>
 *
 * https://github.com/fballiano/p4a
 *
 * @author Timon Zielonka <timon@zukunft.com>
 * @copyright Copyright (c) 2013-2015 zukunft.com AG, Zurich

*/
class Recon_files extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;
	
	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

		$this->setSource($p4a->recon_files);
		$this->firstRow();

		$this->fields->file_path->setWidth(500);
		$this->fields->fixed_field_positions->setWidth(800);
		$this->fields->fixed_field_names->setWidth(800);
		$this->fields->comment->setWidth(500);

		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		/* usually a record does not need to be deleted */
		$this->toolbar->buttons->delete->disable();

		$this->build("p4a_table", "table")
			->setSource($p4a->recon_files)
			->setVisibleCols(array("file_name","file_path"))
			->setWidth(500)
			->showNavigationBar();

		$this->setRequiredField("file_name");

		$this->build('p4a_button','do_recon_now_btn')
			->implement("onclick", $this, "do_recon");;
		$this->do_recon_now_btn->setLabel('Start reconciliation');		
		
		$this->build("p4a_label", "recon_result"," ");

		$this->build("p4a_fieldset", "fs_details")
			->setLabel("Trade type detail")
			->anchor($this->fields->file_name)
			->anchor($this->fields->file_path)
			->anchor($this->fields->back_days)
			->anchor($this->fields->fixed_field_positions)
			->anchor($this->fields->fixed_field_names)
			->anchor($this->fields->last_run)
			->anchor($this->fields->comment);
		
		$this->frame
			->anchor($this->table)
 			->anchor($this->fs_details)
 			->anchor($this->do_recon_now_btn)
 			->anchorLeft($this->recon_result);
 			
		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->file_name);
	}

	public function do_recon()
	{
		$this->recon_result->setLabel(http_parse_message(http_get("https://192.168.2.3/crm/batch/tream_recon_file.php?file_id=3"))->body); 
		//$this->recon_result->setLabel("test"); 
		$this->fields->last_run->setNewValue(date("Y-m-d H:i:s"));
	}
}
