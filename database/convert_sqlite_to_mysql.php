<?php
/**
 * SQLite to MySQL Converter for LMS
 * 
 * Reads the local SQLite database and generates a MySQL-compatible SQL file.
 * Run: php database/convert_sqlite_to_mysql.php
 */

$dbPath = __DIR__ . '/database.sqlite';
$outputPath = __DIR__ . '/server_full_import.sql';

if (!file_exists($dbPath)) {
    die("SQLite database not found at: $dbPath\n");
}

$db = new SQLite3($dbPath);
$db->busyTimeout(5000);
$db->exec('PRAGMA journal_mode = WAL');

$out = fopen($outputPath, 'w');
if (!$out) {
    die("Cannot write to: $outputPath\n");
}

function w($sql = '') {
    global $out;
    fwrite($out, $sql . "\n");
}

// ============================================================
// Header
// ============================================================
w('-- ============================================================');
w('-- LMS Full Database Import (MySQL)');
w('-- Generated from local SQLite database');
w('-- Date: ' . date('Y-m-d H:i:s'));
w('-- ============================================================');
w('');
w('SET NAMES utf8mb4;');
w('SET FOREIGN_KEY_CHECKS = 0;');
w('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";');
w('SET AUTOCOMMIT = 0;');
w('START TRANSACTION;');
w('');

// ============================================================
// Get all tables
// ============================================================
$allTables = [];
$res = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $allTables[] = $row['name'];
}

// ============================================================
// Convert SQLite type to MySQL
// ============================================================
function convertType($sqliteType) {
    $upper = strtoupper(trim($sqliteType));
    
    if ($upper === 'VARCHAR') return 'VARCHAR(255)';
    if (preg_match('/^VARCHAR\((\d+)\)$/i', $sqliteType, $m)) return 'VARCHAR(' . $m[1] . ')';
    if ($upper === 'TEXT') return 'TEXT';
    if ($upper === 'INTEGER') return 'INT';
    if ($upper === 'NUMERIC') return 'DECIMAL(10,2)';
    if ($upper === 'REAL') return 'DOUBLE';
    if ($upper === 'DATETIME') return 'TIMESTAMP';
    if ($upper === 'DATE') return 'DATE';
    if ($upper === 'TIME') return 'TIME';
    if ($upper === 'TINYINT(1)') return 'TINYINT(1)';
    
    return $sqliteType;
}

// ============================================================
// Escape value for MySQL
// ============================================================
function mysqlEscape($value) {
    if ($value === null) return 'NULL';
    if (is_int($value) || is_float($value)) return (string)$value;
    $value = (string)$value;
    return "'" . addslashes($value) . "'";
}

// ============================================================
// Export each table
// ============================================================
$tableCount = 0;

foreach ($allTables as $tableName) {
    $tableCount++;
    
    // Get columns via PRAGMA table_info
    $cols = [];
    $res = $db->query("PRAGMA table_info(`$tableName`)");
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $cols[] = $row;
    }
    
    if (empty($cols)) continue;
    
    w('');
    w('-- -----------------------------------------------------------');
    w("-- Table: $tableName");
    w('-- -----------------------------------------------------------');
    
    // Get foreign keys
    $fks = [];
    $fkRes = $db->query("PRAGMA foreign_key_list(`$tableName`)");
    while ($fk = $fkRes->fetchArray(SQLITE3_ASSOC)) {
        $fks[] = $fk;
    }
    
    w("DROP TABLE IF EXISTS `$tableName`;");
    
    // Build CREATE TABLE
    $lines = [];
    $pkCol = null;
    $pkCols = [];
    foreach ($cols as $col) {
        if ((int)$col['pk'] > 0) $pkCols[] = $col;
    }
    $isCompositePk = count($pkCols) > 1;
    
    foreach ($cols as $col) {
        $name = $col['name'];
        $type = $col['type'];
        $pk = (int)$col['pk'];
        $notnull = (int)$col['notnull'];
        $dflt = $col['dflt_value'];
        
        $mysqlType = convertType($type);
        
        // Single integer primary key with autoincrement
        if ($pk == 1 && strtoupper($type) === 'INTEGER' && !$isCompositePk) {
            $lines[] = "  `$name` INT NOT NULL AUTO_INCREMENT";
            $pkCol = $name;
            continue;
        }
        
        // Non-integer primary key (e.g., VARCHAR for sessions.id, cache.key)
        if ($pk == 1 && !$isCompositePk) {
            $pkCol = $name;
        }
        
        $parts = [];
        $parts[] = "  `$name` $mysqlType";
        
        // NOT NULL
        if ($notnull == 1) {
            $parts[] = 'NOT NULL';
        } elseif (strpos($mysqlType, 'TIMESTAMP') !== false) {
            $parts[] = 'NULL DEFAULT NULL';
        }
        
        // DEFAULT
        if ($dflt !== null) {
            $dfltUpper = strtoupper($dflt);
            if ($dfltUpper === 'CURRENT_TIMESTAMP') {
                $parts[] = 'DEFAULT CURRENT_TIMESTAMP';
            } elseif ($dfltUpper === 'NULL') {
                // skip
            } elseif (preg_match("/^'(.*)'$/", $dflt, $dm)) {
                // String default - already has quotes from SQLite, strip them
                $val = str_replace("''", "'", $dm[1]);
                $parts[] = "DEFAULT '" . addslashes($val) . "'";
            } elseif (is_numeric($dflt)) {
                $parts[] = 'DEFAULT ' . $dflt;
            } else {
                $parts[] = 'DEFAULT ' . $dflt;
            }
        } elseif ($notnull != 1 && $pk != 1 && strpos($mysqlType, 'TIMESTAMP') === false) {
            // Nullable with no default
            if (strpos($mysqlType, 'TIMESTAMP') !== false) {
                // Already has DEFAULT NULL in type
            } else {
                $parts[] = 'DEFAULT NULL';
            }
        }
        
        $lines[] = implode(' ', $parts);
    }
    
    // Add PRIMARY KEY for AUTO_INCREMENT integer PKs
    if ($pkCol !== null && !$isCompositePk) {
        $hasAutoIncrement = false;
        foreach ($lines as $line) {
            if (strpos($line, 'AUTO_INCREMENT') !== false) {
                $hasAutoIncrement = true;
                break;
            }
        }
        if ($hasAutoIncrement) {
            $lines[] = "  PRIMARY KEY (`$pkCol`)";
        } else {
            // Non-auto integer PK or VARCHAR PK (sessions.id, cache.key, etc.)
            $lines[] = "  PRIMARY KEY (`$pkCol`)";
        }
    }
    
    // Add composite PRIMARY KEY
    if ($isCompositePk) {
        $pkNames = array_map(function($c) { return '`' . $c['name'] . '`'; }, $pkCols);
        $lines[] = "  PRIMARY KEY (" . implode(', ', $pkNames) . ")";
    }
    
    // Add foreign keys
    if (!empty($fks)) {
        $fkStatements = [];
        foreach ($fks as $fk) {
            $from = $fk['from'];
            $table = $fk['table'];
            $to = $fk['to'];
            $onUpdate = isset($fk['on_update']) ? strtoupper($fk['on_update']) : 'NO ACTION';
            $onDelete = isset($fk['on_delete']) ? strtoupper($fk['on_delete']) : 'NO ACTION';
            
            $onUpdateSql = $onUpdate !== 'NO ACTION' ? str_replace('_', ' ', $onUpdate) : '';
            $onDeleteSql = $onDelete !== 'NO ACTION' ? str_replace('_', ' ', $onDelete) : '';
            
            $fkLine = "  FOREIGN KEY (`$from`) REFERENCES `$table`(`$to`)";
            if ($onDeleteSql) $fkLine .= " ON DELETE $onDeleteSql";
            if ($onUpdateSql) $fkLine .= " ON UPDATE $onUpdateSql";
            
            $lines[] = $fkLine;
        }
    }
    
    // Note: FKs are only from PRAGMA foreign_key_list (avoids duplicates)
    
    w("CREATE TABLE `$tableName` (");
    $count = count($lines);
    for ($i = 0; $i < $count; $i++) {
        if ($i < $count - 1) {
            w($lines[$i] . ',');
        } else {
            w($lines[$i]);
        }
    }
    w(") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    
    // Export data
    $rowCount = $db->querySingle("SELECT COUNT(*) FROM `$tableName`");
    if ($rowCount > 0) {
        $dataRes = $db->query("SELECT * FROM `$tableName`");
        $rows = [];
        while ($row = $dataRes->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $row;
        }
        
        if (!empty($rows)) {
            $colNames = array_keys($rows[0]);
            $escapedCols = array_map(function($c) { return "`$c`"; }, $colNames);
            $colList = implode(', ', $escapedCols);
            
            w("INSERT INTO `$tableName` ($colList) VALUES");
            
            $values = [];
            foreach ($rows as $row) {
                $vals = [];
                foreach ($colNames as $col) {
                    $vals[] = mysqlEscape($row[$col]);
                }
                $values[] = '  (' . implode(', ', $vals) . ')';
            }
            
            w(implode(",\n", $values) . ';');
            
            // Set AUTO_INCREMENT
            if (isset($rows[0]['id'])) {
                $maxId = max(array_column($rows, 'id'));
                if ($maxId > 0) {
                    w("ALTER TABLE `$tableName` AUTO_INCREMENT = " . ($maxId + 1) . ";");
                }
            }
        }
    }
    
    if ($tableCount % 10 === 0) {
        fwrite(STDERR, "Processed $tableCount tables...\n");
    }
}

// ============================================================
// Footer
// ============================================================
w('');
w('SET FOREIGN_KEY_CHECKS = 1;');
w('COMMIT;');
w('');
w("-- ============================================================");
w("-- Import complete: $tableCount tables processed");
w("-- ============================================================");

fclose($out);
$db->close();

fwrite(STDERR, "\nDone! Generated: $outputPath\n");
fwrite(STDERR, "Tables: $tableCount\n");
fwrite(STDERR, "File size: " . number_format(filesize($outputPath)) . " bytes\n");
