<?php
require 'utils.php';
require 'xmlwriter.php';

define("XmlFilePath", getRelativeRssFilePath());
define("DownloadFolderPath", getRelativeDownloadFolderPath());
define("RssFeedTitle", "y-dl Rss Feed");
define("RssFeedIcon", "img/icon.png");

generateRssFeedFile();
printRssFeedFile();

function generateRssFeedFile () {

    $downloads = prepareRssItems(getRelativeDownloadFolderPath());
    $scriptPathRelativeUrl = calculateScriptPathUrl();
    createRssFile($scriptPathRelativeUrl, $downloads);
}

function getAllDownloadedFileNames ( $directoryPath ) {
    $directoryEntries = glob($directoryPath."/*");
    $allDownloadedFiles = array_filter($directoryEntries, "is_file");
    return $allDownloadedFiles;
}

function calculateScriptPathUrl () {
    $docRootPath = getDocumentRootPath();
    $absoluteScriptPath = getAbsoluteScriptPath();
    $relativeScriptPath = calculateRelativeScriptPath($docRootPath, $absoluteScriptPath);
    $url = getServerUrl();

    return $url . $relativeScriptPath;
}

function getDocumentRootPath() {
    return $_SERVER["DOCUMENT_ROOT"];
}

function getAbsoluteScriptPath() {
    return dirname(__FILE__);
}

function calculateRelativeScriptPath ($docRootPath, $absoluteScriptPath) {
    $scriptRelativePath = str_replace($docRootPath, '', $absoluteScriptPath);
    return $scriptRelativePath;
}

function getServerUrl() {
    $url = getHttpProtocol() . $_SERVER["SERVER_NAME"];
    return $url;
}

function getHttpProtocol() {
    if( httpsIsActive() ) {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }
    return $protocol;
}

function httpsIsActive() { 
   return (isset($_SERVER["HTTPS"]) &&
        ( $_SERVER["HTTPS"] == "on" || $_SERVER["HTTPS"] == 1 ) );
}

function prepareRssItems($downloadFolder){
    $allDownloadedFiles = getAllDownloadedFileNames($downloadFolder);
    $allDownloads = convertDownloadedFileNamesToDownloads($allDownloadedFiles, $downloadFolder);

    return $allDownloads;
}

function convertDownloadedFileNamesToDownloads($allDownloads, $pathPrefix) {
    $scriptPathRelativeUrl = calculateScriptPathUrl();
    //$allDownloadedFilesWithAbsolutePaths = convertDlFilenamesToUrls($allDownloadedFiles, $scriptPathRelativeUrl);

    $downloads = array();

    foreach($allDownloads as $download){
        $name = extractReadableNames($download, $pathPrefix);
        $url = addRelativeScriptUrlToFileName($scriptPathRelativeUrl, $download);
        $date = getDateOfLastModification($download);

        $downloads[] = new Download($name, $url, $date);
    }

    return $downloads;
}

function extractReadableNames ($downloadedFile, $filePathPrefix) {
    $name = removePathPrefix($downloadedFile, $filePathPrefix);
    $name = underscoresToWhitespaces($name);

    return $name;
}

function convertDlFilenamesToUrls ($allDownloadedFiles, $scriptPathRelativeUrl) {
    foreach( $allDownloadedFiles as &$file ) {
        $file = addRelativeScriptUrlToFileName($scriptPathRelativeUrl, $file);
    }
    return $allDownloadedFiles;
}

function addRelativeScriptUrlToFileName ($scriptPathRelativeUrl, $filename) {
    return ($scriptPathRelativeUrl . '/' . $filename);
}

function removePathPrefix($file, $prefix) {
    $name = str_replace($prefix.'/', '', $file);
    return $name;
}

function underscoresToWhitespaces($name) {
    $name = str_replace('_', ' ', $name);
    return $name;
}

function getDateOfLastModification($filePath) {
    $timestamp = filemtime($filePath);
    $date = timestampToFormattedDate($timestamp, 'r'); //'r' = RFC 2822 compliant date format for publication date
    return $date;
}

function timestampToFormattedDate($timestamp, $format) {
    return date($format, $timestamp);
}

function createRssFile($scriptPathRelativeUrl, $allDownloadedFilesWithAbsolutePaths) {
    $feedWriter = new rssFeedWriter(XmlFilePath);
    $feedWriter->openRssFeed(RssFeedTitle, $scriptPathRelativeUrl);

    if(defined('RssFeedIcon') && !empty(RssFeedIcon)) {
        $feedWriter->setIcon(addRelativeScriptUrlToFileName($scriptPathRelativeUrl, RssFeedIcon),
            RssFeedTitle,
            $scriptPathRelativeUrl);
    }

    insertDownloadedFilesAsFeedItems($feedWriter, $allDownloadedFilesWithAbsolutePaths);

    $feedWriter->closeRssFeed();
    $feedWriter->writeRssFeed();
}

function insertDownloadedFilesAsFeedItems($feedWriter, $downloads) {

    foreach($downloads as $item) {
       $feedWriter->addItem($item->getName(), $item->getUrl(), $item->getDate());
    }
}

function printRssFeedFile(){
    readfile(XmlFilePath);
}

class Download
{
    var $url;
    var $name;
    var $date;

    public function Download($name, $url, $date) {
        $this->url = $url;
        $this->name = $name;
        $this->date = $date;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getName() {
        return $this->name;
    }

    public function getDate() {
        return $this->date;
    }
}
?>
