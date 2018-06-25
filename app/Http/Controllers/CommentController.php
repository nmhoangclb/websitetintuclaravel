<?php

namespace App\Http\Controllers;
use App\Comment;

class CommentController extends Controller {

	public function getXoa($id, $idTinTuc) {
		$comment = Comment::find($id);
		$comment->delete();

		return redirect('admin/tintuc/sua/' . $idTinTuc)->with('thongbao', 'Bạn đã xoá comment thành công');
	}
}
