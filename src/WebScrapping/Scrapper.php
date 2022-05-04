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
    //Find each article with xPath 
    $xPath = new DomXpath($dom);
    $domNodeList = $xPath->query('.//a[@class="paper-card p-lg bd-gradient-left"]');

    $rows = [];
    $authorsNumber = 0;

    //Gets the DOM data
    foreach($domNodeList as $article){
      $authors = [];

      $id = $article->childNodes[2]->childNodes[1]->nodeValue;
      $title = $article->childNodes[0]->nodeValue;
      $type = $article->childNodes[2]->childNodes[0]->nodeValue;

      foreach($article->childNodes[1]->childNodes as $span){
        if($span->nodeType == 1){
          array_push($authors, array(rtrim($span->nodeValue, ";"), $span->getAttribute("title")));
        }
      }

      if(count($authors) > $authorsNumber){
        $authorsNumber = count($authors);
      }

      array_push($rows, array($id, $title, $type, $authors));
    }

    //writes the data to xlsx file
    $writer = WriterEntityFactory::createXLSXWriter();
    $writer->openToFile(__DIR__ . '/../../webscrapping/model.xlsx');

    $titleCells = [
      WriterEntityFactory::createCell("ID"),
      WriterEntityFactory::createCell("Title"),
      WriterEntityFactory::createCell("Type"),
    ];

    for($i=1; $i<=$authorsNumber; $i++){
      array_push($titleCells, WriterEntityFactory::createCell("Author " . $i));
      array_push($titleCells, WriterEntityFactory::createCell("Author " . $i . " Institution"));
    }
      
    $singleRow = WriterEntityFactory::createRow($titleCells);
    $writer->addRow($singleRow);

    foreach($rows as $row){
      $cells = [];
      array_push($cells, WriterEntityFactory::createCell($row[0]));
      array_push($cells, WriterEntityFactory::createCell($row[1]));
      array_push($cells, WriterEntityFactory::createCell($row[2]));
      foreach($row[3] as $author){
        array_push($cells, WriterEntityFactory::createCell($author[0]));
        array_push($cells, WriterEntityFactory::createCell($author[1]));
      }

      $singleRow = WriterEntityFactory::createRow($cells);
      $writer->addRow($singleRow);
    }
    $writer->close();
  }

}
