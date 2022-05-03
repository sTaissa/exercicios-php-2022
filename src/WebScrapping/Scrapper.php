<?php

namespace Galoa\ExerciciosPhp2022\WebScrapping;

use DOMXPath;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Common\Entity\Row;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and creates a XLSX file.
   */
  public function scrap(\DOMDocument $dom): void {
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile(__DIR__ . '/../../webscrapping/result.xlsx');

    /** 
     * Find each article with xPath 
     * */
    $xPath = new DomXpath($dom);
    $domNodeList = $xPath->query('.//a[@class="paper-card p-lg bd-gradient-left"]');

    /** 
     * Adds each element of the article to a worksheet cell 
     * */
    foreach($domNodeList as $article){
      $cells = [];
      $child = $article->childNodes;

      foreach($child as $childElement){
        if($childElement->nodeName == "h4"){
          $title = $childElement->textContent;
          
          array_push($cells, WriterEntityFactory::createCell($title));
        }
      }
      $singleRow = WriterEntityFactory::createRow($cells);
      $writer->addRow($singleRow);
    }
    $writer->close();
  }

}
