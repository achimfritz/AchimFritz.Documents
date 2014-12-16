<?php
namespace AchimFritz\Documents\Domain;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

interface FileSystemInterface {

	const IMAGE_MOUNT_POINT = '/bilder/main';
	const IMAGE_EXPORT = '/bilder/export';
	const IMAGE_THUMB_THUMB = 'thumbs/64x48';
	const IMAGE_THUMB_PREVIEW = 'thumbs/320x240';
	const IMAGE_THUMB_BIG = 'thumbs/800x600';
	const IMAGE_THUMB_WEB = 'thumbs/1280x1024';
	const MOUNT_POINT = '';
	const MP3_MOUNT_POINT = '/mp3/db';
	const MP3_WEB = 'mp3';
}
