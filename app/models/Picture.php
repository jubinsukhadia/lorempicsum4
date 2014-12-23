<?php

	use LaravelBook\Ardent\Ardent;

	class Picture extends Ardent {

		use SoftDeletingTrait;

		protected $dates = ['deleted_at'];

		protected $softDelete = true;

		public $autoPurgeRedundantAttributes = true;

		protected $fillable = ['name', 'slug', 'description', 'views', 'likes', 'approved', 'allow_download', 'downloads', 'url', 'large_url', 'source_id', 'copyright', 'site', 'width', 'height', 'downloaded_locally', 'type'];

		public static $rules = [

		];
	}