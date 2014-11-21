# CSVDumper

Help you dump database into CSV format, built on Laravel.

## Usage

```php
// dump a table and store it into a file
$dumper = new CSVDumper($tableName);
$dumper->dumpAndStoreTable($folderPathToStore);
```
