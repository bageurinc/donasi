<?php
Route::name('bageur.')->group(function () {
	Route::group(['prefix' => 'bageur/v1'], function () {
		Route::apiResource('campaign', 'bageur\donasi\CampaignController');
		Route::apiResource('lembaga', 'bageur\donasi\LembagaController');
		Route::apiResource('donatur', 'bageur\donasi\DonaturController');
		Route::apiResource('aktifitas', 'bageur\donasi\AktifitasController');
		// Route::apiResource('members', 'bageur\donasi\MembersController');
		Route::apiResource('penerima', 'bageur\donasi\PenerimaController');
	});
});
