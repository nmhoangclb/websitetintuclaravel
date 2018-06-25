<?php

namespace App\Http\Controllers;
use App\LoaiTin;
use App\TheLoai;
use App\TinTuc;
use Illuminate\Http\Request;

class TinTucController extends Controller {
	//
	public function getDanhSach() {
		$tintuc = TinTuc::orderBy('id', 'DESC')->get();
		return view('admin.tintuc.danhsach', ['tintuc' => $tintuc]);
	}

	public function getThem() {
		$theloai = Theloai::all();
		$loaitin = LoaiTin::all();

		return view('admin.tintuc.them', ['theloai' => $theloai, 'loaitin' => $loaitin]);
	}

	public function postThem(Request $request) {
		$this->validate($request,
			[
				'LoaiTin' => 'required',
				'TieuDe' => 'required|min:5|unique:TinTuc,TieuDe',
				'TomTat' => 'required',
				'NoiDung' => 'required',

			],
			[
				'LoaiTin.required' => 'Bạn chưa chọn loại tin.',
				'TieuDe.required' => 'Bạn chưa nhập tiêu đề',
				'TieuDe.min' => 'Bạn phải nhập tiêu đề từ 5 ký tự trở lên.',
				'TieuDe.unique' => 'Tiêu đề đã tồn tại',
				'TomTat.required' => 'Bạn chưa nhập tóm tắt',
				'NoiDung.required' => 'Bạn chưa nhập nội dung',

			]);

		$tintuc = new TinTuc;
		$tintuc->TieuDe = $request->TieuDe;
		$tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
		$tintuc->idLoaiTin = $request->LoaiTin;
		$tintuc->TomTat = $request->TomTat;
		$tintuc->NoiDung = $request->NoiDung;
		$tintuc->SoLuotXem = 0;

		if ($request->hasFile('Hinh')) {
			$file = $request->file('Hinh');
			$duoi = $file->getClientOriginalExtension();
			if ($duoi != 'jpg' && $duoi != 'png') {
				return redirect('admin/tintuc/them')->with('loi', 'Bạn chỉ được chọn file jpg,png.');
			}
			$name = $file->getClientOriginalName();
			$Hinh = str_random(5) . "_" . $name;
			while (file_exists("upload/tintuc/" . $Hinh)) {
				$Hinh = str_random(5) . "_" . $name;
			}
			$file->move("upload/tintuc", $Hinh);
			$tintuc->Hinh = $Hinh;

			//echo $Hinh;
		} else {
			$tintuc->Hinh = "default.png";
		}
		$tintuc->save();

		return redirect('admin/tintuc/them')->with('thongbao', 'Thêm tin tức thành công');
	}

	public function getSua($id) {
		$tintuc = TinTuc::find($id);
		$theloai = Theloai::all();
		$loaitin = LoaiTin::all();
		return view('admin.tintuc.sua', ['tintuc' => $tintuc, 'theloai' => $theloai, 'loaitin' => $loaitin]);

	}

	public function postSua(Request $request, $id) {
		$tintuc = TinTuc::find($id);
		$this->validate($request,
			[
				'LoaiTin' => 'required',
				'TieuDe' => 'required|min:5|unique:TinTuc,TieuDe',
				'TomTat' => 'required',
				'NoiDung' => 'required',

			],
			[
				'LoaiTin.required' => 'Bạn chưa chọn loại tin.',
				'TieuDe.required' => 'Bạn chưa nhập tiêu đề',
				'TieuDe.min' => 'Bạn phải nhập tiêu đề từ 5 ký tự trở lên.',
				'TieuDe.unique' => 'Tiêu đề đã tồn tại',
				'TomTat.required' => 'Bạn chưa nhập tóm tắt',
				'NoiDung.required' => 'Bạn chưa nhập nội dung',

			]);
		$tintuc->TieuDe = $request->TieuDe;
		$tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
		$tintuc->idLoaiTin = $request->LoaiTin;
		$tintuc->TomTat = $request->TomTat;
		$tintuc->NoiDung = $request->NoiDung;

		if ($request->hasFile('Hinh')) {
			$file = $request->file('Hinh');
			$duoi = $file->getClientOriginalExtension();
			if ($duoi != 'jpg' && $duoi != 'png') {
				return redirect('admin/tintuc/them')->with('loi', 'Bạn chỉ được chọn file jpg,png.');
			}
			$name = $file->getClientOriginalName();
			$Hinh = str_random(5) . "_" . $name;
			while (file_exists("upload/tintuc/" . $Hinh)) {
				$Hinh = str_random(5) . "_" . $name;
			}
			$file->move("upload/tintuc", $Hinh);
			unlink("upload/tintuc/" . $tintuc->Hinh);
			$tintuc->Hinh = $Hinh;

			//echo $Hinh;
		} else {
			//Khi không sửa hình thì không làm gì cả
		}
		$tintuc->save();

		return redirect('admin/tintuc/sua/' . $id)->with('thongbao', 'Sửa tin tức thành công');

	}

	public function getXoa($id) {
		$tintuc = TinTuc::find($id);
		$tintuc->delete();

		return redirect('admin/tintuc/danhsach')->with('thongbao', 'Xoá tin tức thành công');
	}
}
