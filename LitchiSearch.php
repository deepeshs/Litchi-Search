<?php
/**
 * LitchiSearch
 * This is a simple command line utility to search text in all files of given folder. It expects two parameters search text and folder path.
 * If folder path is not given then it will search on current folder. 
 * @example php LitchiSearch.php sometext somepath
 * @author Deepesh S <techfiddler.wordpress.com>
 */

class LitchiSearch {
    private $m_strSearchText;
    private $m_strDirectory;
    private $m_arrstrResults;
    
    function __construct($strSearchText, $strDirectory) {
        $this->m_strSearchText  = $strSearchText;
        $this->m_strDirectory   = $strDirectory;
    }
    
    function search() {
        $objDir = new DirectoryIterator($this->m_strDirectory);

        foreach($objDir as $objFile) {
            //skip "." and ".." files and skip if its not a file type
            if($objFile->isDot() || false === $objFile->isFile()) continue;
            
            $intPosition = strpos(file_get_contents($objFile->getPathname()), $this->m_strSearchText);
            if(false !== $intPosition) {
                $this->m_arrstrResults[] = array('file' => $objFile->getFilename(), 'position' => $intPosition);
            }
        }

        return $this->m_arrstrResults;
    }
}

//Execution and Display of Results
if(false === isset($argv[1]) || true === is_null($argv[1])) {
    echo "Please enter some text to be searched as first parameter.";
    exit(0);
}

$objLitchiSearch = new LitchiSearch($argv[1], ((true === isset($argv[2]))?$argv[2]:getcwd()));
$arrstrResults = $objLitchiSearch->search();

if(true === is_null($arrstrResults)) {
    echo "No Results Found";
    exit(0);
}

foreach($arrstrResults as $arrstrResult) {
    echo "Found in " . $arrstrResult['file'] . " at " . $arrstrResult['position'] . PHP_EOL;
}
