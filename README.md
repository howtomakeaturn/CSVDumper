# CSVDumper

Help you dump database into CSV format, built on Laravel.

## Usage

Dump a table and store it into a file
```php
$dumper = new Howtomakeaturn\CSVDumper\CSVDumper($tableName);
$dumper->dumpAndStoreTable($folderPathToStore);
```

Get a CSV format string of a table
```php
$dumper = new Howtomakeaturn\CSVDumper\CSVDumper($tableName);
$result = $dumper->dumpTable();
```
