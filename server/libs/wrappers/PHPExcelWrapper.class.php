<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/server/libs/PHPExcel/Classes/PHPExcel.php";

/**
 * Class that wraps many functionalities of PHPExcel for ease of use
 */
class PHPExcelWrapper
{
	const DEFAULT_COLUMN_CHARACTER = 'A';
	const DEFAULT_ROW_NUMBER = 1;
	const MAX_SHEET_NAME_LENGTH = 31; # Excel puts a limit on the name of a sheet to 31 characters	
	
	private $objPHPExcel;
	private $nextSheetIndex = 0; 

	private $currentSheetIndex = 0;
	private $rowNumberForSheetIndex = array();
	private $columnCharacterForSheetIndex = array();

    public function __construct()
    {
		$this->objPHPExcel = new PHPExcel();
		# Remove the default sheet
		$this->objPHPExcel->setActiveSheetIndexByName('Worksheet'); 
		$this->objPHPExcel->removeSheetByIndex($this->objPHPExcel->getActiveSheetIndex());
    }

	/**
	* Returns the newly created sheet's index
	* 
	* @param title The title of the new sheet
	* @return int
	*/ 
	public function createSheet($title) {
		$this->objPHPExcel->createSheet();
		$this->objPHPExcel->setActiveSheetIndex($this->nextSheetIndex);
		$activeSheet = $this->objPHPExcel->getActiveSheet();
		$trimmedTitle = mb_strimwidth($title, 0, self::MAX_SHEET_NAME_LENGTH, '...'); 
		$activeSheet->setTitle($trimmedTitle);

		$this->objPHPExcel->setActiveSheetIndex($this->currentSheetIndex); # Return to previous sheet		

		$this->rowNumberForSheetIndex[$this->nextSheetIndex] = self::DEFAULT_ROW_NUMBER;
		$this->columnCharacterForSheetIndex[$this->nextSheetIndex] = self::DEFAULT_COLUMN_CHARACTER;
		$this->nextSheetIndex++;

		return ($this->nextSheetIndex - 1); # The index for the new sheet is -1 the new nextSheetIndex
	}

	/**
	* Inserts a header row with the provided header names.

	* @param headerColumnNames An array containing the header column names
	* @return void
	*/ 
	public function insertHeaderRow($headerColumnNames) {
		$activeSheet = $this->objPHPExcel->getActiveSheet();

		$rowNumber = $this->rowNumberForSheetIndex[$this->currentSheetIndex];
		$columnCharacter = $this->columnCharacterForSheetIndex[$this->currentSheetIndex];
		foreach ($headerColumnNames as $headerName) {
			$activeSheet->setCellValue("$columnCharacter$rowNumber", $headerName);
			$activeSheet->getColumnDimension("$columnCharacter")->setAutoSize(true);
			$columnCharacter++;
		}
	}

	/**
	 * Sets the active sheet index
	 * 
	 * @param sheetIndex The index to set as the active sheet
	 * @return void
	 */
	public function setActiveSheetIndex($sheetIndex) {
		$this->currentSheetIndex = $sheetIndex;
		$this->objPHPExcel->setActiveSheetIndex($this->currentSheetIndex);		
	}

	/**
	 * Shifts to the next row and resets column character of the current sheet
	 * 
	 * @return void
	 */
	public function nextRow() {
		$this->rowNumberForSheetIndex[$this->currentSheetIndex]++;
		$this->columnCharacterForSheetIndex[$this->currentSheetIndex] = self::DEFAULT_COLUMN_CHARACTER;
	}

	/**
	 * Sets the row number of the current sheet
	 * 
	 * @param rowNumber The row number
	 * @return void
	 */
	public function setRow($rowNumber) {
		$this->rowNumberForSheetIndex[$this->currentSheetIndex] = $rowNumber;
	}

	/**
	 * Shifts to the next column of the current sheet
	 * 
	 * @return void
	 */
	public function nextColumn() {
		$this->columnCharacterForSheetIndex[$this->currentSheetIndex]++;
	}

	/**
	 * Sets the column character for the current sheet
	 * 
	 * @param columnCharacter The character for the column
	 * @return void
	 */
	public function setColumn($columnCharacter) {
		$this->columnCharacterForSheetIndex[$this->currentSheetIndex] = $columnCharacter;
	}

	/**
	 * Inserts the given data into the current sheets current row and column cell
	 * 
	 * @param data The data
	 * @return void
	 */
	public function insertData($data) {
		$activeSheet = $this->objPHPExcel->getActiveSheet();

		$rowNumber = $this->rowNumberForSheetIndex[$this->currentSheetIndex];
		$columnCharacter = $this->columnCharacterForSheetIndex[$this->currentSheetIndex];

		$activeSheet->setCellValue("$columnCharacter$rowNumber", $data);
	}

	/**
	* Returns a writer for this PHP Excel object
	* 
	* @param excelFileType The file type to write, default is 'Excel2007'
	* @return PHPExcelWriter
	*/ 
	public function createExcelWriter($excelFileType = 'Excel2007') {
		return PHPExcel_IOFactory::createWriter($this->objPHPExcel, $excelFileType);		
	}
}