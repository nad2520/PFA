<?php
declare(strict_types=1);

namespace Tests\UI;

use Tests\Support\CoverageReportPage;
use PHPUnit\Framework\TestCase;

final class CoverageReportIndexHtmlTest extends TestCase
{
    private CoverageReportPage $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = new CoverageReportPage(
            dirname(__DIR__, 2) . '/coverage-report/index.html'
        );
    }

    public function testDocumentMetadataAndTopLevelStructureArePresent(): void
    {
        $html = $this->page->rawHtml();

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertSame('en', $this->page->htmlLanguage());
        $this->assertSame(
            'Code Coverage for C:\xampp\htdocs\PFA\app\services\chatbot',
            $this->page->title()
        );
        $this->assertSame('UTF-8', $this->page->evaluateString('//meta[@charset]/@charset'));
        $this->assertSame(
            'width=device-width, initial-scale=1.0',
            $this->page->evaluateString('//meta[@name="viewport"]/@content')
        );
        $this->assertSame(1, $this->page->countNodes('//header'));
        $this->assertSame(1, $this->page->countNodes('//footer'));
    }

    public function testPageLoadsAllDeclaredStylesheetsAndEachAssetExists(): void
    {
        $expected = [
            '_css/bootstrap.min.css?v=10.1.16',
            '_css/octicons.css?v=10.1.16',
            '_css/style.css?v=10.1.16',
            '_css/custom.css',
        ];

        $this->assertSame($expected, $this->page->stylesheetHrefs());

        foreach ($expected as $href) {
            $this->assertReportPathExists($href);
        }
    }

    public function testBreadcrumbNavigationMatchesTheReportContext(): void
    {
        $this->assertSame('C:\xampp\htdocs\PFA\app\services\chatbot', $this->page->breadcrumbCurrentItem());

        $dashboard = $this->page->dashboardBreadcrumb();
        $this->assertSame('Dashboard', $dashboard['label']);
        $this->assertSame('dashboard.html', $dashboard['href']);
        $this->assertSame(1, $this->page->countNodes('//nav[@aria-label="breadcrumb"]'));
    }

    public function testCoverageTableHeadersAndSummaryValuesAreFullyRendered(): void
    {
        $this->assertSame(1, $this->page->countNodes('//table[contains(@class, "table")]'));
        $this->assertSame('Code Coverage', $this->page->evaluateString('//thead/tr[1]/td[2]//strong'));
        $this->assertSame('Lines', $this->page->evaluateString('//thead/tr[2]/td[2]//strong'));
        $this->assertSame('Functions and Methods', $this->page->evaluateString('//thead/tr[2]/td[3]//strong'));
        $this->assertSame('Classes and Traits', $this->page->evaluateString('//thead/tr[2]/td[4]//strong'));

        $this->assertSame('Total', $this->page->evaluateString('//tbody/tr[1]/td[1]'));
        $this->assertSame('100.00%', $this->page->evaluateString('//tbody/tr[1]/td[3]'));
        $this->assertSame('147 / 147', $this->normalizeWhitespace($this->page->evaluateString('//tbody/tr[1]/td[4]')));
        $this->assertSame('100.00%', $this->page->evaluateString('//tbody/tr[1]/td[6]'));
        $this->assertSame('43 / 43', $this->normalizeWhitespace($this->page->evaluateString('//tbody/tr[1]/td[7]')));
        $this->assertSame('100.00%', $this->page->evaluateString('//tbody/tr[1]/td[9]'));
        $this->assertSame('9 / 9', $this->normalizeWhitespace($this->page->evaluateString('//tbody/tr[1]/td[10]')));
    }

    public function testAllCoverageRowsAppearWithExpectedTargetsAndIcons(): void
    {
        $expectedRows = [
            ['name' => 'Contracts', 'href' => 'Contracts/index.html', 'cssClass' => '', 'iconSource' => '_icons/file-directory.svg'],
            ['name' => 'Entity', 'href' => 'Entity/index.html', 'cssClass' => 'success', 'iconSource' => '_icons/file-directory.svg'],
            ['name' => 'Exceptions', 'href' => 'Exceptions/index.html', 'cssClass' => '', 'iconSource' => '_icons/file-directory.svg'],
            ['name' => 'Infrastructure', 'href' => 'Infrastructure/index.html', 'cssClass' => 'success', 'iconSource' => '_icons/file-directory.svg'],
            ['name' => 'Security', 'href' => 'Security/index.html', 'cssClass' => 'success', 'iconSource' => '_icons/file-directory.svg'],
            ['name' => 'ValueObject', 'href' => 'ValueObject/index.html', 'cssClass' => 'success', 'iconSource' => '_icons/file-directory.svg'],
            ['name' => 'ChatbotService.php', 'href' => 'ChatbotService.php.html', 'cssClass' => 'success', 'iconSource' => '_icons/file-code.svg'],
        ];

        $this->assertSame($expectedRows, $this->page->coverageRows());

        foreach ($expectedRows as $row) {
            $this->assertReportPathExists($row['href']);
            $this->assertReportPathExists($row['iconSource']);
        }
    }

    public function testEntityCoveragePageShowsChatbotClassesAndTraitsAsFullyCovered(): void
    {
        $entityPage = new CoverageReportPage(
            dirname(__DIR__, 2) . '/coverage-report/Entity/index.html'
        );

        $this->assertSame('100.00%', $entityPage->evaluateString('//tbody/tr[1]/td[3]'));
        $this->assertSame('2 / 2', $this->normalizeWhitespace($entityPage->evaluateString('//tbody/tr[1]/td[10]')));
        $this->assertSame(
            [
                ['name' => 'ChatMessage.php', 'href' => 'ChatMessage.php.html', 'cssClass' => 'success', 'iconSource' => '../_icons/file-code.svg'],
                ['name' => 'Conversation.php', 'href' => 'Conversation.php.html', 'cssClass' => 'success', 'iconSource' => '../_icons/file-code.svg'],
            ],
            $entityPage->coverageRows()
        );
    }

    public function testProgressBarsExposeAccessibleCoverageSemantics(): void
    {
        $bars = $this->page->progressBars();

        $this->assertCount(18, $bars);

        foreach ($bars as $bar) {
            $this->assertMatchesRegularExpression('/\bbg-(success|warning|danger)\b/', $bar['class']);
            $this->assertMatchesRegularExpression('/^\d+\.\d{2}$/', $bar['ariaValueNow']);
            $this->assertStringContainsString('width: ' . $bar['ariaValueNow'] . '%', $bar['style']);
            $this->assertStringContainsString($bar['ariaValueNow'] . '% covered', $bar['srOnly']);
        }
    }

    public function testAllLocalLinksAndAssetsResolveInsideTheCoverageReportFolder(): void
    {
        foreach ($this->page->localAssetReferences() as $reference) {
            $this->assertReportPathExists($reference);
        }
    }

    public function testFooterLegendAndGenerationMetadataAreCompletelyRendered(): void
    {
        $this->assertSame(
            [
                'Low: 0% to 50%',
                'Medium: 50% to 90%',
                'High: 90% to 100%',
            ],
            $this->page->legendTexts()
        );

        $metadata = $this->page->footerMetadata();
        $this->assertSame('php-code-coverage 10.1.16', $metadata['generatedBy']);
        $this->assertSame('PHP 8.1.25', $metadata['php']);
        $this->assertSame('PHPUnit 10.5.63', $metadata['phpunit']);
        $this->assertStringContainsString('Generated by php-code-coverage 10.1.16', $metadata['fullText']);
        $this->assertMatchesRegularExpression(
            '/at\s+[A-Z][a-z]{2}\s+[A-Z][a-z]{2}\s+\d{1,2}\s+\d{2}:\d{2}:\d{2}\s+[A-Z]{4}\s+\d{4}\.$/',
            $metadata['fullText']
        );
    }

    public function testNoUnimplementedDynamicUiControlsArePresent(): void
    {
        $this->assertSame(0, $this->page->countNodes('//script'));
        $this->assertSame(0, $this->page->countNodes('//form'));
        $this->assertSame(0, $this->page->countNodes('//button'));
        $this->assertSame(0, $this->page->countNodes('//input'));
        $this->assertSame(0, $this->page->countNodes('//select'));
        $this->assertSame(0, $this->page->countNodes('//textarea'));
    }

    private function assertReportPathExists(string $relativePath): void
    {
        $cleanPath = preg_replace('/\?.*/', '', $relativePath) ?? $relativePath;
        $absolutePath = $this->page->baseDirectory() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);

        $this->assertFileExists($absolutePath, sprintf('Referenced asset missing: %s', $relativePath));
    }

    private function normalizeWhitespace(string $value): string
    {
        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $decoded = str_replace("\u{00A0}", ' ', $decoded);

        return trim(preg_replace('/[\s\p{Z}]+/u', ' ', $decoded) ?? '');
    }
}
