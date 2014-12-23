<?php


class PicturesTableSeeder extends Seeder {

	public function run()
	{
		$base_url = 'http://www.splashbase.co/api/v1/images/';
		$pictureCount = Picture::all()->count();
		if ($pictureCount > 0) {
			$pics = Picture::orderby('id', 'desc')->first();
			$pictureCount = $pics->source_id;
		}
		$continue = true;
		$continueStack = 0;
		$number = $pictureCount + 1;
		while ($continue) {
			$response = Buzz::get($base_url . $number);

			$responseArr = json_decode($response->getContent());

			if (!isset($responseArr->message)) {
				$width = null;
				$height = null;
				$picture = array(
					'url'       => $responseArr->url,
					'large_url' => $responseArr->large_url,
					'source_id' => $number,
					'copyright' => $responseArr->copyright,
					'site'      => $responseArr->site,
					'width'     => $width,
					'height'    => $height
				);
				if ($responseArr->url != "" || $responseArr->large_url != "") {
					if($responseArr->large_url == "")
					{
						$picture['large_url'] = $responseArr->url;
					}
					Picture::create($picture);
				}

			} else {
				Log::info('This path does not exist::'.$number);
				$continueStack ++;
				if ($continueStack > 30) {
					echo "exiting";
					Log::info('Exiting\n');
					$continue = false;
				}
			}
			$response = null;
			$responseArr = null;
			$width = null;
			$height = null;
			$picture = null;
			$number ++;
		}
	}

}