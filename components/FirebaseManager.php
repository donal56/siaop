<?php
namespace app\components;

use Google\Cloud\Storage\StorageClient;

class FirebaseManager {

    // El primer bucket es el activo
    private static $buckets = [
        [
            'bucketName' => 'siaop-e20f1.appspot.com',
            'keyFilePath' => 'siaop-e20f1-c5ef38105926.json',
            'projectId' => 'siaop-e20f1',
        ]
    ];
    private static $urlPrefix = 'https://storage.googleapis.com/';

    public static function upload($objectPath, $file) {
		$bucket = self::getActiveBucket();
        $object = $bucket->upload($file, ['name' => $objectPath]);
		$object->update(['acl' => []], ['predefinedAcl' => 'PUBLICREAD']);
		return self::$urlPrefix . $bucket->name() . "/{$objectPath}";
	}

    /**
     * Actualmente no funciona
     */
    public static function delete($url) {

        $exito = false;

        foreach(self::$buckets as $index => $bucket) {
            if($exito === false) {
                try {
                    $bucket = self::getBucket($index);
                    $name = str_replace(self::$urlPrefix . $bucket->name() . '/', '', $url);
                    $object = $bucket->object($name);
                    $object->delete();
                    $exito = true;
                }
                catch(\Exception $e) {
                    $exito = false;
                }
            }
        }

        return $exito;
    }

    private static function getActiveBucket() {
        return self::getBucket(0);
    }
    
    private static function getBucket($index) {
        return (new StorageClient([
			'keyFilePath' => self::$buckets[$index]['keyFilePath'],
			'projectId' => self::$buckets[$index]['projectId']
		]))
        ->bucket(self::$buckets[$index]['bucketName']);
    }
}