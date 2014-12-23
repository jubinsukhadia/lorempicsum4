<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImageDownloadCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:pictures';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$downloadedPictures = array_flatten(Picture::where('downloaded_locally',1)->get(array('id','downloaded_locally'))->lists('id'));

		$pictures = Picture::where('downloaded_locally',0)->get(array('id','url','large_url','site','downloaded_locally'));

		$downloaded = $downloadedPictures;

		/* temp */
		$downloaded = array();
		$pictures = Picture::where('id','>','2117')->get();
		/* temp End*/

		$logFile = 'log-picturesDownloaded.txt';
		Log::useDailyFiles(storage_path().'/logs/'.$logFile);
		foreach($pictures as $picture){
			try{
				$ext = '.jpg';
				if(str_contains($picture->large_url,'.mp4')){
					$ext = '.mp4';
				}
				if(str_contains($picture->large_url,'.gif')){
					$ext = '.gif';
				}
				if(str_contains($picture->large_url,'.png')){
					$ext = '.png';
				}

				$filename = public_path().'/pictures/'.$picture->site.'/'.$picture->id.$ext;
				$filename_small = public_path().'/pictures/'.$picture->site.'/'.$picture->id.'_small'.$ext;
				if ($ext!='.mp4' && !File::exists($filename)){
					echo 'Big'.$picture->id;
					//file_put_contents($filename,file_get_contents($picture->large_url));

					$in = fopen($picture->large_url, "rb");
					$out = fopen($filename, 'w');

					while ( ! feof($in) ) {
						fwrite($out, fread($in, 8192));
					}

					echo 'Downloaded'.PHP_EOL;
					$downloaded[]= $picture->id;
					$lastDownloaded = $picture->id;


					Log::info('Picture Downloaded::'.$picture->id,$downloaded);
				}
				if (!File::exists($filename_small)){
					echo 'small'.$picture->id;
					//file_put_contents($filename_small,file_get_contents($picture->url));
					$in = fopen($picture->url, "rb");
					$out = fopen($filename_small, 'w');

					while ( ! feof($in) ) {
						fwrite($out, fread($in, 8192));
					}
					echo 'Downloaded _small'.PHP_EOL;
					$downloaded[]= $picture->id;
					$lastDownloaded = $picture->id;


					Log::info('Picture Downloaded::'.$picture->id,$downloaded);
				}
				$filename = null;
				$filename_small = null;
			}catch  (Exception $e) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
				Log::info('Last Picture Downloaded::'.$lastDownloaded,$downloaded);
				Picture::whereIn('id', $downloaded)->update(array('downloaded_locally' => 1));
			}

		}
		if(count($downloaded)>0){
			Picture::whereIn('id', $downloaded)->update(array('downloaded_locally' => 1));
		}
	}

}
