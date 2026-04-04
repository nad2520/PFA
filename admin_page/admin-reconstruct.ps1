$base_dir = "c:\nadine\template\LEXORA_OFFICIEL\HELP\landing_page\LEXORA_FINAL\admin_page"
$html = [IO.File]::ReadAllText("$base_dir\base-layout.html", [Text.Encoding]::UTF8)

$components = @("sidebar", "header", "dashboard", "users", "books", "community", "lumo", "age", "rewards", "settings")

foreach ($c in $components) {
    $comp_path = "$base_dir\components\$c\index.html"
    if (Test-Path $comp_path) {
        $comp_html = [IO.File]::ReadAllText($comp_path, [Text.Encoding]::UTF8)
        $html = $html -replace "<!-- COMPONENT: $c -->", $comp_html
    }
}

# Update CSS and JS routing to local component architecture
$html = $html -replace '<link rel="stylesheet" href="scss/admin.css">', '<link rel="stylesheet" href="common/styles/admin-bundle.css">'
$html = $html -replace '<script src="js/admin.js"></script>', '<script src="common/scripts/global.js"></script>'

[IO.File]::WriteAllText("$base_dir\admin.html", $html, [Text.Encoding]::UTF8)
Write-Host "Admin Page successfully reconstructed from components!"