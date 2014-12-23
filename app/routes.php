<?php

	Route::get('/', function () {
		return View::make('hello');
	});


	// Confide routes
	Route::get('users/create', 'UsersController@create');
	Route::post('users', 'UsersController@store');
	Route::get('users/login', 'UsersController@login');
	Route::post('users/login', 'UsersController@doLogin');
	Route::get('users/confirm/{code}', 'UsersController@confirm');
	Route::get('users/forgot_password', 'UsersController@forgotPassword');
	Route::post('users/forgot_password', 'UsersController@doForgotPassword');
	Route::get('users/reset_password/{token}', 'UsersController@resetPassword');
	Route::post('users/reset_password', 'UsersController@doResetPassword');
	Route::get('users/logout', 'UsersController@logout');



	Route::get('/{width}/{height}/{options?}', function($width = 300, $height = 200, $options = null)
	{
		$params = array();
		if(isset($options)){
			foreach (explode('/',$options) as $pair)
			{
				list ($k,$v) = explode (':',$pair);
				$params[$k] = $v;
			}
		}


		$img = Image::cache(function($image) use ($width,$height,$params) {

			$file = 'foo.jpg';
			if(isset($params['id']) && is_int(intval($params['id']))){
				$picture = Picture::find($params['id']);
				$file = 'pictures/'.$picture->site.'/'.$picture->id.'_small.jpg';
			}
			$newImg = $image->make($file)->fit($width, $height);

			$newImg->interlace();

			if(isset($params['grey']) && $params['grey']=='yes'){
				$newImg->greyscale();
			}

			if(isset($params['blur'])){
				$blurOptions = explode ('|',$params['blur']);
				if($blurOptions[0]=='yes'){
					if(isset($blurOptions[1]) && is_int(intval($blurOptions[1]))){
						$blur = intval($blurOptions[1]);
					}else{
						$blur = 1;
					}
					$newImg->blur($blur);
				}
			}
			if(isset($params['sharpen'])){
				$sharpenOptions = explode ('|',$params['sharpen']);
				if($sharpenOptions[0]=='yes'){
					if(isset($sharpenOptions[1]) && is_int(intval($sharpenOptions[1]))){
						$sharpen = intval($sharpenOptions[1]);
					}else{
						$sharpen = 10;
					}
					$newImg->sharpen($sharpen);
				}
			}

			if(isset($params['pixelate'])){
				$pixelateOptions = explode ('|',$params['pixelate']);
				if($pixelateOptions[0]=='yes'){
					if(isset($pixelateOptions[1]) && is_int(intval($pixelateOptions[1]))){
						$pixelate = intval($pixelateOptions[1]);
					}else{
						$pixelate = 2;
					}
					$newImg->pixelate($pixelate);
				}
			}

			if(isset($params['invert']) && $params['invert']=='yes'){
				$newImg->invert();
			}
			return $newImg;

		});


		return Response::make($img,200,array('Content-Type'=>'image/jpeg'));

	})->where(['width' => '[0-9]+', 'height' => '[0-9]+', 'options' => '.*']);


	Route::get('/temp', function () {
		$pictureCount = Picture::find(1);
		print_r($pictureCount);
	});