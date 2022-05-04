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
    $writer->openToFile(__DIR__ . '/../../webscrapping/model.xlsx');

    //Find each article with xPath 
    $xPath = new DomXpath($dom);
    $domNodeList = $xPath->query('.//a[@class="paper-card p-lg bd-gradient-left"]');

    //Adds each element of the article to a worksheet cell 
    foreach($domNodeList as $article){
      $authors = [];

      $id = $article->childNodes[2]->childNodes[1]->nodeValue;
      $title = $article->childNodes[0]->nodeValue;
      $type = $article->childNodes[2]->childNodes[0]->nodeValue;

      foreach($article->childNodes[1]->childNodes as $span){
        if($span->nodeType == 1){
          array_push($authors, array($span->nodeValue, $span->getAttribute("title")));
        }
      }

      $cells = [
        WriterEntityFactory::createCell($id),
        WriterEntityFactory::createCell($title),
        WriterEntityFactory::createCell($type)
      ];

      for($i=0; $i<count($authors); $i++){
        array_push($cells, WriterEntityFactory::createCell($authors[$i][0]));
        array_push($cells, WriterEntityFactory::createCell($authors[$i][1]));
      }

      $singleRow = WriterEntityFactory::createRow($cells);
      $writer->addRow($singleRow);
    }
    $writer->close();
  }

}
