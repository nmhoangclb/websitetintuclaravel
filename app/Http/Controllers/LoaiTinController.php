<?php

namespace App\Http\Controllers;
use App\LoaiTin;
use App\TheLoai;
use App\TinTuc;
use Illuminate\Http\Request;

class LoaiTinController extends Controller {
	//
	public function getDanhSach() {
		$loaitin = LoaiTin::all();
		return view('admin.loaitin.danhsach', ['loaitin' => $loaitin]);
	}

	public function getThem() {
		$theloai = TheLoai::all();
		return view('admin.loaitin.them', ['theloai' => $theloai]);
	}

	public function postThem(Request $request) {
		//echo $request->Ten; Kiểm tra thử
		$this->validate($request,
			[
				'Ten' => 'required|unique:LoaiTin,Ten|min:3|max:100',
				'TheLoai' => 'required',

			],
			[
				'Ten.required' => 'Bạn chưa nhập tên loại tin.',
				'Ten.unique' => 'Tên loại tin đã tồn tại.',
				'Ten.min' => 'Tên loại tin có độ dài từ  đến 100 ký tự.',
				'Ten.min' => 'Tên loại tin có độ dài từ  đến 100 ký tự.',
				'TheLoai.required' => 'Bạn chưa chọn thể loại.',

			]);
		$loaitin = new LoaiTin;
		$loaitin->Ten = $request->Ten;
		$loaitin->TenKhongDau = changeTitle($request->Ten);
		$loaitin->idTheLoai = $request->TheLoai;
		$loaitin->save();

		return redirect('admin/loaitin/them')->with('thongbao', 'Thêm thành công');

	}

	public function getSua($id) {
		$theloai = TheLoai::all();
		$loaitin = LoaiTin::find($id);

		return view('admin.loaitin.sua', ['loaitin' => $loaitin, 'theloai' => $theloai]);
	}

	public function postSua(Request $request, $id) {
		$this->validate($request,
			[
				'Ten' => 'required|unique:LoaiTin,Ten|min:3|max:100',
				'TheLoai' => 'required',

			],
			[
				'Ten.required' => 'Bạn chưa nhập tên loại tin.',
				'Ten.unique' => 'Tên loại tin đã tồn tại.',
				'Ten.min' => 'Tên loại tin có độ dài từ  đến 100 ký tự.',
				'Ten.min' => 'Tên loại tin có độ dài từ  đến 100 ký tự.',
				'TheLoai.required' => 'Bạn chưa chọn thể loại.',

			]);
		$loaitin = LoaiTin::find($id);
		$loaitin->Ten = $request->Ten;
		$loaitin->TenKhongDau = changeTitle($request->Ten);
		$loaitin->idTheLoai = $request->TheLoai;
		$loaitin->save();

		return redirect('admin/loaitin/sua/' . $id)->with('thongbao', 'Sửa thành công');
	}

	public function getXoa($id) {
		//Kiểm tra làm sao vậy a
		$tintuc = new TinTuc;
		$tin = $tintuc->select('*')->where('idLoaiTin', $id)->get();
		if (count($tin) == 0) {
			$loaitin = LoaiTin::find($id)->delete();
			return redirect('admin/loaitin/danhsach')->with('thongbao', 'Xoá thành công');
		} else {
			return redirect('admin/loaitin/danhsach')->with('thongbaoloi', 'Không được phép xoá loại tin này!');
		}

	}
}
