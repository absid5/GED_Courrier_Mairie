<?php
/**
* Core class for status
*
*  Contains all the functions to manage status
*
* @package  maarch
* @version 3.0
* @since 10/2005
* @license GPL v3
* @author  Claire Figueras  <dev@maarch.org>
*
*/

class manage_status extends Database
{
	public $statusArr;

	function __construct()
	{
		parent::__construct();
		$this->statusArr = array();
		$this->get_status_data_array();
	}

	public function get_searchable_status()
	{
		$status = array();
		$stmt = $this->query("select id, label_status from "
			. $_SESSION['tablename']['status'] 
			. " where can_be_searched = 'Y'");
		while($res = $stmt->fetchObject())
		{
			array_push($status, array('ID' => $res->id, 'LABEL' => $res->label_status));
		}
		return $status;
	}

	public function get_not_searchable_status()
	{
		$status = array();
		$stmt = $this->query("select id, label_status from " 
			. $_SESSION['tablename']['status'] 
			. " where can_be_searched = 'N'");
		while($res = $stmt->fetchObject())
		{
			array_push($status, array('ID' => $res->id, 'LABEL' => $res->label_status));
		}
		return $status;
	}
	
	public function get_status_data_array()
	{
		$stmt = $this->query("select * from ".$_SESSION['tablename']['status']."");
		while($res = $stmt->fetchObject())
		{
			$id_status = $res->id;
			$status_txt = $this->show_string($res->label_status);
			$maarch_module = $res->maarch_module;
			$img_name = $res->img_filename;
			if(!empty($img_name))
			{
				//For big
				$big_temp_explode = explode( ".", $img_name);
				$big_temp_explode[0] = $big_temp_explode[0]."_big";
				$big_img_name = implode(".", $big_temp_explode);
			}
			if($maarch_module == 'apps' && isset($img_name) && !empty($img_name))
			{
				$img_path = $_SESSION['config']['businessappurl'].'static.php?filename='.$img_name;
				$big_img_path = $_SESSION['config']['businessappurl'].'static.php?filename='.$big_img_name;
			}
			else if(!empty($maarch_module) && isset($maarch_module)&& isset($img_name) && !empty($img_name))
			{
				$img_path = $_SESSION['config']['businessappurl'].'static.php?filename='.$img_name."&module=".$maarch_module;
				$big_img_path = $_SESSION['config']['businessappurl'].'static.php?filename='.$big_img_name."&module=".$maarch_module;
			}
			else
			{
				$img_path = $_SESSION['config']['businessappurl'].'static.php?filename=default_status.gif';
				$big_img_path = $_SESSION['config']['businessappurl'].'static.php?filename=default_status_big.gif';
			}
			if(empty($status_txt) || !isset($status_txt))
			{
				$status_txt = $id_status;
			}
			$img_path = $img_name;
			$big_img_path = $img_name;
			array_push($this->statusArr, array('ID' => $id_status, 'LABEL' => $status_txt, 'IMG_SRC' => $img_path , 'IMG_SRC_BIG' => $big_img_path));
		}
	}
	
	public function get_status_data($id_status, $extension = '')
	{
		for($cptStatusArr=0;$cptStatusArr<count($this->statusArr);$cptStatusArr++)
		{
			if($id_status == $this->statusArr[$cptStatusArr]['ID'])
			{
				$status_txt = $this->statusArr[$cptStatusArr]['LABEL'];
				if ($extension == "_big")
					$img_path = $this->statusArr[$cptStatusArr]['IMG_SRC_BIG'];
				else
					$img_path = $this->statusArr[$cptStatusArr]['IMG_SRC'];
			}
		}
		return array('ID'=> $id_status, 'LABEL'=> $status_txt, 'IMG_SRC' => $img_path);
	}

	public function can_be_modified($id_status)
	{
		$stmt = $this->query("select can_be_modified from " 
			. $_SESSION['tablename']['status'] 
			. " where id = ?", array($id_status));
		if($stmt->rowCount() == 0)
		{
			return false;
		}
		$res = $stmt->fetchObject();
		if($res->can_be_modified == 'N')
		{
			return false;
		}
		return true;
	}
}
