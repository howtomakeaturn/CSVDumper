<?php
namespace Howtomakeaturn\CSVDumper;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CSVDumper{
    
    protected $tableName;
    protected $doctrineSchemaManager;
    protected $queryBuilder;
    
    public function __construct($tableName)
    {
        $this->tableName = $tableName;
        $this->doctrineSchemaManager = Schema::getConnection()->getDoctrineSchemaManager();
        $this->queryBuilder = DB::table($tableName);
    }
    
    public function dumpColumnNames(){
        $columnNames = array_map( function($e){
                return $e->getName();
            },
            $this->doctrineSchemaManager->listTableColumns($this->tableName)
        );
        return implode($columnNames, ',');
    }
    
    public function rowsCount(){
        return $this->queryBuilder->count();
    }
    
    /*
     * Thanks for http://stackoverflow.com/questions/769621/dealing-with-commas-in-a-csv-file
     * 
     * "Fields containing line breaks (CRLF), double quotes, and commas should be enclosed in double-quotes." 
     * "If double-quotes are used to enclose fields, then a double-quote appearing inside a field must be escaped by preceding it with another double quote."
     */
    public function dumpRows($lineSeparator = "\n")
    {
        $result = '';
        $rows = $this->queryBuilder->get();
        foreach($rows as $row){
            $arrayFormat = json_decode( json_encode($row), true);
            foreach($arrayFormat as $index => $value){
                $arrayFormat[$index] = '"' . str_replace( '"', '\"', $value) . '"';
            }            
            $result = $result . implode($arrayFormat, ',') . $lineSeparator;
        }
        return $result;
    }
    
    public function dumpTable($timestamp = false){
        $result = '';
        $result = $this->dumpColumnNames() . "\n";
        $result = $result . $this->dumpRows();
        if ($timestamp){
            $result = $result . "created at " . date('c') . "\n";
        }
        return $result;
    }
    
    public function getTableName(){
        return $this->tableName;
    }
    
    public function dumpAndStoreTable($path)
    {
        $result = File::put($path . '/' . $this->getTableName() . '.csv', $this->dumpTable(true));
        return $result;        
    }
    
}
