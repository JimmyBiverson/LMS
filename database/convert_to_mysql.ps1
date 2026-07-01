$inputFile = "database/lms_dump.sql"
$outputFile = "database/lms_mysql.sql"

if (-not (Test-Path $inputFile)) {
    Write-Error "Input file not found: $inputFile"
    exit 1
}

$lines = Get-Content $inputFile
$bt = [char]0x60
$outputLines = [System.Collections.ArrayList]::new()

# Header
[void]$outputLines.Add("-- MySQL dump converted from SQLite")
[void]$outputLines.Add("-- Target: MySQL 8.0+ / MariaDB 10.3+")
[void]$outputLines.Add("--")
[void]$outputLines.Add("SET FOREIGN_KEY_CHECKS = 0;")
[void]$outputLines.Add("SET UNIQUE_CHECKS = 0;")
[void]$outputLines.Add("START TRANSACTION;")
[void]$outputLines.Add("")

foreach ($line in $lines) {
    # Process only non-empty lines
    if ([string]::IsNullOrWhiteSpace($line)) {
        [void]$outputLines.Add($line)
        continue
    }

    # Check if this is an INSERT statement — replace only table name, skip VALUES data
    # SQLite dump always uses format: INSERT INTO "table" VALUES (...)
    if ($line -match '^(INSERT INTO )"([\w_]+)"( VALUES.*)$') {
        $prefix = $matches[1]
        $tableName = $matches[2]
        $suffix = $matches[3]
        [void]$outputLines.Add("${prefix}${bt}${tableName}${bt}${suffix}")
        continue
    }

    # For INSERT lines that don't match the pattern (edge cases)
    if ($line -match '^INSERT INTO ') {
        # Fallback: just replace table name
        $line = $line -replace '^INSERT INTO "([\w_]+)"', "INSERT INTO ${bt}`$1${bt}"
        [void]$outputLines.Add($line)
        continue
    }

    # For CREATE TABLE lines — replace all identifiers outside single-quoted strings
    # Also add DROP TABLE IF EXISTS and ENGINE clause
    if ($line -match '^CREATE TABLE "([\w_]+)"') {
        $tableName = $matches[1]

        # Add DROP TABLE IF EXISTS
        [void]$outputLines.Add("DROP TABLE IF EXISTS ${bt}${tableName}${bt};")

        # Process the CREATE TABLE line: replace identifiers outside single quotes
        $parts = $line -split "'"
        $processed = ""
        for ($i = 0; $i -lt $parts.Count; $i++) {
            if ($i % 2 -eq 0) {
                # Outside quotes — replace "word" with `word`
                $parts[$i] = $parts[$i] -replace '"([\w_]+)"', "${bt}`$1${bt}"
            }
            $processed += $parts[$i]
            if ($i -lt $parts.Count - 1) {
                $processed += "'"
            }
        }

        # Fix default ('value') → default 'value' (parens not needed for scalar)
        $processed = $processed -replace "default\s+\('([^']*)'\)", "default '`$1'"
        # Backtick-quote bare table names in foreign key REFERENCES (only those not already quoted)
        $processed = $processed -replace '(?i)(\breferences\s+)(categories|users|courses)(\()', "`$1${bt}`$2${bt}`$3"

        # Replace autoincrement with AUTO_INCREMENT
        $processed = $processed -replace '(?i)\bautoincrement\b', 'AUTO_INCREMENT'
        # Replace varchar (without length) with varchar(255)
        $processed = $processed -replace '(?i)\bvarchar\b(?!\s*\()', 'varchar(255)'
        # Replace numeric with decimal(8,2)
        $processed = $processed -replace '(?i)\bnumeric\b', 'decimal(8,2)'

        # Add ENGINE clause
        $processed = $processed -replace '\);$', ') ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;'

        [void]$outputLines.Add($processed)
        continue
    }

    # Other lines (empty lines, standalone comments, etc.) — 
    # replace identifiers outside single-quoted strings only if they look like identifiers
    # Skip lines that don't contain double-quoted words
    if ($line -match '"([\w_]+)"') {
        $parts = $line -split "'"
        $processed = ""
        for ($i = 0; $i -lt $parts.Count; $i++) {
            if ($i % 2 -eq 0) {
                # Outside quotes — replace "word" with `word`
                $parts[$i] = $parts[$i] -replace '"([\w_]+)"', "${bt}`$1${bt}"
            }
            $processed += $parts[$i]
            if ($i -lt $parts.Count - 1) {
                $processed += "'"
            }
        }
        [void]$outputLines.Add($processed)
    } else {
        [void]$outputLines.Add($line)
    }
}

# Footer
[void]$outputLines.Add("")
[void]$outputLines.Add("COMMIT;")
[void]$outputLines.Add("SET UNIQUE_CHECKS = 1;")
[void]$outputLines.Add("SET FOREIGN_KEY_CHECKS = 1;")

$outputLines -join "`r`n" | Set-Content -Path $outputFile -NoNewline -Encoding UTF8

Write-Output "Conversion complete: $outputFile"
Write-Output "$($outputLines.Count) lines written"
