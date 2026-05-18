$ErrorActionPreference = 'Stop'

$repoRoot = Resolve-Path (Join-Path $PSScriptRoot '..')
$coverageDir = Join-Path $repoRoot 'coverage'
$coverageText = Join-Path $coverageDir 'coverage.txt'
$coverageClover = Join-Path $coverageDir 'clover.xml'
$phpunitConfig = Join-Path $repoRoot 'phpunit.filtering.xml'
$nodeTestFile = Join-Path $repoRoot 'tests\Filtering\UI\catalogFiltering.test.mjs'
$coverageThreshold = 85.0

function Get-PhpUnitCommand {
    $candidates = @(
        (Join-Path $repoRoot 'vendor\bin\phpunit.bat'),
        (Join-Path $repoRoot 'vendor\bin\phpunit'),
        (Join-Path $repoRoot 'TP5\travel\vendor\bin\phpunit.bat'),
        (Join-Path $repoRoot 'TP5\travel\vendor\bin\phpunit')
    )

    foreach ($candidate in $candidates) {
        if (-not (Test-Path $candidate)) {
            continue
        }

        & $candidate --version *> $null
        if ($LASTEXITCODE -eq 0) {
            return $candidate
        }
    }

    throw "Could not find a working PHPUnit executable."
}

function Test-CoverageDriver {
    $modules = (& php -m) | ForEach-Object { $_.Trim() }
    if ($modules -contains 'xdebug') {
        $env:XDEBUG_MODE = 'coverage'
        return 'Xdebug'
    }
    if ($modules -contains 'pcov') {
        $env:PCOV_ENABLED = '1'
        return 'PCOV'
    }

    throw 'No coverage driver detected. Enable Xdebug or PCOV before running filtering tests.'
}

function Get-LineCoveragePercent {
    param(
        [Parameter(Mandatory = $true)]
        [string]$CoverageFile
    )

    if (-not (Test-Path $CoverageFile)) {
        throw "Coverage summary file not found: $CoverageFile"
    }

    $content = Get-Content $CoverageFile -Raw
    $match = [regex]::Match($content, 'Lines:\s+([0-9]+(?:\.[0-9]+)?)%')
    if (-not $match.Success) {
        throw 'Could not parse line coverage percentage from coverage report.'
    }

    return [double]$match.Groups[1].Value
}

try {
    Set-Location $repoRoot

    if (Test-Path $coverageDir) {
        Remove-Item -LiteralPath $coverageDir -Recurse -Force
    }
    New-Item -ItemType Directory -Force -Path $coverageDir | Out-Null

    $phpunit = Get-PhpUnitCommand
    $coverageDriver = Test-CoverageDriver

    Write-Host "Running PHP filtering tests with coverage via $coverageDriver..."
    & $phpunit `
        --configuration $phpunitConfig `
        --coverage-html $coverageDir `
        --coverage-clover $coverageClover `
        --coverage-text=$coverageText `
        --show-uncovered-for-coverage-text

    if ($LASTEXITCODE -ne 0) {
        throw "PHP filtering tests failed with exit code $LASTEXITCODE."
    }

    Get-Content $coverageText
    $lineCoverage = Get-LineCoveragePercent -CoverageFile $coverageText
    if ($lineCoverage -lt $coverageThreshold) {
        throw ("Filtering coverage is below target: {0:N2}% < {1:N2}%." -f $lineCoverage, $coverageThreshold)
    }

    Write-Host ''
    Write-Host 'Running catalog UI filtering tests...'
    & node --test $nodeTestFile

    if ($LASTEXITCODE -ne 0) {
        throw "Catalog UI filtering tests failed with exit code $LASTEXITCODE."
    }

    Write-Host ''
    Write-Host ("Filtering test suite passed. Coverage report generated in '{0}' with line coverage {1:N2}%." -f $coverageDir, $lineCoverage) -ForegroundColor Green
    exit 0
}
catch {
    Write-Host ''
    Write-Host ("Filtering test suite failed: {0}" -f $_.Exception.Message) -ForegroundColor Red
    exit 1
}
