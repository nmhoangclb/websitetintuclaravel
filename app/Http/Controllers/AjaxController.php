<?php

namespace App\Http\Controllers;
use App\LoaiTin;

class AjaxController extends Controller {
	public function getLoaiTin($idTheLoai) {
		$loaitin = LoaiTin::where('idTheLoai', $idTheLoai)->get();
		foreach ($loaitin as $lt) {
			echo "<option value='" . $lt->id . "'>" . $lt->Ten . "</option>";
		}
	}
}
