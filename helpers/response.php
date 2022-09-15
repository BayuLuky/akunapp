<?php

function validatorMsg()
{
	$messages = [
		'required' => 'Form <b style="text-transform: uppercase;">:attribute</b> tidak boleh kosong',
		'min' => 'Karakter <b style="text-transform: uppercase;">:attribute</b> minimal <b style="text-transform: uppercase;">:min</b>',
		'unique' => '<b style="text-transform: uppercase;">:attribute</b> sudah ada'
	];

	return $messages;
}

function setResponse($code, $data = null, $msg = null, $status = null)
{
	switch ($code) {
		case '200':
			$s = 'OK';
			$m = 'Sukses';
			break;
		case '202':
			$s = 'Saved';
			$m = 'Data berhasil disimpan';
			break;
		case '204':
			$s = 'No Content';
			$m = 'Data tidak ditemukan';
			break;
		case '304':
			$s = 'Not Modified';
			$m = 'Data gagal disimpan';
			break;
		case '400':
			$s = 'Bad Request';
			$m = 'Fungsi tidak ditemukan';
			break;
		case '401':
			$s = 'Unauthorized';
			$m = 'Silahkan login terlebih dahulu';
			break;
		case '403':
			$s = 'Forbidden';
			$m = 'Sesi anda telah berakhir';
			break;
		case '404':
			$s = 'Not Found';
			$m = 'Halaman atau data tidak ditemukan';
			break;
		case '406':
			$s = 'Not Acceptable';
			$m = 'Proses ditolak';
			break;
		case '413':
			$s = 'Cannot Proceed';
			$m = 'Proses dibatalkan';
			break;
		case '414':
			$s = 'Request URI Too Long';
			$m = 'Data yang dikirim terlalu panjang';
			break;
		case '500':
			$s = 'Internal Server Error';
			$m = 'Maaf, terjadi kesalahan dalam mengolah data';
			break;
		case '502':
			$s = 'Bad Gateway';
			$m = 'Tidak dapat terhubung ke server';
			break;
		case '503':
			$s = 'Service Unavailable';
			$m = 'Server tidak dapat diakses';
			break;
		default:
			$s = 'Undefined';
			$m = 'Undefined';
			break;
	}

	$status = ($status) ? $status : $s;
	$msg = ($msg) ? $msg : $m;

	$result = [
		'title' => $status,
		'msg' => $msg,
		'type' => ($code == 200) ? 'success' : 'error',
		'code' => $code,
		'data' => $data
	];

	return response()->json($result, $code);
}
