<?php
	class rssFeedWriter
	{
        var $xmlWriter;
        var $xmlString = "";
        var $openElements = 0;

        public function rssFeedWriter ($outputFilePath) {
            $this->xmlWriter = new XMLWriter();
            $this->xmlWriter->openUri($outputFilePath);
            $this->xmlWriter->setIndent(true);
        }

        private function incrementOpenElements() {
            $this->openElements++;
        }

        private function appendElementToXml ($name, $value) {
            $this->xmlWriter->startElement($name);
            $this->xmlWriter->text($value);
            $this->xmlWriter->endElement();
        }

        private function appendAttributeToXml ($name, $value) {
            $this->xmlWriter->startAttribute($name);
            $this->xmlWriter->text($value);
            $this->xmlWriter->endAttribute();
        }

        public function openRssFeed ($channelTitle, $channelUrl) {
            $this->xmlWriter->startDocument("1.0");
            $this->xmlWriter->startElement("rss");
            $this->incrementOpenElements();
            $this->appendAttributeToXml("version", "2.0");
            $this->xmlWriter->startElement("channel");
            $this->incrementOpenElements();
            $this->appendElementToXml("title", $channelTitle);
            $this->appendElementToXml("link", $channelUrl);
        }

        public function closeRssFeed () {
            $this->closeAllOpenElements();
            $this->xmlWriter->endDocument();
        }

        public function writeRssFeed () {
            $this->xmlWriter->flush();
        }

        private function closeAllOpenElements () {
            for ($i = 0; $i < $this->openElements; $i++) {
                $this->xmlWriter->endElement();
            }
            $openElements = 0;
        }

        public function addItem ($title, $link, $date) {
            $this->xmlWriter->startElement("item");
            $this->addItemTitle($title);
            $this->addItemPublicationDate($date);
            $this->addItemLink($link);
            $this->addItemGuid($link);
            $this->addItemEnclosure($link);
            $this->xmlWriter->endElement();
        }

        private function addItemTitle ($title) {
            $this->appendElementToXml("title", $title);
        }

        private function addItemLink ($link) {
            $this->appendElementToXml("link", $link);
        }

        private function addItemGuid ($link) {
            $this->appendElementToXml("guid", $link);
        }

        private function addItemEnclosure ($link) {
            $this->xmlWriter->startElement("enclosure");
            $this->addEnclosureUrl($link);
            $this->xmlWriter->endElement();
        }

        private function addEnclosureUrl ($link) {
            $this->appendAttributeToXml("url", $link);
        }

        private function addItemPublicationDate ($link) {
            $this->appendElementToXml("pubDate", $link);
        }


	}
