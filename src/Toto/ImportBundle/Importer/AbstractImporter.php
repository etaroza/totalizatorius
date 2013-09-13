<?php
namespace Toto\ImportBundle\Importer;


abstract class AbstractImporter
{
    protected $invalidateCacheTime = 180; // 3 min
    
    /**
     * @var string
     */
    protected $cacheDir;

    protected function curlGet($url)
    {
        $file = $this->getCacheFile($url);

        // download data
        if (!file_exists($file)) {
            $fp = fopen ($file, 'w+');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
            curl_exec($ch); // get curl response
            curl_close($ch);
            fclose($fp);
        } 

        return file_get_contents($file);
    }

    protected function getCacheFile($url)
    {
        $hash = sha1($url);
        $file = $this->cacheDir . '/imp_' . $hash . '.tmp';
        $dir = dirname($file);
        
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        // cleanup oldies
        if (file_exists($file) && (time() - filemtime($file)) > $this->invalidateCacheTime) {
            unlink($file);
        }

        return $file;
    }
}